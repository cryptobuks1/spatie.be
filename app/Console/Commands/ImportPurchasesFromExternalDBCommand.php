<?php

namespace App\Console\Commands;

use App\Models\License;
use App\Models\Product;
use App\Models\Purchasable;
use App\Models\Purchase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Paddle\Receipt;

class ImportPurchasesFromExternalDBCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:purchases {db}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import purchases from an external DB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $purchases = DB::connection($this->argument('db'))
            ->table('purchases')
            ->leftJoin('users', 'purchases.user_id', '=', 'users.id')
            ->leftJoin('products', 'products.id', '=', 'purchases.product_id')
            ->leftJoin('licenses', 'licenses.id', '=', 'purchases.license_id')
            ->select(
                'purchases.*',
                DB::raw('users.email as user_email'),
                DB::raw('users.password as user_password'),
                DB::raw('users.name as user_name'),
                DB::raw('users.github_username as user_github_username'),
                DB::raw('users.github_id as user_github_id'),
                DB::raw('products.paddle_product_id as product_paddle_id'),
                DB::raw('products.name as product_name'),
                DB::raw('products.type as product_type'),
                DB::raw('licenses.uuid as license_uuid'),
                DB::raw('licenses.domain as license_domain'),
                DB::raw('licenses.key as license_key'),
                DB::raw('licenses.expires_at as license_expires_at')
            )
            ->get();

        $this->getOutput()->progressStart(count($purchases));

        foreach ($purchases as $purchase) {
            /** @var User $user */
            $user = User::firstOrCreate([
                'email' => $purchase->user_email,
            ], [
                'name' => $purchase->user_name,
                'password' => $purchase->user_password,
                'github_username' => $purchase->user_github_username,
                'github_id' => $purchase->user_github_id,
            ]);

            $purchasable = Purchasable::query()
                ->where('title', $purchase->product_name)
                ->where('paddle_product_id', $purchase->product_paddle_id)
                ->first();

            if (! $purchasable) {
                $product = Product::firstOrCreate([
                    'title' => $purchase->product_name,
                ], [
                    'description' => '',
                    'url' => '',
                    'action_url' => '',
                    'action_label' => '',
                    'slug' => Str::slug($purchase->product_name),
                ]);

                $purchasable = Purchasable::create([
                    'paddle_product_id' => $purchase->product_paddle_id,
                    'product_id' => $product->id,
                    'title' => $purchase->product_name,
                    'description' => '',
                    'type' => $purchase->product_type,
                ]);
            }

            if ($purchase->license_key) {
                $license = License::create([
                    'user_id' => $user->id,
                    'purchasable_id' => $purchasable->id,
                    'key' => $purchase->license_key,
                    'expires_at' => $purchase->license_expires_at,
                ]);
            }

            $payload = json_decode($purchase->paddle_webhook_payload, true);

            $receipt = Receipt::firstOrCreate([
                'order_id' => $payload['order_id'],
            ], [
                'billable_id' => $user->id,
                'billable_type' => $user->getMorphClass(),
                'paddle_subscription_id' => $payload['subscription_id'] ?? null,
                'checkout_id' => $payload['checkout_id'],
                'amount' => $payload['sale_gross'],
                'tax' => $payload['payment_tax'],
                'currency' => $payload['currency'],
                'quantity' => (int) $payload['quantity'],
                'receipt_url' => $payload['receipt_url'],
                'paid_at' => Carbon::createFromFormat('Y-m-d H:i:s', $payload['event_time'], 'UTC'),
            ]);

            Purchase::create([
                'user_id' => $user->id,
                'purchasable_id' => $purchasable->id,
                'license_id' => isset($license) ? $license->id : null,
                'receipt_id' => $receipt->id,
            ]);

            $this->getOutput()->progressAdvance(1);
        }

        $this->getOutput()->progressFinish();
    }
}
