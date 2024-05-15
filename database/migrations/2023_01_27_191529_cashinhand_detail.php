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
        Schema::table('cash_in_hand_detail', function (Blueprint $table) {
            
            $table->date('date');
            $table->integer('cash_id');
            $table->integer('cash_in_hand');
            $table->integer('total_debit');
            $table->integer('total_credit');
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
        Schema::dropIfExists('cash_in_hand_detail');
    }
};
