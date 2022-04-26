<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->integer('user_id');
            $table->integer('type')->comment('1-referral_payment, 2-refund, 3-package_order');
            $table->integer('order_id');
            $table->integer('referrer_id');
            $table->tinyInteger('status')->comment('1-pending, 2-cancelled, 3-completed');
            // $table->dateTime('created');
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
        Schema::dropIfExists('transactions');
    }
}
