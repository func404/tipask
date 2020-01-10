<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('platform_id')->comment('平台')->index();
            $table->string('describe', 50)->nullable()->comment('广告位描述');
            $table->string('mark', 25)->comment('广告位标记')->index();
            $table->integer('disable')->default(1)->comment('广告位是否可用 0 禁用 1可用');

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
        Schema::dropIfExists('ad_positions');
    }
}
