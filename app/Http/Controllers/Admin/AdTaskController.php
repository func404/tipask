<?php

namespace App\Http\Controllers\Admin;

use App\Services\AdTaskLogService;
use App\Services\AdTaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 广告任务管理控制器
 */
class AdTaskController extends AdminController
{

    /**
     * 添加广告素材界面
     * @dateTime 2020-01-09
     * @author Jingxinpo
     * @return   [type]     [description]
     */
    public function create()
    {
        return view('admin.adtask.create');
    }

    /**
     * 删除广告素材信息
     * @dateTime 2020-01-09
     * @author Jingxinpo
     * @param    Request    $request [description]
     * @return   [type]              [description]
     */
    public function delete(Request $request)
    {
        $image_id    = $request->input('id');
        $task_server = new AdTaskService;
        $result      = $task_server->delete($image_id);

        if ($result) {
            return $this->success(route('admin.adtask.index'), '广告任务删除成功');
        } else {
            return $this->error(route('admin.adtask.index'), '广告任务删除失败');
        }
    }

    /**
     * 编辑广告素材界面
     * @dateTime 2020-01-09
     * @author Jingxinpo
     * @return   [type]     [description]
     */
    public function edit($id)
    {
        $task_server = new AdTaskService;
        $task        = $task_server->getById($id);
        return view('admin.adtask.edit')->with(compact('task'));
    }

    /**
     * 保存广告任务修改，素材先不关联平台
     */
    public function update(Request $request)
    {
        $request->flash();
        $task_server = new AdTaskService;
        $id          = $request->input('id');
        $task        = $task_server->getById($id);
        if (!$task) {
            abort(404);
        }
        $task_name   = $request->input('name') ?? '';
        $user_id     = $request->input('user_id') ?? '';
        $platform_id = $request->input('platform_id') ?? '';
        $date_range  = $request->input('date_range') ?? '';
        $remark      = $request->input('remark');
        $real_amount = $request->input('real_amount');
        $discount    = $request->input('discount');
        $positions   = $request->input('positions');

        if (!$task_name) {
            return $this->error(route('admin.adtask.index'), '请输入任务名称');
        }

        if (!$user_id) {
            return $this->error(route('admin.adtask.index'), '请选择投放用户');
        }

        if (!$platform_id) {
            return $this->error(route('admin.adtask.index'), '请选择投平台');
        }

        if (!$positions) {
            return $this->error(route('admin.adtask.index'), '请选择广告位');
        }

        if (!$date_range) {
            return $this->error(route('admin.adtask.index'), '请选择开始结束时间');
        }
        $dates = explode(" - ", $date_range);
        $begin = $dates[0] ?? '';
        $end   = $dates[1] ?? '';

        $task->task_name  = $task_name;
        $task->user_id    = $user_id;
        $task->disable    = $request->input('status') ?? 0;
        $task->updated_by = Auth::id() ?? 0;

        $task_log_server = new AdTaskLogService;
        $log_data        = [
            'ad_task_id'  => $task->id,
            'platforms'   => $platform_id,
            'begin'       => $begin,
            'end'         => $end,
            'remark'      => $remark,
            'real_amount' => $real_amount,
            'discount'    => $discount,
            'created_by'  => Auth::id(),
        ];
        $log                  = $task_log_server->add($log_data);
        $task->ad_task_log_id = $log->id;
        $res                  = $task->save();
        // 创建任务明细
        if ($res) {
            $data = [
                'platform_id'  => $platform_id,
                'positions'    => $positions,
                'task_id'      => $task->id,
                'ad_image_id'  => $ad_image_id ?? 1,
                'ad_image_url' => $ad_image_url ?? '',
                'created_by'   => Auth::id(),
            ];
            $res = $task_server->createDetails($data);
        }

        if ($res) {
            return $this->success(route('admin.adtask.index'), '广告任务修改成功');
        } else {
            return $this->error(route('admin.adtask.index'), '广告任务保存失败');
        }

    }

