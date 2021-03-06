<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Mailcoach\Models\EmailList;
use Spatie\Mailcoach\Models\Subscriber;

class ImportFromMailCoachDatabaseCommand extends Command
{
    protected $signature = 'mailcoach:import-list {connection} {listId} {--name=} {--tag=*}';

    protected $description = 'Imports a Mailcoach list, subscribers and relevant tags from a separate database.';

    public function handle()
    {
        $connection = $this->argument('connection');

        if (! config("database.connections.{$connection}")) {
            $this->error("Database connection {$connection} does not exist.");

            return;
        }

        $listId = $this->argument('listId');

        $list = DB::connection($connection)->selectOne('SELECT * FROM `mailcoach_email_lists` WHERE id = ?', [$listId]);

        if (! $list) {
            $this->error("List with id {$listId} does not exist on {$connection}.");

            return;
        }

        $subscribers = DB::connection($connection)
            ->selectOne('SELECT COUNT(*) AS count FROM `mailcoach_subscribers` WHERE email_list_id = ?', [$listId]);

        $newName = $this->option('name') ?? $list->name;

        if (! $this->confirm("You are about to import `{$list->name}` with {$subscribers->count} subscribers from {$connection} to a list named {$newName}.")) {
            return;
        }

        /** @var EmailList $emailList */
        $emailList = EmailList::firstOrCreate(['name' => $newName]);

        $progress = $this->output->createProgressBar($subscribers->count);

        $tags = collect(DB::connection($connection)->select('SELECT * FROM `mailcoach_tags`'))->pluck('name', 'id');

        $subscriberTags = collect(DB::connection($connection)
            ->select('SELECT * FROM `mailcoach_email_list_subscriber_tags`'));

        collect(DB::connection($connection)
            ->select('SELECT * FROM `mailcoach_subscribers` WHERE email_list_id = ?', [$listId]))
            ->each(function ($subscriber) use ($tags, $subscriberTags, $progress, $emailList) {
                $progress->advance();

                if ($emailList->getSubscriptionStatus($subscriber->email)) {
                    $newSubscriber = Subscriber::findForEmail($subscriber->email, $emailList);

                    foreach ($this->option('tag') as $tag) {
                        $newSubscriber->addTag($tag);
                    }

                    return;
                }

                $newSubscriber = $emailList->subscribeSkippingConfirmation($subscriber->email, [
                    'first_name' => $subscriber->first_name,
                    'last_name' => $subscriber->last_name,
                    'extra_attributes' => $subscriber->extra_attributes,
                    'subscribed_at' => $subscriber->subscribed_at,
                    'unsubscribed_at' => $subscriber->unsubscribed_at,
                    'created_at' => $subscriber->created_at,
                    'updated_at' => $subscriber->updated_at,
                ]);

                $subscriberTags->where('subscriber_id', $subscriber->id)
                    ->each(function ($subscriberTag) use ($tags, $newSubscriber) {
                        $newSubscriber->addTag($tags[$subscriberTag->tag_id]);
                    });

                foreach ($this->option('tag') as $tag) {
                    $newSubscriber->addTag($tag);
                }
            });

        $progress->finish();
    }
}
