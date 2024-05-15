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
        Schema::create('inward', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('item_id');
            $table->integer('account_id');
            $table->string('vehicle_no', 250);
            $table->enum('vehicle_status', ['pending ','loading','completed']);
            $table->integer('no_of_bags')->nullable();
            $table->integer('fare')->nullable();
            $table->integer('bilty_no')->nullable();
            $table->integer('gp_no')->nullable();
            $table->integer('company_weight')->nullable();
            $table->integer('party_weight')->nullable();
            $table->integer('first_weight')->nullable();
            $table->integer('second_weight')->nullable();
            $table->integer('weight_difference')->nullable();
            $table->integer('party_weight_difference')->nullable();
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
        Schema::dropIfExists('inward');
    }
};
