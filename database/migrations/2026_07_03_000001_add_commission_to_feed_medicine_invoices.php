<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('feed_invoices', function (Blueprint $table) {
            $table->double('commission_percent', 10, 2)->default(0)->after('discount_in_percent');
            $table->double('commission_amount', 10, 2)->default(0)->after('commission_percent');
        });

        Schema::table('medicine_invoices', function (Blueprint $table) {
            $table->double('commission_percent', 10, 2)->default(0)->after('discount_in_percent');
            $table->double('commission_amount', 10, 2)->default(0)->after('commission_percent');
        });
    }

    public function down()
    {
        Schema::table('feed_invoices', function (Blueprint $table) {
            $table->dropColumn(['commission_percent', 'commission_amount']);
        });

        Schema::table('medicine_invoices', function (Blueprint $table) {
            $table->dropColumn(['commission_percent', 'commission_amount']);
        });
    }
};
