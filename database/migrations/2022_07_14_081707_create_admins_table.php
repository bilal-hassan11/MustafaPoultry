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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name', 180)->nullable();
            $table->string('username', 80)->unique();
            $table->string('email')->unique();
            $table->string('password');
            // $table->string('contact_no', 30)->nullable();
            // $table->string('cnic', 20)->nullable();
            $table->string('image', 500)->nullable();
            $table->string('user_type')->default('normal')->nullable();
            $table->text('user_permissions')->nullable();
            $table->boolean('is_active')->default(1)->nullable();
            // $table->bigInteger('added_by_id')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('admins');
    }
};
