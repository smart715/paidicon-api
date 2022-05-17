<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable(false);
            $table->integer('user_id');
            $table->integer('package_id');
            $table->string('payment_method');
            $table->double('amount',8,2);
            $table->integer('discount');
            $table->string('discount_reason');
            $table->double('total_payable',8,2);
            $table->tinyInteger('package_status')->comment('1-pending, 2-cancelled, 3-completed');
            $table->tinyInteger('status')->comment('1-pending, 2-cancelled, 3-completed');
            // $table->timestamp('created')->nullable();
            // $table->timestamp('updated')->nullable();
            $table->timestamps();
            $table->integer('updated_by_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
