<?php

namespace App\Services;

use App\Models\PlatForm;

/**
 *  平台服务
 */
class PlatFormService extends BaseService
{
    //失效
    const DISABLE_TRUE = 0;
    //有效
    const DISABLE_FALSE = 1;

    public function __construct()
    {
        $this->model = new PlatForm;
    }

    public function FunctionName($value = '')
    {

    }
}
