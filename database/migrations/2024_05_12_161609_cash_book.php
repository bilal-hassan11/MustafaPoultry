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
        Schema::create('cash_book', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date');
            $table->integer('account_id');
            $table->text('narration');
            $table->integer('bil_no');
            $table->integer('payment_ammount')->nullable();
            $table->integer('receipt_ammount')->nullable();
            $table->enum('status', ['payment','receipt']);
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
        Schema::dropIfExists('cash_book');
    }
};
