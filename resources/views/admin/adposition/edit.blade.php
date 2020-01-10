@extends('admin/public/layout')

@section('css')
    <link href="{{ asset('/static/js/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" />
@endsection

@section('title')
    编辑广告位信息
@endsection

@section('content')
    <section class="content-header">
        <h1>
             编辑广告位信息
            <small> 编辑广告位信息</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">广告位信息</h3>
                    </div>
                    <form role="form" name="userForm" method="POST" action="{{ route('admin.adposition.update') }}">

                        <input name="_method" type="hidden" value="PUT">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-body">
                          <div class="form-group @if ($errors->has('mark')) has-error @endif">
                              <label for="name">广告位标识</label>
                               <input type="hidden" name="id" value="{{ $position->id }}">
                              <input type="text" name="mark" class="form-control " placeholder="广告位标识" value="{{ old('mark',$position->mark) }}">
                              @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                          </div>

                          <div class="form-group @if ($errors->has('describe')) has-error @endif">
                              <label for="name">广告位描述</label>
                              <input type="text" name="describe" class="form-control " placeholder="广告位描述文字" value="{{ old('describe',$position->describe) }}">
                              @if ($errors->has('describe')) <p class="help-block">{{ $errors->first('describe') }}</p> @endif
                          </div>

                           <div class="form-group">
                                <select class="form-control" name="platform_id">
                                       <option value="0">选择平台</option>
                                             @include('admin.platform.option',['type'=>'platform','select_id'=>$position->platform_id])
                                </select>
                         </div>


                            <div class="form-group">
                                <label>状态</label>
                                <span class="text-muted">(禁用后平台将不可用)</span>
                                <div class="radio">
                                    @foreach(trans_common_disable('all') as $key => $status)
                                        <label>
                                            <input type="radio" name="status" value="{{ $key }}" @if($position->disable === $key) checked @endif /> {{ $status }}
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

            set_active_menu('ad_manage',"{{ route('admin.adposition.index') }}");

    </script>
@endsection
