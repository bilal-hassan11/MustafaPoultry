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
            $table->integer('sale_id')->nullable();
            $table->integer('purchase_id')->nullable();
            $table->integer('debit')->nullable();
            $table->integer('credit')->nullable();
            $table->integer('total_ammount')->nullable();
            $table->integer('due_ammount')->nullable();
            $table->string('description',250)->nullable();
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
