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
        Schema::create('sales_book', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('gp_no', 250);
            $table->integer('item_id');
            $table->integer('account_id');
            $table->string('sub_dealer_name', 250);
            $table->string('vehicle_no', 250);
            $table->enum('vehicle_status', ['pending ','completed']);
            $table->integer('bag_rate')->nullable();
            $table->integer('no_of_bags')->nullable();
            $table->integer('commission')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('fare')->nullable();
            $table->integer('bilty_no')->nullable();
            $table->integer('net_ammount')->nullable();
            $table->enum('sale_status', ['pending','rejected','completed']);
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
        Schema::dropIfExists('sales_book');
    }
};
