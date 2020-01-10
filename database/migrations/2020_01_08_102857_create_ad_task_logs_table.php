<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdTaskLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_task_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_task_id')->comment('任务id')->index();
            $table->string('platforms', 100)->nullable()->comment('平台信息 json');
            $table->dateTime('begin')->nullable()->comment('投放生效时间');
            $table->dateTime('end')->nullable()->comment('投放失效时间');
            $table->string('remark')->nullable()->comment('备注');
            $table->decimal('real_amount', 10, 2)->nullable()->comment('实收金额,单位分，换算成元或万元');
            $table->string('discount')->default('')->comment('具体折扣');

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
        Schema::dropIfExists('ad_task_logs');
    }
}
