<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baidu_pushes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('site')->comment('网站');
            $table->string('url')->comment('要推送的Url');
            $table->string('type')->comment('推送类型');
            $table->tinyInteger('status')->default(0)->nullable()->index();
            $table->string('msg')->nullable();
            $table->unsignedInteger('failures')->nullable()->default(0)->comment('失败计数');
            $table->timestamp('push_at')->nullable()->comment('推送时间');
            $table->timestamp('created_at')->nullable()->comment('创建时间');

            $table->unique(['site', 'url']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('baidu_pushes');
    }
};
