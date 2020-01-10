@extends('admin/public/layout')

@section('title')
    添加广告任务
@endsection

@section('content')
    <section class="content-header">
        <h1>
            添加广告任务
            <small>添加广告任务</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Tables</a></li>
            <li class="active">Simple</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                @include('admin/public/error')
                <div class="box box-default">
                    <form role="form" name="userForm" method="POST" action="{{ route('admin.adtask.store') }}">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <div class="box-body">
                        <div class="form-group @if ($errors->has('mark')) has-error @endif">
                          <label>广告任务名称</label>
                          <input type="text" name="name" class="form-control "  placeholder="广告任务名称" value="{{ old('name') }}">
                            @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif

                        </div>

                        <div class="form-group">
                                <select class="form-control" name="user_id">
                                       <option value="0">选择客户</option>
                                             @include('admin.user.option',['type'=>'user','select_id'=>0])
                                </select>
                         </div>

                          <div class="form-group">
                                <select class="form-control" name="platform_id" onchange="gradeChange(this.options[this.options.selectedIndex].value)">
                                       <option value="0">选择平台</option>
                                             @include('admin.platform.option',['type'=>'platform','select_id'=>0])
                                </select>
                         </div>

                        <div class="form-group" >
                                <label for="website_url">选择广告位</label>
                                <span class="text-muted">(选择该任务在该平台下的广告位)</span>
                                <div class="checkbox" id="test">
                                     @include('admin.adposition.option',['type'=>'position','platform_id'=>0,'task_id'=>0])
                                </div>
                         </div>


                       <div class="form-group ">
                          <label>选择任务时间范围</label>
                            <input type="text" name="date_range" id="date_range" class="form-control" placeholder="时间范围" value="{{ $filter['date_range'] or '' }}" />
                        </div>

                        <div class="form-group @if ($errors->has('remark')) has-error @endif">
                          <label>备注</label>
                          <input type="text" name="remark" class="form-control "  placeholder="备注" value="{{ old('remark') }}">
                            @if ($errors->has('remark')) <p class="help-block">{{ $errors->first('remark') }}</p> @endif

                        </div>

                        <div class="form-group @if ($errors->has('real_amount')) has-error @endif">
                          <label>实收（元）</label>
                          <input type="text" name="real_amount" class="form-control "  placeholder="实收" value="{{ old('real_amount') }}" onkeyup="this.value=this.value.replace(/[^\-?\d.]/g,'')" >
                            @if ($errors->has('real_amount')) <p class="help-block">{{ $errors->first('real_amount') }}</p> @endif

                        </div>

                        <div class="form-group @if ($errors->has('discount')) has-error @endif">
                          <label>折扣</label>
                          <input type="text" name="discount" class="form-control "  placeholder="折扣" value="{{ old('discount') }}">
                            @if ($errors->has('discount')) <p class="help-block">{{ $errors->first('discount') }}</p> @endif

                        </div>



                      </div>
                      <div class="box-footer">
                        <button type="submit" class="btn btn-primary">保存</button>
                      </div>
                    </form>
                  </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript">

        function gradeChange(platform_id) {
              $.post("{{ route('api.position.option') }}", {platform_id: platform_id}, function(data) {
                if(data.code==0 ){
                     $("#test").replaceWith('<div class="checkbox" id="test">'+data.data+'</div>');
                }
            });
        }
        set_active_menu('ad_manage',"{{ route('admin.adtask.index') }}");
    </script>
@endsection
