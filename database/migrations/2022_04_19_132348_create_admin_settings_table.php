<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('site_name');
            $table->string('timezone');
            $table->string('locale');
            $table->boolean('maintenance_mode');
            $table->date('maintenance_start');
            $table->date('maintenance_end');
            $table->string('maintenance_reason');
            $table->double('ip_requests_limit');
            $table->double('ip_requests_limit_seconds');
            $table->double('ip_requests_interval_after_limit_seconds');
            $table->string('referral_percentage');
            $table->double('minimimum_referral_payout_amount');
            $table->string('discount_percentage_on_first_payment');
            // $table->dateTime('created');
            // $table->dateTime('updated');
            $table->text('branding_text');
            $table->integer('updated_by_user_id');
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
        Schema::dropIfExists('admin_settings');
    }
}
