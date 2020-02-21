<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduledTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('note');
            $table->bigInteger('amount');

            $table->bigInteger('category_id');
            $table->morphs('trackable');
            $table->bigInteger('causedby_id')->nullable();
            $table->bigInteger('user_id')->nullable();

            $table->integer('period');
            $table->integer('at');

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
        Schema::dropIfExists('scheduled_transactions');
    }
}
