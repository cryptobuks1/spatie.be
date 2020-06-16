<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->string('slug');
            $table->integer('price');
            $table->text('description');
            $table->string('url');
            $table->string('action_url');
            $table->string('action_label');
            $table->integer('sort_order');
            $table->boolean('requires_license')->default(0);
            $table->string('paddle_product_id');
            $table->timestamps();
        });
    }
}
