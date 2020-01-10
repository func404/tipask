@extends('admin/public/layout')

@section('title')
    广告任务明细
@endsection

@section('content')
    <section class="content-header">
        <h1>
            广告任务明细
            <small>显示当前所拥有的广告任务明细</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="btn-group">

                                    <button class="btn btn-default btn-sm" data-toggle="tooltip" title="删除选中项" onclick="confirm_submit('item_form','{{  route('admin.adtaskdetail.delete') }}','确认删除选中项？')"><i class="fa fa-trash-o"></i></button>
                                </div>
                            </div>
                            <div class="col-xs-9">
                              <div class="row">
                                    <form name="searchForm" action="{{ route('admin.adtaskdetail.index') }}" method="GET">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                         <div class="col-xs-2">
                                            <select class="form-control" name="platform_id">
                                                   <option value="0">选择平台</option>
                                                         @include('admin.platform.option',['type'=>'platform','select_id'=>$filter['platform_id']??0])
                                            </select>
                                         </div>
                                        <div class="col-xs-2 hidden-xs">
                                             <select class="form-control" name="user_id">
                                               <option value="0">选择客户</option>
                                                     @include('admin.user.option',['type'=>'user','select_id'=>$filter['user_id']??0])
                                             </select>
                                        </div>

                                        <div class="col-xs-2">
                                            <select class="form-control" name="status">
                                                <option value="-9">全部</option>
                                                @foreach(trans_common_disable('all') as $key => $status)
                                                    <option value="{{ $key }}" @if( isset($filter['status']) && $filter['status']==$key) selected @endif >{{ $status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xs-3 hidden-xs">
                                            <input type="text" name="date_range" id="date_range" class="form-control" placeholder="时间范围" value="{{ $filter['date_range'] or '' }}" />
                                        </div>
                                        <div class="col-xs-1">
                                            <button type="submit" class="btn btn-primary">搜索</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body  no-padding">
                        <form name="itemForm" id="item_form" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th><input type="checkbox" class="checkbox-toggle"/></th>
                                        <th>广告任务名称</th>
                                        <th>客户</th>
                                        <th>广告平台</th>
                                        <th>广告位</th>
                                        <th>图片地址</th>
                                        <th>创建时间</th>
                                        <th>更新时间</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    @foreach($task_details as $task_detail)
                                        <tr>
                                            <td><input type="checkbox" value="{{ $task_detail->id }}" name="id[]"/></td>
                                            <td>{{ $task_detail->task->task_name??''}}</td>
                                            <td>{{ $task_detail->task->user->name??'' }}</td>
                                            <td>{{ $task_detail->platform->name??'' }}</td>
                                            <td>{{ $task_detail->position->mark??'' }}</td>
                                            <td><a href="{{ $task_detail->ad_image_url??'' }}" target="_blank" >{{ $task_detail->ad_image_url??'' }}</a></td>
                                            <td>{{ $task_detail->created_at??'' }}</td>
                                            <td>{{ $task_detail->updated_at??'' }}</td>
                                            <td><span class="label @if($task_detail->disable==0) label-default @elseif($task_detail->disable==1) label-success @endif">{{ trans_common_disable($task_detail->disable) }}</span> </td>
                                            <td>
                                                <div class="btn-group-xs" >
                                                    <a class="btn btn-default" href="{{ route('admin.adtaskdetail.edit',['id'=>$task_detail->id]) }}" data-toggle="tooltip" title="编辑广告任务明细"><i class="fa fa-edit"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="box-footer clearfix">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="btn-group">
                                    <button class="btn btn-default btn-sm" data-toggle="tooltip" title="删除选中项" onclick="confirm_submit('item_form','{{  route('admin.adtaskdetail.delete') }}','确认删除选中项？')"><i class="fa fa-trash-o"></i></button>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="text-right">
                                    <span class="total-num">共 {{ $task_details->total() }} 条数据</span>
                                    {!! str_replace('/?', '?', $task_details->appends($filter)->links()) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        set_active_menu('ad_manage',"{{ route('admin.adtaskdetail.index') }}");
    </script>
@endsection
