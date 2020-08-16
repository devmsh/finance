<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('note');
            $table->bigInteger('amount');

            $table->bigInteger('category_id');
            $table->morphs('trackable');
            $table->bigInteger('causedby_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->timestamp('due_date');
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('pending_transactions');
    }
}
