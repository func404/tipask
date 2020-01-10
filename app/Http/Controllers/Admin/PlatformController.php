<?php

namespace App\Http\Controllers\Admin;

use App\Services\PlatFormService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 平台管理控制器
 */
class PlatformController extends AdminController
{
    /**
     * 删除平台
     * @dateTime 2020-01-08
     * @author Jingxinpo
     * @param    Request    $request [description]
     * @return   [type]              [description]
     */
    public function delete(Request $request)
    {
        $platform_id     = $request->input('id');
        $platform_server = new PlatFormService;
        $result          = $platform_server->delete($platform_id);
        if ($result) {
            return $this->success(route('admin.platform.index'), '平台信息删除成功');
        } else {
            return $this->error(route('admin.platform.index'), '平台信息删除失败');
        }
    }

    /**
     *
     * @dateTime 2020-01-08
     * @author Jingxinpo
     * @return   [type]     [description]
     */
    public function edit($id)
    {
        $platform_server = new PlatFormService;
        $platform        = $platform_server->getById($id);
        return view('admin.platform.edit')->with(compact('platform'));
    }

    /**
     * 保存用户修改
     */
    public function update(Request $request)
    {
        $request->flash();
        $platform_server = new PlatFormService;
        $id              = $request->input('id');
        $platform        = $platform_server->getById($id);
        if (!$platform) {
            abort(404);
        }
        $name = $request->input('name') ?? '';
        $host = $request->input('host') ?? '';

        $result = $platform_server->exist(['id' => ['<>', $id], 'name' => $name]);
        if ($result) {
            return $this->error(route('admin.platform.index'), '平台名称重复');
        }
        $result = $platform_server->exist(['id' => ['<>', $id], 'host' => $host]);
        if ($result) {
            return $this->error(route('admin.platform.index'), '平台域名重复');
        }

        $platform->name       = $name;
        $platform->host       = $host;
        $platform->disable    = $request->input('status') ?? 0;
        $platform->updated_by = Auth::id() ?? 0;

        if ($platform->save()) {
            return $this->success(route('admin.platform.index'), '平台信息修改成功');
        } else {
            return $this->error(route('admin.platform.index'), '平台信息修改失败');
        }

    }

    public function create()
    {
        return view('admin.platform.create');
    }

    /**
     * 展示平台信息
     * @dateTime 2020-01-08
     * @author Jingxinpo
     * @param    Request    $request [description]
     * @return   [type]              [description]
     */
    public function index(Request $request)
    {
        $inputs = $request->all();

        $platform_server = new PlatFormService;
        $filter          = [];

        if (isset($inputs['platform_id']) && $inputs['platform_id'] > 0) {
            $filter['id'] = $inputs['platform_id'];
        }

        /*名称过滤*/
        if (isset($inputs['name']) && $inputs['name']) {
            $filter['name'] = ['like', $inputs['name']];
        }

        /*注册时间过滤*/
        if (isset($filter['date_range']) && $filter['date_range']) {
            $dates                = explode(" - ", $filter['date_range']);
            $filter['created_at'] = ['between', $dates[0] ?? '', dates[1] ?? ''];
        }

        /*状态过滤*/
        if (isset($inputs['status']) && $inputs['status'] > -1) {
            $filter['disable'] = $inputs['status'];
        }

        $query = $platform_server->page($filter, ['created_at' => 'desc']);
        $size  = config('tipask.admin.page_size');
        $users = $query->paginate($size);
        return view('admin.platform.index')->with('platforms', $users)->with('filter', $inputs);
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
        $form_data = $request->all();
        $name      = $form_data['name'];
        $host      = $form_data['host'];
        $this->validate($request, [
            'name' => "required|max:80|unique:platforms,name",
            'host' => "required|max:50|unique:platforms,host",
        ]);

        $form_data['disable']    = PlatFormService::DISABLE_FALSE;
        $form_data['created_by'] = Auth::id();
        $host                    = $form_data['host'];
        if (stripos($host, 'http') !== false) {
            $data = parse_url($host);
            $host = $data['host'] ?? '';
        }

        $platform_server = new PlatFormService;
        $platform_server->add($form_data);

        return $this->success(route('admin.platform.index'), '添加平台信息成功！');

    }

}
