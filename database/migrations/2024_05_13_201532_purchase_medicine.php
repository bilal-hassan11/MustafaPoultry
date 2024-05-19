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
        Schema::create('purchase_medicine', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('inv_no');
            $table->integer('item_id');
            $table->integer('account_id');
            $table->string('quantity');
            $table->integer('rate')->default(0);
            $table->integer('discount_in_rs')->default(0);
            $table->integer('discount_in_percentage')->default(0);    
            $table->integer('net_ammount')->default(0);
            $table->date('expiry_date')->nullable();
            $table->tinyInteger('expiry_status')->default(1)->comment('1 means active 0 means deactive');
            $table->integer('other_charges')->default(0);
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('purchase_medicine');
    }
};