<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 广告任务表
        Schema::create('ad_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('task_name', 50)->nullable()->comment('任务名称');
            $table->integer('disable')->default(1)->comment('0 任务禁用 1任务可用');
            $table->integer('user_id')->nullable()->comment('用户id,投放人')->index();
            $table->integer('ad_task_log_id')->nullable()->comment('正在使用的投放记录')->index();

            $table->integer('created_by')->default(0)->comment('创建人');
            $table->integer('updated_by')->default(0)->comment('修改人');
            $table->integer('deleted_by')->default(0)->comment('删除人');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_tasks');
    }
}
