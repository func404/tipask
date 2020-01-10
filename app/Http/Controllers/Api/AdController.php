<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Services\AdTaskDetailService;
use Illuminate\Http\Request;

/**
 *广告api
 */
class AdController extends BaseController
{
    /**
     * 拉取广告接口
     * @dateTime 2020-01-10
     * @author Jingxinpo
     * @return   [type]     [description]
     */
    public function pull(Request $request)
    {
        $platform_host  = $request->getClientIp();
        $platform_host  = '111.11.11.1111';
        $ad_task_server = new AdTaskDetailService;
        $data           = $ad_task_server->pull($platform_host);
        return $this->toJson(0, ['images' => $data], '请求成功');
    }
}
