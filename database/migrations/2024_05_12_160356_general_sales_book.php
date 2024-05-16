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
        Schema::create('general_sales_book', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('inv_no');
            $table->integer('item_id');
            $table->integer('account_id');
            $table->string('vehicle_no');
            $table->integer('rate')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('net_ammount')->nullable();
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
        Schema::dropIfExists('general_sales_book');
    }
};
