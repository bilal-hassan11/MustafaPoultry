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
        Schema::create('expiry_stock', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('ref_no')->nullable();
            $table->integer('stock_id');
            $table->integer('item_id');
            $table->integer('quantity')->default(0);
            $table->integer('rate')->default(0);
            $table->date('expiry_date');
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
        Schema::dropIfExists('expiry_stock');
    }
};
