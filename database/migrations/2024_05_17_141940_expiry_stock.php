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
            $table->unsignedBigInteger('medicine_invoice_id')->nullable();
            $table->unsignedBigInteger('item_id');
            $table->decimal('rate',10,2)->default(0.00);
            $table->decimal('quantity',10,2)->default(0);
            $table->date('expiry_date')->nullable();
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