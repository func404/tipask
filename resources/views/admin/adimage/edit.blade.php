@extends('admin/public/layout')

@section('css')
    <link href="{{ asset('/static/js/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" />
@endsection

@section('title')
    编辑素材信息
@endsection

@section('content')
    <section class="content-header">
        <h1>
            编辑图片信息
            <small>编辑素材信息</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">素材信息</h3>
                    </div>
                    <form role="form" name="userForm" method="POST" enctype="multipart/form-data" action="{{ route('admin.adimage.update') }}">

                        <input name="_method" type="hidden" value="PUT">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-body">

                          <div class="form-group @if ($errors->has('name')) has-error @endif">
                          <label>素材名称</label>
                          <input type="hidden" name="id" value="{{ $image->id }}">
                          <input type="text" name="name" class="form-control "  placeholder="素材名称" value="{{ old('name',$image->name) }}">
                            @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif

                        </div>
                            <div class="form-group">
                                <label>素材文件</label>
                                <div style="margin-top: 10px;">
                                    <img src="{{ $image->url }}" width="300"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>修改素材文件</label>
                                <input type="file" name="image"  accept="image/*"/>
                            </div>


                            <div class="form-group">
                                <label>状态</label>
                                <span class="text-muted">(禁用后平台将不可用)</span>
                                <div class="radio">
                                    @foreach(trans_common_disable('all') as $key => $status)
                                        <label>
                                            <input type="radio" name="status" value="{{ $key }}" @if($image->disable === $key) checked @endif /> {{ $status }}
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

            set_active_menu('ad_manage',"{{ route('admin.adimage.index') }}");

    </script>
@endsection
