<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTransactionLogsTableV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_logs', function(Blueprint $table)
        {
            $table->integer('user_account_id')->nullable();
            $table->text('memo')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_logs', function(Blueprint $table)
        {
            $table->dropColumn('user_account_id');
            $table->dropColumn('memo');
        });
    }
}
