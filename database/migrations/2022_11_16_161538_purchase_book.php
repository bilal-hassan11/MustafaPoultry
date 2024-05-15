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
        Schema::create('purchase_book', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('pro_inv_no', 250);
            $table->integer('item_id');
            $table->integer('account_id');
            $table->string('vehicle_no', 250);
            $table->enum('vehicle_status', ['pending ','completed']);
            $table->integer('bag_rate')->nullable();
            $table->integer('no_of_bags')->nullable();
            $table->integer('commission')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('fare')->nullable();
            $table->integer('bilty_no')->nullable();
            $table->integer('other_charges')->nullable();
            $table->integer('company_weight')->nullable();
            $table->integer('party_weight')->nullable();
            $table->integer('weight_difference')->nullable();
            $table->integer('posted_weight')->nullable();
            $table->integer('net_weight')->nullable();
            $table->integer('net_ammount')->nullable();
            $table->enum('purchase_status', ['pending','rejected','completed']);
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
        Schema::dropIfExists('purchase_book');
    }
};
