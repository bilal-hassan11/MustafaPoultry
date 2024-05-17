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
        Schema::create('chick_invoices', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('inv_no');
            $table->string('ref_no')->nullable();
            $table->integer('account_id');
            $table->integer('item_id');
            $table->string('unit');
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('sale_price', 10, 2);
            $table->date('expiry_date')->nullable();
            $table->integer('rate')->default(0);
            $table->integer('quantity')->default(0);
            $table->integer('ammount')->default(0);
            $table->integer('discount_in_rs')->default(0);
            $table->integer('discount_in_percentage')->default(0);    
            $table->integer('net_ammount')->default(0);
            $table->enum('type', ['purchase','sale','purchase_return','sale_return','adjust_in','adjust_out'])->default('active');
            $table->enum('status', ['active','not_active'])->default('active');
            $table->enum('whatsapp_status', ['send','not_send'])->default('send');
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
        Schema::dropIfExists('chick_invoices');
    }
};
