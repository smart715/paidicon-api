<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->integer('package_id');
            $table->json('package_features');
            $table->string('key');
            $table->ipAddress('restrict_to_ip_address');
            $table->integer('user_id');
            $table->double('ip_requests_limit');
            $table->double('ip_requests_limit_seconds');
            $table->double('ip_requests_interval_after_limit_seconds');
            // $table->dateTime('created');
            $table->date('expires');
            $table->tinyInteger('status')->comment('1-pending,2-active,3-disabled')->default(2);
            // $table->dateTime('updated');
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
        Schema::dropIfExists('api_keys');
    }
}
