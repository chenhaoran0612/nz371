<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsBasicHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_basic_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goods_basic_id');
            $table->string('title');
            $table->decimal('price',8,2)->nullable();
            $table->string('currency');
            $table->text('main_image')->nullable();
            $table->text('images')->nullable();
            $table->string('length');
            $table->string('width');
            $table->string('height');
            $table->string('weight');
            $table->string('barcode')->nullable();
            $table->string('condition')->nullable();
            $table->string('description')->nullable();
            $table->text('attribute')->nullable();
            $table->text('memo')->nullable();
            $table->tinyInteger('push_status')->default(0);
            $table->integer('vendor_id');
            $table->integer('user_id');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['vendor_id', 'user_id', 'goods_basic_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_basic_histories');
    }
}
