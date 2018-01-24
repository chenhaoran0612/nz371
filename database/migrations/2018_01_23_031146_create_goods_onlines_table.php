<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsOnlinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_onlines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goods_basic_id');
            $table->integer('category_id')->nullable();
            $table->string('title')->nullable();
            $table->string('item_number')->nullable();
            $table->decimal('price',8,2)->nullable();
            $table->string('currency')->nullable();
            $table->text('main_image')->nullable();
            $table->text('images')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('barcode')->nullable();
            $table->string('condition')->nullable();
            $table->string('description')->nullable();
            $table->text('attribute')->nullable();
            $table->text('memo')->nullable();
            $table->tinyInteger('online_status')->default(0);
            $table->integer('vendor_id');
            $table->integer('user_id');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['goods_basic_id', 'vendor_id', 'user_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_onlines');
    }
}
