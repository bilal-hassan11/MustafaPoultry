<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToMedicineInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medicine_invoices', function (Blueprint $table) {
            $table->string('transport_name')->nullable()->after('description');
            $table->string('vehicle_no')->nullable()->after('transport_name');
            $table->string('driver_name')->nullable()->after('vehicle_no');
            $table->string('contact_no')->nullable()->after('driver_name');
            $table->string('builty_no')->nullable()->after('contact_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medicine_invoices', function (Blueprint $table) {
            $table->dropColumn('transport_name');
            $table->dropColumn('vehicle_no');
            $table->dropColumn('driver_name');
            $table->dropColumn('contact_no');
            $table->dropColumn('builty_no');
        });
    }
}
