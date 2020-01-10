<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdTaskDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //广告任务明细表
        Schema::create('ad_task_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_task_id')->comment('任务id')->index();
            $table->integer('platform_id')->comment('平台ID')->index();
            $table->integer('ad_task_position_id')->comment('广告位ID')->index();
            $table->integer('ad_image_id')->comment('广告位图片')->index();
            $table->string('ad_image_url', 255)->comment('广告位图片地址'); //冗余
            $table->integer('disable')->default(1)->comment('0 不可用 1可用'); //冗余

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
        Schema::dropIfExists('ad_task_details');
    }
}
