<?php

namespace App\Services;

use App\Models\AdImage;

/**
 *  平台服务
 */
class AdImageService extends BaseService
{
    //失效
    const DISABLE_TRUE = 0;
    //有效
    const DISABLE_FALSE = 1;

    public function __construct()
    {
        $this->model = new AdImage;
    }

    /**
     * 通用分页方法
     * @author      Michael Liang    <liang15946@163.com>
     * @date        2017-10-17
     * $filter      筛选条件
     * $sorts       排序字段
     * $page_size   单页数量
     */
    public function page($filter = [], $sorts = [], $page_index = 1, $page_size = null, $is_api = false)
    {
        if (!($page_index > 0)) {
            $page_index = 1;
        }

        // 默认分页数量
        if (!($page_size > 0)) {
            $page_size = config('const.page_size', 15);
        }

        $query = $this->model->select();

        // 添加 筛选条件
        $this->_append_filter($query, $filter);

        // 添加 排序字段
        $this->_append_sort($query, $sorts);

        if (!$is_api) {
            return $query;
        }
        // 数据数量
        $count = $query->count();
        // 最大页数
        $max_page = (int) ceil($count / $page_size);

        if ($page_index > $max_page) {
            $page_index = $max_page;
        }

        // 分页查询
        $data = $query->skip($page_size * ($page_index - 1))
            ->take($page_size)
            ->get();

        $result = [
            'index'    => (int) $page_index,
            'size'     => (int) $page_size,
            'count'    => $count,
            'max_page' => (int) $max_page,
            'result'   => $data,
        ];

        // 保存筛选条件
        return $result;
    }

    /**
     * 图片上传
     * @dateTime 2020-01-09
     * @author Jingxinpo
     * @return   [type]     [description]
     */
    public function upload()
    {

    }
}
