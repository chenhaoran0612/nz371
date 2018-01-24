<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->rememberToken();
            $table->integer('kirin_id')->nullable();
            $table->string('user_code')->nullable(); //编码
            $table->tinyInteger('status')->default(0); //状态
            $table->integer('level')->nullable(); //级别
            $table->string('role', '128')->nullable(); //等级
            $table->integer('credit')->nullable(); //信用
            $table->integer('parent_id')->nullable()->default(0); //父ID
            $table->text('permission')->nullable(); //权限
            $table->decimal('amount', 8, 2)->default(0.00); //余额
            $table->string('contacts', '128')->nullable(); //联系人
            $table->string('phone', '128')->nullable(); //联系方式
            $table->string('address')->nullable(); //地址
            $table->string('logo')->nullable(); //logo
            $table->text('memo')->nullable(); //备注
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
