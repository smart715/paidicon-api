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
            $table->string('stripe_id')->nullable();
            $table->double('amount');
            $table->integer('type')->comment('1-referral_payment, 2-refund, 3-package_order');
            $table->integer('order_id')->nullable();
            $table->integer('referrer_id')->nullable();
            $table->tinyInteger('status')->comment('1-pending, 2-cancelled, 3-completed');
            $table->integer('refund_id')->nullable();

            $table->string('bank_account')->nullable();
            $table->json('card')->nullable();
            // $table->dateTime('created');
            // $table->dateTime('updated');
            $table->integer('updated_by_user_id')->nullable();
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
