<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TransactionLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('action');
            $table->string('payment_method');
            $table->decimal('amount', 8, 2)->default(0.00); //金额
            $table->string('out_trade_no');
            $table->string('trade_no')->nullable();
            $table->integer('operate_user_id'); //操作人
            $table->tinyInteger('status')->default(0); //状态
            $table->integer('user_id');
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_logs');
    }
}
