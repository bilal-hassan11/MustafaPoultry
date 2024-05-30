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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('grand_parent_id');
            $table->integer('parent_id');
            $table->string('name', 250);
            $table->float('opening_balance');
            $table->date('opening_date');
            $table->enum('account_nature', ['credit','debit']);
            $table->integer('ageing');
            $table->tinyInteger('commission');
            $table->tinyInteger('discount');
            $table->text('address')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 means active and 0 means deactive');
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
        Schema::dropIfExists('accounts');
    }
};
