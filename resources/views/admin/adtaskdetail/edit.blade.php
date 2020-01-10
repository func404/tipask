@extends('admin/public/layout')

@section('css')
    <link href="{{ asset('/static/js/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" />
@endsection

@section('title')
    编辑广告任务明细
@endsection

@section('content')
    <section class="content-header">
        <h1>
             编辑广告任务明细
            <small> 编辑广告任务明细</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">广告任务明细</h3>
                    </div>
                    <form role="form" name="userForm" method="POST" action="{{ route('admin.adtaskdetail.update') }}">

                        <input name="_method" type="hidden" value="PUT">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="{{ $task_detail->id }}">
                        <div class="box-body">

                           <div class="form-group">
                                <select class="form-control" name="ad_image_id">
                                       <option value="0">选择图片</option>
                                             @include('admin.adimage.option',['type'=>'all','select_id'=>$task_detail->ad_image_id??0])
                                </select>
                         </div>


                            <div class="form-group">
                                <label>状态</label>
                                <span class="text-muted">(禁用后平台将不可用)</span>
                                <div class="radio">
                                    @foreach(trans_common_disable('all') as $key => $status)
                                        <label>
                                            <input type="radio" name="status" value="{{ $key }}" @if($task_detail->disable === $key) checked @endif /> {{ $status }}
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

            set_active_menu('ad_manage',"{{ route('admin.adtaskdetail.index') }}");

    </script>
@endsection
