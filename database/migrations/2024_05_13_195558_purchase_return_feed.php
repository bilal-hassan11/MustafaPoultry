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
        Schema::create('purchase_return_feed', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('inv_no');
            $table->integer('item_id');
            $table->integer('account_id');
            $table->string('no_of_bags');
            $table->integer('rate')->default(0);
            $table->integer('dedection_in_rs')->default(0);
            $table->integer('dedection_in_percentage')->default(0);    
            $table->integer('net_ammount')->default(0);
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
        Schema::dropIfExists('purchase_return_feed');
    }
};
