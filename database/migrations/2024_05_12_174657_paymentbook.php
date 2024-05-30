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
        Schema::create('payment_book', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('creditor_account_id');
            $table->integer('credit_ammount');
            $table->integer('debtor_account_id');
            $table->integer('debtor_ammount')->nullable();
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
        Schema::dropIfExists('payment_book');
    }
};
