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
        Schema::create('purchase_murghi', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('inv_no');
            $table->integer('item_id');
            $table->integer('account_id');
            $table->string('vehicle_no');
            $table->string('no_of_crate');
            $table->integer('rate')->default(0);
            $table->integer('rate_detection')->default(0);
            $table->integer('final_rate')->default(0);    
            $table->integer('gross_weight')->default(0);
            $table->integer('weight_detection')->default(0);
            $table->integer('net_weight')->default(0);
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
        Schema::dropIfExists('purchase_murghi');
    }
};
