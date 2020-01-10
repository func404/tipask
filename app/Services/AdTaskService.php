<?php

namespace App\Services;

use App\Models\AdTask;
use App\Models\AdTaskDetail;
use App\Models\AdTaskLog;

/**
 * 广告管理
 */
class AdTaskService extends BaseService
{

    //失效
    const DISABLE_TRUE = 0;
    //有效
    const DISABLE_FALSE = 1;

    public function __construct()
    {
        $this->model = new AdTask;
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

        $query = $this->model->select('ad_tasks.*');
        $query = $query->join('ad_task_logs', 'ad_tasks.ad_task_log_id', '=', 'ad_task_logs.id');
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
     * 创建明细
     * @dateTime 2020-01-09
     * @author Jingxinpo
     * @return   [type]     [description]
     */
    public function createDetails($data)
    {
        $platform_id  = $data['platform_id'] ?? '';
        $positions    = $data['positions'] ?? '';
        $task_id      = $data['task_id'] ?? '';
        $ad_image_id  = $data['ad_image_id'] ?? 1;
        $ad_image_url = $data['ad_image_id'];
        $created_by   = $data['created_by'] ?? 0;
        $data         = [];
        if ($platform_id && $positions && $task_id) {
            foreach ($positions as $position) {
                $data[] = [
                    'ad_task_id'          => $task_id,
                    'platform_id'         => $platform_id,
                    'ad_task_position_id' => $position,
                    'ad_image_id'         => $ad_image_id,
                    'ad_image_url'        => $ad_image_url,
                    'created_by'          => $created_by,
                    'created_at'          => date('Y-m-d H:i:s'),
                    'updated_at'          => date('Y-m-d H:i:s'),
                ];
            }
            AdTaskDetail::where('platform_id', $platform_id)
                ->where('ad_task_id', $task_id)
                ->delete();
            return AdTaskDetail::insert($data);
        }

        return false;
    }

    /**
     * 广告任务删除 记录、明细、任务删除
     * @dateTime 2020-01-10
     * @author Jingxinpo
     * @param    [type]     $task_id [description]
     * @return   [type]              [description]
     */
    public function delete($task_id, $is_soft = false)
    {
        $task_ids = [];
        if (!is_array($task_ids)) {
            $task_ids[] = $task_id;
        } else {
            $task_ids = $task_id;
        }

        if ($task_ids) {
            $task_res        = AdTask::whereIn('id', $task_ids)->delete();
            $task_log_res    = AdTaskLog::whereIn('ad_task_id', $task_ids)->delete();
            $task_detail_res = AdTaskDetail::whereIn('ad_task_id', $task_ids)->delete();
        }
        // DB::beginTransaction();

        // if ($task_res && $task_log_res && $task_detail_res) {
        // DB::commit();
        return true;
        // } else {
        // DB::rollBack();
        // return false;
        // }
    }
}
