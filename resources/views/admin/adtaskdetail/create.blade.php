@extends('admin/public/layout')

@section('title')
    添加广告位
@endsection

@section('content')
    <section class="content-header">
        <h1>
            添加广告位
            <small>添加广告位</small>
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
                    <form role="form" name="userForm" method="POST" action="{{ route('admin.adposition.store') }}">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <div class="box-body">
                        <div class="form-group @if ($errors->has('mark')) has-error @endif">
                          <label>广告位标识</label>
                          <input type="text" name="mark" class="form-control "  placeholder="广告位标识" value="{{ old('mark') }}">
                            @if ($errors->has('mark')) <p class="help-block">{{ $errors->first('mark') }}</p> @endif

                        </div>
                        <div class="form-group ">
                          <label>广告位描述</label>
                          <input type="text" name="describe" class="form-control"  placeholder="广告位描述文字" value="{{ old('describe') }}">
                            @if ($errors->has('describe')) <p class="help-block">{{ $errors->first('describe') }}</p> @endif

                        </div>

                        <div class="form-group">
                                <select class="form-control" name="platform_id">
                                       <option value="0">选择平台</option>
                                             @include('admin.platform.option',['type'=>'platform','select_id'=>0])
                                </select>
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
        set_active_menu('ad_manage',"{{ route('admin.adposition.index') }}");
    </script>
@endsection
