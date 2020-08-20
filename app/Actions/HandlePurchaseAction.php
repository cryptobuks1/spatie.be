<?php

namespace App\Actions;

use App\Models\Purchasable;
use App\Models\Purchase;
use App\Models\User;
use App\Support\Paddle\PaddlePayload;
use Laravel\Paddle\Receipt;

class HandlePurchaseAction
{
    protected HandlePurchaseLicensingAction $handlePurchaseLicensingAction;

    public function __construct(HandlePurchaseLicensingAction $handlePurchaseLicensingAction)
    {
        $this->handlePurchaseLicensingAction = $handlePurchaseLicensingAction;
    }

    public function execute(User $user, Purchasable $purchasable, PaddlePayload $paddlePayload): Purchase
    {
        $purchase = $this->createPurchase($user, $purchasable, $paddlePayload);

        $purchase = $this->handlePurchaseLicensingAction->execute($purchase);

        return $purchase;
    }

    protected function createPurchase(User $user, Purchasable $purchasable, PaddlePayload $paddlePayload): Purchase
    {
        $receipt = Receipt::where('order_id', $paddlePayload->order_id)->first();

        return Purchase::create([
            'license_id' => null,
            'user_id' => $user->id,
            'purchasable_id' => $purchasable->id,
            'receipt_id' => $receipt->id,
            'paddle_webhook_payload' => $paddlePayload,
            'paddle_fee' => $paddlePayload->fee,
            'earnings' => $paddlePayload->earnings,
        ]);
    }
}
