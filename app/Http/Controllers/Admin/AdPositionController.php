<?php
namespace App\Http\Controllers\Admin;

use App\Services\AdPositionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 广告位管理控制器
 */
class AdPositionController extends AdminController
{
    /**
     * 创建广告位界面
     * @dateTime 2020-01-09
     * @author Jingxinpo
     * @return   [type]     [description]
     */
    public function create()
    {
        return view('admin.adposition.create');
    }

    /**
     * 删除广告位信息
     * @dateTime 2020-01-09
     * @author Jingxinpo
     * @param    Request    $request [description]
     * @return   [type]              [description]
     */
    public function delete(Request $request)
    {
        $position_id     = $request->input('id');
        $position_server = new AdPositionService;
        $result          = $position_server->delete($position_id);
        if ($result) {
            return $this->success(route('admin.adposition.index'), '广告位删除成功');
        } else {
            return $this->error(route('admin.adposition.index'), '广告位删除失败');
        }
    }

    /**
     * 编辑广告位界面
     * @dateTime 2020-01-09
     * @author Jingxinpo
     * @return   [type]     [description]
     */
    public function edit($id)
    {
        $position_server = new AdPositionService;
        $position        = $position_server->getById($id);
        return view('admin.adposition.edit')->with(compact('position'));
    }

    /**
     * 保存广告位修改
     */
    public function update(Request $request)
    {
        $request->flash();
        $position_server = new AdPositionService;
        $id              = $request->input('id');
        $position        = $position_server->getById($id);
        if (!$position) {
            abort(404);
        }
        $mark        = $request->input('mark') ?? '';
        $describe    = $request->input('describe') ?? '';
        $platform_id = $request->input('platform_id') ?? 0;
        if (!$platform_id) {
            return $this->error(route('admin.adposition.index'), '请选择平台');
        }

        $result = $position_server->exist(['id' => ['<>', $id], 'mark' => $mark]);
        if ($result) {
            return $this->error(route('admin.adposition.index'), '平台名称重复');
        }

        $position->mark        = $mark;
        $position->describe    = $describe;
        $position->platform_id = $platform_id;
        $position->disable     = $request->input('status') ?? 0;
        $position->updated_by  = Auth::id() ?? 0;

        if ($position->save()) {
            return $this->success(route('admin.adposition.index'), '平台信息修改成功');
        } else {
            return $this->error(route('admin.adposition.index'), '平台信息修改失败');
        }

    }

    /**
     * 展示广告位
     * @dateTime 2020-01-08
     * @author Jingxinpo
     * @param    Request    $request [description]
     * @return   [type]              [description]
     */
    public function index(Request $request)
    {
        $inputs = $request->all();

        $position_server = new AdPositionService;
        $filter          = [];

        if (isset($inputs['position_id']) && $inputs['position_id'] > 0) {
            $filter['id'] = $inputs['position_id'];
        }

        /*名称过滤*/
        if (isset($inputs['mark']) && $inputs['mark']) {
            $filter['mark'] = ['like', $inputs['mark']];
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

        $query = $position_server->page($filter, ['created_at' => 'desc']);
        $query = $query->with('platform');
        $size  = config('tipask.admin.page_size');
        $users = $query->paginate($size);
        return view('admin.adposition.index')->with('positions', $users)->with('filter', $inputs);
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
        $form_data   = $request->all();
        $mark        = $form_data['mark'] ?? '';
        $platform_id = $form_data['platform_id'] ?? '';
        $describe    = $form_data['describe'] ?? '';

        $this->validate($request, [
            'mark'        => "required|max:80|unique:ad_positions,mark",
            'platform_id' => "required",
        ]);

        $form_data['disable']    = AdPositionService::DISABLE_FALSE;
        $form_data['created_by'] = Auth::id();

        $position_server = new AdPositionService;
        $position_server->add($form_data);

        return $this->success(route('admin.adposition.index'), '添加平台信息成功！');

    }

    /**
     * 返回选项卡
     * @dateTime 2020-01-10
     * @author Jingxinpo
     * @return   [type]     [description]
     */
    public function options(Request $request)
    {
        $platform_id = $request->input('platform_id') ?? '';
        $task_id     = $request->input('task_id') ?? '';
        $str         = make_option_position('position', $platform_id, $task_id);
        $result      = [
            'code' => 0,
            'data' => $str,
        ];
        return response()->json($result);
    }
}
