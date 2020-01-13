<?php

namespace App\Services;

use App\Models\AdPosition;
use App\Models\AdTaskDetail;

/**
 *  平台服务
 */
class AdTaskDetailService extends BaseService
{
    //失效
    const DISABLE_TRUE = 0;
    //有效
    const DISABLE_FALSE = 1;

    public function __construct()
    {
        $this->model = new AdTaskDetail;
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

        $query = $this->model->select('ad_task_details.*')
            ->join('ad_tasks', 'ad_tasks.id', '=', 'ad_task_details.ad_task_id')
        ;

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
     * 广告图片
     * @dateTime 2020-01-10
     * @author Jingxinpo
     * @param    [type]     $platform_host [description]
     * @return   [type]                    [description]
     */
    public function pull($platform_host)
    {
        $result = [];

        $used_positons = AdTaskDetail::select('ad_task_details.ad_task_position_id', 'ad_task_details.ad_image_url')
            ->join('ad_tasks', 'ad_tasks.id', '=', 'ad_task_details.ad_task_id')
            ->join('ad_task_logs', 'ad_task_logs.id', '=', 'ad_task_log_id')
            ->where('ad_task_logs.end', '>=', date('Y-m-d H:i:s'))
            ->where('ad_task_details.disable', 1)
            ->get();
        $data = [];
        foreach ($used_positons as $value) {
            $data[$value->ad_task_position_id] = $value->ad_image_url;
        }
        // 获取有效的广告位
        $positions = AdPosition::select('ad_positions.id', 'ad_positions.mark', 'ad_positions.describe')
            ->join('platforms', 'platforms.id', '=', 'ad_positions.platform_id')
            ->where('platforms.host', $platform_host)
            ->where('ad_positions.disable', 1)
            ->where('platforms.disable', 1)
            ->get();

        foreach ($positions as $position) {
            $result[] = [
                // 后期改到配置文件里
                'image_url' => $data[$position->id] ?? 'http://adadmin.kuaiso.com/storage/adimages/u2yiUpmblzGN6bZ0gBLD6oexWqXQVy9jmtQn43qJ.jpeg',
                'mark'      => $position->mark,
                'describe'  => $position->describe,
            ];

        }
        return $result;
    }

    // 删除过期的广告任务明细
    public function check()
    {
        $used_positons = AdTaskDetail::select('ad_task_details.id')
            ->join('ad_tasks', 'ad_tasks.id', '=', 'ad_task_details.ad_task_id')
            ->join('ad_task_logs', 'ad_task_logs.id', '=', 'ad_task_log_id')
            ->where('ad_task_logs.end', '<=', date('Y-m-d H:i:s'))
            ->delete();
    }

}
