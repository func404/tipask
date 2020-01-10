@extends('admin/public/layout')

@section('css')
    <link href="{{ asset('/static/js/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" />
@endsection

@section('title')
    编辑平台信息
@endsection

@section('content')
    <section class="content-header">
        <h1>
            编辑平台信息
            <small>编辑平台信息</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">基本资料</h3>
                    </div>
                    <form role="form" name="userForm" method="POST" action="{{ route('admin.platform.update') }}">

                        <input name="_method" type="hidden" value="PUT">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-body">
                          <div class="form-group @if ($errors->has('name')) has-error @endif">
                              <label for="name">平台名称</label>
                               <input type="hidden" name="id" value="{{ $platform->id }}">
                              <input type="text" name="name" class="form-control " placeholder="平台名称" value="{{ old('name',$platform->name) }}">
                              @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                          </div>

                          <div class="form-group @if ($errors->has('host')) has-error @endif">
                              <label for="name">IP</label>
                              <input type="text" name="host" class="form-control " placeholder="IP地址" value="{{ old('host',$platform->host) }}">
                              @if ($errors->has('host')) <p class="help-block">{{ $errors->first('host') }}</p> @endif
                          </div>


                            <div class="form-group">
                                <label>状态</label>
                                <span class="text-muted">(禁用后平台将不可用)</span>
                                <div class="radio">
                                    @foreach(trans_common_disable('all') as $key => $status)
                                        <label>
                                            <input type="radio" name="status" value="{{ $key }}" @if($platform->disable === $key) checked @endif /> {{ $status }}
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

            set_active_menu('platform_manage',"{{ route('admin.platform.index') }}");

    </script>
@endsection
