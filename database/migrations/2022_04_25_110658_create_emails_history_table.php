<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails_history', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('subject');
            $table->string('signature');
            $table->text('body');
            $table->bigInteger('email_template_id')->unsigned()->nullable();
            $table->bigInteger('sent_by_user')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('email_template_id')
                ->references('id')->on('email_templates');

            $table->foreign('sent_by_user')
                ->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emails_history');
    }
}
