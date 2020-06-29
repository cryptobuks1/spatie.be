<?php

namespace App\Actions;

use App\Models\User;
use Spatie\Mailcoach\Models\EmailList;

class UnsubscribeUserFromNewsletterAction
{
    public function execute(User $user): User
    {
        /** @todo how do we get the specific list? */
        $emailList = EmailList::first();

        if ($emailList->isSubscribed($user->email)) {
            $emailList->unsubscribe($user->email);
        }

        return $user;
    }
}
