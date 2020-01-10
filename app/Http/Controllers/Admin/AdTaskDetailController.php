<?php

namespace App\Http\Controllers\Admin;

use App\Services\AdImageService;
use App\Services\AdTaskDetailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 广告素材管理控制器
 */

class AdTaskDetailController extends AdminController
{
    /**
     * 删除广告任务明细
     * @dateTime 2020-01-09
     * @author Jingxinpo
     * @param    Request    $request [description]
     * @return   [type]              [description]
     */
    public function delete(Request $request)
    {
        $task_detail_id     = $request->input('id');
        $task_detail_server = new AdTaskDetailService;
        $result             = $task_detail_server->delete($task_detail_id);
        if ($result) {
            return $this->success(route('admin.adtaskdetail.index'), '广告任务明细成删除功');
        } else {
            return $this->error(route('admin.adtaskdetail.index'), '广告任务明细删除失败');
        }
    }

    /**
     * 编辑广告任务明细界面
     * @dateTime 2020-01-09
     * @author Jingxinpo
     * @return   [type]     [description]
     */
    public function edit($id)
    {
        $task_detail_server = new AdTaskDetailService;
        $task_detail        = $task_detail_server->getById($id);
        return view('admin.adtaskdetail.edit')->with(compact('task_detail'));
    }

    /**
     * 保存广告任务明细修改
     */
    public function update(Request $request)
    {

        $request->flash();
        $task_detail_server = new AdTaskDetailService;
        $id                 = $request->input('id');
        $task_detail        = $task_detail_server->getById($id);

        if (!$task_detail) {
            abort(404);
        }
        // $platform_id = $request->input('platform_id') ?? 0;
        // if (!$platform_id) {
        //     return $this->error(route('admin.adimage.index'), '请选择平台');
        // }

        $image_id = $request->input('ad_image_id') ?? '';
        if (!$image_id) {
            return $this->error(route('admin.adtaskdetail.index'), '请选择修改的图片');
        }

        $image_server = new AdImageService;
        $image        = $image_server->getById($image_id);
        if (!$image || $image->disable == 0) {
            return $this->error(route('admin.adtaskdetail.index'), '该素材已失效');
        }

        $task_detail->ad_image_url = $image->url;
        $task_detail->ad_image_id  = $image_id;
        $task_detail->disable      = $request->input('status') ?? 0;
        $task_detail->updated_by   = Auth::id() ?? 0;

        if ($task_detail->save()) {
            return $this->success(route('admin.adtaskdetail.index'), '广告素材修改成功');
        } else {
            return $this->error(route('admin.adtaskdetail.index'), '广告素材修改失败');
        }

    }

    /**
     * 展示广告素材
     * @dateTime 2020-01-08
     * @author Jingxinpo
     * @param    Request    $request [description]
     * @return   [type]              [description]
     */
    public function index(Request $request)
    {
        $inputs = $request->all();

        $task_detail_server = new AdTaskDetailService;
        $filter             = [];

        $is_admin = $this->isAdmin();
        if (!$is_admin) {
            $filter['ad_tasks.user_id'] = Auth::id();
        }

        // 任务过滤
        if (isset($inputs['ad_task_id']) && $inputs['ad_task_id'] > 0) {
            $filter['ad_task_details.ad_task_id'] = $inputs['ad_task_id'];
        }

        //平台过滤
        if (isset($inputs['platform_id']) && $inputs['platform_id']) {
            $filter['ad_task_details.platform_id'] = $inputs['platform_id'];
        }

        //广告位过滤
        if (isset($inputs['ad_task_position_id']) && $inputs['ad_task_position_id']) {
            $filter['ad_task_details.ad_task_position_id'] = $inputs['ad_task_position_id'];
        }
        //平台过滤
        if (isset($inputs['ad_image_id']) && $inputs['ad_image_id']) {
            $filter['ad_task_details.ad_image_id'] = $inputs['ad_image_id'];
        }

        /*注册时间过滤*/
        if (isset($filter['date_range']) && $filter['date_range']) {
            $dates                                = explode(" - ", $filter['date_range']);
            $filter['ad_task_details.created_at'] = ['between', $dates[0] ?? '', dates[1] ?? ''];
        }

        /*状态过滤*/
        if (isset($inputs['status']) && $inputs['status'] > -1) {
            $filter['ad_task_details.disable'] = $inputs['status'];
        }

        // $user_id              = Auth::user();
        // $filter['created_by'] = Auth::id();

        $query = $task_detail_server->page($filter, ['ad_task_details.created_at' => 'desc']);
        $query = $query->with('task.user', 'platform', 'position');
        $size  = config('tipask.admin.page_size');
        $users = $query->paginate($size);
        return view('admin.adtaskdetail.index')->with('task_details', $users)->with('filter', $inputs);
    }
}
