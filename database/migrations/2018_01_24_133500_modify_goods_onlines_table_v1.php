<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyGoodsOnlinesTableV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goods_onlines', function(Blueprint $table)
        {
            $table->tinyInteger('repush')->nullable()->default(0);
            $table->integer('goods_basic_history_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goods_onlines', function(Blueprint $table)
        {
            $table->dropColumn('repush');
            $table->dropColumn('goods_basic_history_id');
        });
    }
}
