<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60)->default('123456');
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->string('api_token')->nullable();
            $table->char('lang', 2)->default('en');
            $table->boolean('is_approved')->default(2)->comment('1: pending, 2: accepted, 3: rejected');
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
        Schema::drop('users');
    }
}
