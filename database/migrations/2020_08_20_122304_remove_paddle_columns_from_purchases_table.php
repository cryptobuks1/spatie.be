<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePaddleColumnsFromPurchasesTable extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'receipt_url',
                'paddle_webhook_payload',
                'paddle_fee',
                'payment_tax',
                'earnings',
                'paddle_alert_id',
            ]);

            $table->foreignId('receipt_id')->after('license_id');

            $table->foreign('receipt_id')->references('id')->on('receipts');
        });
    }
}
