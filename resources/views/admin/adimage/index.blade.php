@extends('admin/public/layout')

@section('title')
    素材管理
@endsection

@section('content')
    <section class="content-header">
        <h1>
            素材列表
            <small>显示当前所拥有的素材</small>
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
                                    <a href="{{ route('admin.adimage.create') }}" class="btn btn-default btn-sm" data-toggle="tooltip" title="添加新素材"><i class="fa fa-plus"></i></a>
                                    <button class="btn btn-default btn-sm" data-toggle="tooltip" title="删除选中项" onclick="confirm_submit('item_form','{{  route('admin.adimage.delete') }}','确认删除选中项？')"><i class="fa fa-trash-o"></i></button>
                                </div>
                            </div>
                            <div class="col-xs-9">
                                <div class="row">
                                    <form name="searchForm" action="{{ route('admin.adimage.index') }}" method="GET">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="col-xs-2 hidden-xs">
                                            <input type="text" class="form-control" name="image_id" placeholder="ID" value="{{ $filter['image_id'] or '' }}"/>
                                        </div>
                                        <div class="col-xs-3 hidden-xs">
                                            <input type="text" class="form-control" name="name" placeholder="素材名称" value="{{ $filter['name'] or '' }}"/>
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
                                        <th>素材ID</th>
                                        <th>素材名称</th>
                                        <th>素材地址</th>
                                        <th>创建时间</th>
                                        <th>更新时间</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>

                                    @foreach($images as $image)
                                        <tr>
                                            <td><input type="checkbox" value="{{ $image->id }}" name="id[]"/></td>
                                            <td>{{ $image->id }}</td>
                                            <td>{{ $image->name }}</td>
                                            <td><a href="{{ $image->url }}" target="_blank" >{{ $image->url }}</a></td>
                                            <td>{{ $image->created_at }}</td>
                                            <td>{{ $image->updated_at }}</td>
                                            <td><span class="label @if($image->disable==0) label-default @elseif($image->disable==1) label-success @endif">{{ trans_common_disable($image->disable) }}</span> </td>
                                            <td>
                                                <div class="btn-group-xs" >
                                                    <a class="btn btn-default" href="{{ route('admin.adimage.edit',['id'=>$image->id]) }}" data-toggle="tooltip" title="编辑素材信息"><i class="fa fa-edit"></i></a>
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
                                    <a href="{{ route('admin.adimage.create') }}" class="btn btn-default btn-sm" data-toggle="tooltip" title="添加新素材"><i class="fa fa-plus"></i></a>
                                    <button class="btn btn-default btn-sm" data-toggle="tooltip" title="删除选中项" onclick="confirm_submit('item_form','{{  route('admin.adimage.delete') }}','确认删除选中项？')"><i class="fa fa-trash-o"></i></button>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="text-right">
                                    <span class="total-num">共 {{ $images->total() }} 条数据</span>
                                    {!! str_replace('/?', '?', $images->appends($filter)->links()) !!}
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
        set_active_menu('ad_manage',"{{ route('admin.adimage.index') }}");
    </script>
@endsection
