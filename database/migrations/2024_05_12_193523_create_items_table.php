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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->integer('company_id');
            $table->string('name', 255);
            $table->string('unit', 255);
            $table->string('primary_unit', 255);
            $table->double('price', 10, 2);
            $table->double('discount',  10, 2);
            $table->double('purchase_price',  10, 2);
            $table->double('sale_price',  10, 2);
            $table->double('opening_stock',  10, 2);
            $table->double('stock_qty',  10, 2);  
            $table->enum('type', ['purchase', 'sale']);
            $table->tinyInteger('stock_status')->default(1)->comment('1 means enabled 0 means disabled');
            $table->tinyInteger('status')->default('1')->comment('1 means active 0 means deactive');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('items');
    }
};
