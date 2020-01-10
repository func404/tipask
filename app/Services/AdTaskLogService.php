<?php

namespace App\Services;

use App\Models\AdTaskLog;

/**
 * 广告管理
 */
class AdTaskLogService extends BaseService
{

    public function __construct()
    {
        $this->model = new AdTaskLog;
    }
    public function FunctionName($value = '')
    {

    }
}
