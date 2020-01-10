@extends('admin/public/layout')

@section('css')
    <link href="{{ asset('/static/js/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" />
@endsection

@section('title')
    编辑广告任务
@endsection

@section('content')
    <section class="content-header">
        <h1>
             编辑广告任务
            <small> 编辑广告任务</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">编辑广告任务</h3>
                    </div>
                    <form role="form" name="userForm" method="POST" action="{{ route('admin.adtask.update') }}">

                        <input name="_method" type="hidden" value="PUT">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-body">
                          <div class="form-group @if ($errors->has('name')) has-error @endif">
                              <label for="name">广告任务名称</label>
                               <input type="hidden" name="id" value="{{ $task->id }}">
                              <input type="text" name="name" class="form-control " placeholder="广告任务名称" value="{{ old('name',$task->task_name) }}">
                              @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                          </div>

                            <div class="form-group">
                                <select class="form-control" name="user_id">
                                       <option value="0">选择客户</option>
                                             @include('admin.user.option',['type'=>'user','select_id'=>$task->user_id])
                                </select>
                         </div>

                           <div class="form-group">
                                <select class="form-control" name="platform_id">
                                       <option value="0">选择平台</option>
                                             @include('admin.platform.option',['type'=>'platform','select_id'=>$task->log->platforms??0])
                                </select>
                         </div>

                            <div class="form-group" >
                                <label for="website_url">选择广告位</label>
                                <span class="text-muted">(选择该任务在该平台下的广告位)</span>
                                <div class="checkbox" id="test">
                                     @include('admin.adposition.option',['type'=>'position','platform_id'=>$task->log->platforms??0,'task_id'=>$task->id??0])
                                </div>
                         </div>

                         <div class="form-group ">
                          <label>选择任务时间范围</label>
                            <input type="text" name="date_range" id="date_range" class="form-control" placeholder="时间范围" value="{{ $task->log->begin.'-'.$task->log->end }}" />
                        </div>

                         <div class="form-group @if ($errors->has('remark')) has-error @endif">
                              <label for="name">备注</label>
                              <input type="text" name="remark" class="form-control " placeholder="备注" value="{{ old('remark',$task->log->remark??'') }}">
                              @if ($errors->has('remark')) <p class="help-block">{{ $errors->first('remark') }}</p> @endif
                          </div>

                           <div class="form-group @if ($errors->has('real_amount')) has-error @endif">
                          <label>实收（元）</label>
                          <input type="text" name="real_amount" class="form-control "  placeholder="实收" value="{{ old('real_amount',$task->log->real_amount??'') }}" onkeyup="this.value=this.value.replace(/[^\-?\d.]/g,'')" >
                            @if ($errors->has('real_amount')) <p class="help-block">{{ $errors->first('real_amount') }}</p> @endif

                        </div>

                         <div class="form-group @if ($errors->has('discount')) has-error @endif">
                          <label>折扣</label>
                          <input type="text" name="discount" class="form-control "  placeholder="折扣" value="{{ old('discount',$task->log->discount??'') }}">
                            @if ($errors->has('discount')) <p class="help-block">{{ $errors->first('discount') }}</p> @endif

                        </div>


                            <div class="form-group">
                                <label>状态</label>
                                <span class="text-muted">(禁用后平台将不可用)</span>
                                <div class="radio">
                                    @foreach(trans_common_disable('all') as $key => $status)
                                        <label>
                                            <input type="radio" name="status" value="{{ $key }}" @if($task->disable === $key) checked @endif /> {{ $status }}
                                        </label>&nbsp;&nbsp;
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                          <button type="submit" class="btn btn-primary">保存</button>
                          <button type="reset" class="btn btn-success">重置</button>
                        </div>
                    </form>
                  </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ asset('/static/js/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('/static/js/bootstrap-datepicker/locales/bootstrap-datepicker.zh-CN.min.js') }}"></script>
    <script type="text/javascript">

            set_active_menu('ad_manage',"{{ route('admin.adtask.index') }}");

            function gradeChange(platform_id) {
              $.post("{{ route('api.position.option') }}", {platform_id: platform_id}, function(data) {
                if(data.code==0 ){
                     $("#test").replaceWith('<div class="checkbox" id="test">'+data.data+'</div>');
                }
            });

    </script>
@endsection
