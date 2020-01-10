<?php

namespace App\Http\Controllers\Admin;

use App\Services\AdImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 广告素材管理控制器
 */

class AdImageController extends AdminController
{
    /**
     * 添加广告素材界面
     * @dateTime 2020-01-09
     * @author Jingxinpo
     * @return   [type]     [description]
     */
    public function create()
    {
        return view('admin.adimage.create');
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
        $image_id     = $request->input('id');
        $image_server = new AdImageService;
        $result       = $image_server->delete($image_id);
        if ($result) {
            return $this->success(route('admin.adimage.index'), '广告素材删除成功');
        } else {
            return $this->error(route('admin.adimage.index'), '广告素材删除失败');
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
        $image_server = new AdImageService;
        $image        = $image_server->getById($id);
        return view('admin.adimage.edit')->with(compact('image'));
    }

    /**
     * 保存广告素材修改，素材先不关联平台
     */
    public function update(Request $request)
    {
        $request->flash();
        $image_server = new AdImageService;
        $id           = $request->input('id');
        $image        = $image_server->getById($id);

        if (!$image) {
            abort(404);
        }
        $name = $request->input('name') ?? '';
        if (!$name) {
            return $this->error(route('admin.adimage.index'), '请输入素材名称');
        }
        $image->name = $name;

        if ($request->file('image')) {
            $path       = $request->file('image')->store('public/adimages');
            $path       = str_replace('public', 'storage', $path);
            $url        = 'http://' . config('app.url') . '/' . $path;
            $image->url = $url;
        }

        // $image->platform_id = $platform_id;
        $image->disable    = $request->input('status') ?? 0;
        $image->updated_by = Auth::id() ?? 0;

        if ($image->save()) {
            return $this->success(route('admin.adimage.index'), '广告素材修改成功');
        } else {
            return $this->error(route('admin.adimage.index'), '广告素材修改失败');
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

        $image_server = new AdImageService;
        $filter       = [];

        $is_admin = $this->isAdmin();
        if (!$is_admin) {
            $filter['created_by'] = Auth::id();
        }

        if (isset($inputs['image_id']) && $inputs['image_id'] > 0) {
            $filter['id'] = $inputs['image_id'];
        }

        /*名称过滤*/
        if (isset($inputs['name']) && $inputs['name']) {
            $filter['name'] = ['like', $inputs['name']];
        }

        //平台过滤
        if (isset($inputs['platform_id']) && $inputs['platform_id']) {
            $filter['platform_id'] = ['like', $inputs['platform_id']];
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

        // $user_id              = Auth::user();
        // $filter['created_by'] = Auth::id();

        $query = $image_server->page($filter, ['created_at' => 'desc']);
        // $query = $query->with('platform');
        $size  = config('tipask.admin.page_size');
        $users = $query->paginate($size);
        return view('admin.adimage.index')->with('images', $users)->with('filter', $inputs);
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
        $mark      = $form_data['name'] ?? '';
        // $platform_id = $form_data['platform_id'] ?? '';
        $path = $request->file('image')->store('public/adimages');
        $path = str_replace('public', 'storage', $path);
        $url  = 'http://' . config('app.url') . '/' . $path;

        $form_data['disable']    = AdImageService::DISABLE_FALSE;
        $form_data['created_by'] = Auth::id();
        $form_data['url']        = $url;

        $image_server = new AdImageService;
        $image_server->add($form_data);

        return $this->success(route('admin.adimage.index'), '添加素材成功！');

    }
}