    /**
     * 展示广告任务
     * @dateTime 2020-01-08
     * @author Jingxinpo
     * @param    Request    $request [description]
     * @return   [type]              [description]
     */
    public function index(Request $request)
    {
        $inputs = $request->all();

        $task_server = new AdTaskService;
        $filter      = [];

        if (isset($inputs['task_id']) && $inputs['task_id'] > 0) {
            $filter['ad_tasks.id'] = $inputs['task_id'];
        }

        /*名称过滤*/
        if (isset($inputs['name']) && $inputs['name']) {
            $filter['ad_tasks.task_name'] = ['like', $inputs['name']];
        }

        //平台过滤
        if (isset($inputs['platform_id']) && $inputs['platform_id']) {
            $filter['ad_task_logs.platforms'] = $inputs['platform_id'];
        }

        /*注册时间过滤*/
        if (isset($filter['date_range']) && $filter['date_range']) {
            $dates                         = explode(" - ", $filter['date_range']);
            $filter['ad_tasks.created_at'] = ['between', $dates[0] ?? '', dates[1] ?? ''];
        }

        /*状态过滤*/
        if (isset($inputs['status']) && $inputs['status'] > -1) {
            $filter['ad_tasks.disable'] = $inputs['status'];
        }

        // $user_id              = Auth::user();
        // $filter['created_by'] = Auth::id();

        $query = $task_server->page($filter, ['ad_tasks.created_at' => 'desc']);
        $query = $query->with('log.platform');
        $size  = config('tipask.admin.page_size');
        $users = $query->paginate($size);
        return view('admin.adtask.index')->with('tasks', $users)->with('filter', $inputs);
    }

    /**
     * 保存平台信息
     * @dateTime 2020-01-08
     * @author Jingxinpo
     * @param    Request    $request [description]
     * @return   [type]              [description]
     */
    public function store(Request $request)
    {
        $request->flash();
        $task_data     = [];
        $task_log_data = [];
        $task_name     = $request->input('name') ?? '';
        $user_id       = $request->input('user_id') ?? '';
        $platform_id   = $request->input('platform_id') ?? '';
        $date_range    = $request->input('date_range') ?? '';
        $remark        = $request->input('remark');
        $real_amount   = $request->input('real_amount');
        $discount      = $request->input('discount');
        $positions     = $request->input('positions');

        if (!$task_name) {
            return $this->error(route('admin.adtask.index'), '请输入任务名称');
        }

        if (!$user_id) {
            return $this->error(route('admin.adtask.index'), '请选择投放用户');
        }

        if (!$platform_id) {
            return $this->error(route('admin.adtask.index'), '请选择投平台');
        }
        if (!$positions) {
            return $this->error(route('admin.adtask.index'), '请选择广告位');
        }

        if (!$date_range) {
            return $this->error(route('admin.adtask.index'), '请选择开始结束时间');
        }

        $dates = explode(" - ", $date_range);
        $begin = $dates[0] ?? '';
        $end   = $dates[1] ?? '';

        $task_data = [
            'disable'    => AdTaskService::DISABLE_FALSE,
            'created_by' => Auth::id(),
            'task_name'  => $task_name,
            'user_id'    => $user_id,
        ];

        $task_server = new AdTaskService;
        $task        = $task_server->add($task_data);
        if (!$task) {
            return $this->error(route('admin.adtask.index'), '广告任务保存失败，请重试');
        }

        $task_log_server = new AdTaskLogService;
        $log_data        = [
            'ad_task_id'  => $task->id,
            'platforms'   => $platform_id,
            'begin'       => $begin,
            'end'         => $end,
            'remark'      => $remark,
            'real_amount' => $real_amount,
            'discount'    => $discount,
            'created_by'  => Auth::id(),
        ];
        $log                  = $task_log_server->add($log_data);
        $task->ad_task_log_id = $log->id;
        $res                  = $task->save();
        // 创建任务明细
        if ($res) {
            $data = [
                'platform_id'  => $platform_id,
                'positions'    => $positions,
                'task_id'      => $task->id,
                'ad_image_id'  => $ad_image_id ?? 1,
                'ad_image_url' => $ad_image_url ?? '',
                'created_by'   => Auth::id(),
            ];
            $res = $task_server->createDetails($data);
        }

        if ($res) {
            return $this->success(route('admin.adtask.index'), '广告任务创建成功');
        } else {
            return $this->error(route('admin.adtask.index'), '广告任务保存失败');
        }

    }
}
