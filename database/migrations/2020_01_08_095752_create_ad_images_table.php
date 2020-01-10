<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url', 255)->nullable()->comment('图片地址');
            $table->string('name', 255)->nullable()->comment('图片名称');
            $table->integer('platform_id')->default(0)->comment('图片平台');
            $table->integer('disable')->default(1)->comment('图片是否可用 0不可用 1可用');

            $table->integer('created_by')->default(0)->comment('创建人');
            $table->integer('updated_by')->default(0)->comment('修改人');
            $table->integer('deleted_by')->default(0)->comment('删除人');
            $table->dateTime('created_at')->nullable()->comment('创建时间');
            $table->dateTime('updated_at')->nullable()->comment('更新时间');
            $table->dateTime('deleted_at')->nullable()->comment('删除时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_images');
    }
}
