<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_ledger', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id');
            $table->integer('item_id');
            $table->integer('general_sale_id')->nullable();
            $table->integer('general_purchase_id')->nullable();
            $table->integer('cash_id')->nullable();
            $table->integer('payment_id')->nullable();
            $table->integer('expense_id')->nullable();
            $table->integer('purchase_chick_id')->nullable();
            $table->integer('sale_chick_id')->nullable();
            $table->integer('purchase_murghi_id')->nullable();
            $table->integer('sale_murghi_id')->nullable();
            $table->integer('purchase_feed_id')->nullable();
            $table->integer('sale_feed_id')->nullable();
            $table->integer('purchase_return_feed_id')->nullable();
            $table->integer('sale_return_feed_id')->nullable();
            $table->integer('purchase_medicine_id')->nullable();
            $table->integer('sale_medicine_id')->nullable();
            $table->integer('expire_medicine_id')->nullable();
            $table->integer('purchase_return_medicine_id')->nullable();
            $table->integer('sale_return_medicine_id')->nullable();
            $table->integer('item_adjustment_id')->nullable();
            $table->integer('debit')->nullable();
            $table->integer('credit')->nullable();
            $table->string('narration')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_ledger');
    }
};
