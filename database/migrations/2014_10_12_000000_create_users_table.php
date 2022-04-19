<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->uuid('uuid')->nullable(false);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('full_name');
            $table->string('company');
            $table->string('address');
            $table->string('country');
            $table->string('phone');
            $table->string('state');
            $table->string('town');
            $table->string('role');
            $table->string('otp');
            $table->uuid('referral_code');
            $table->uuid('referer')->nullable();
            $table->json('ach_details');
            $table->string('referral_balance');
            $table->boolean('status');
            $table->integer('package_id');
            $table->string('verification_token');
            $table->string('password_reset_token');
            $table->integer('local_id');
            // $table->dateTime('created');
            // $table->dateTime('updated');
            $table->integer('updated_by_user_id');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
