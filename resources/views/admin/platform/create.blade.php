@extends('admin/public/layout')

@section('title')
    添加平台
@endsection

@section('content')
    <section class="content-header">
        <h1>
            添加平台
            <small>添加平台</small>
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
                    <form role="form" name="userForm" method="POST" action="{{ route('admin.platform.store') }}">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <div class="box-body">
                        <div class="form-group @if ($errors->has('name')) has-error @endif">
                          <label>平台名称</label>
                          <input type="text" name="name" class="form-control "  placeholder="平台名称" value="{{ old('name') }}">
                            @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif

                        </div>
                        <div class="form-group @if ($errors->has('email')) has-error @endif">
                          <label>平台IP</label>
                          <input type="host" name="host" class="form-control"  placeholder="平台IP(例：123.13.123.12)" value="{{ old('email') }}">
                            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif

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
        set_active_menu('platform_manage',"{{ route('admin.platform.index') }}");
    </script>
@endsection
