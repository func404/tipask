@extends('admin/public/layout')

@section('title')
    广告位管理
@endsection

@section('content')
    <section class="content-header">
        <h1>
            广告位管理
            <small>显示当前所拥有的广告位</small>
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
                                    <a href="{{ route('admin.adposition.create') }}" class="btn btn-default btn-sm" data-toggle="tooltip" title="添加新广告位"><i class="fa fa-plus"></i></a>
                                    <button class="btn btn-default btn-sm" data-toggle="tooltip" title="删除选中项" onclick="confirm_submit('item_form','{{  route('admin.adposition.delete') }}','确认删除选中项？')"><i class="fa fa-trash-o"></i></button>
                                </div>
                            </div>
                            <div class="col-xs-9">
                                <div class="row">
                                    <form name="searchForm" action="{{ route('admin.adposition.index') }}" method="GET">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                       <div class="col-xs-2">
                                <select class="form-control" name="platform_id">
                                       <option value="0">选择平台</option>
                                             @include('admin.platform.option',['type'=>'platform','select_id'=>$filter['platform_id']??0])
                                </select>
                         </div>
                                        <div class="col-xs-2 hidden-xs">
                                            <input type="text" class="form-control" name="position_id" placeholder="PID" value="{{ $filter['position_id'] or '' }}"/>
                                        </div>

                                        <div class="col-xs-3 hidden-xs">
                                            <input type="text" class="form-control" name="mark" placeholder="广告位标识" value="{{ $filter['mark'] or '' }}"/>
                                        </div>
                                        <div class="col-xs-2">
                                            <select class="form-control" name="status">
                                                <option value="-9">全部</option>
                                                @foreach(trans_common_disable('all') as $key => $status)
                                                    <option value="{{ $key }}" @if( isset($filter['status']) && $filter['status']==$key) selected @endif >{{ $status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                      <!--   <div class="col-xs-3 hidden-xs">
                                            <input type="text" name="date_range" id="date_range" class="form-control" placeholder="时间范围" value="{{ $filter['date_range'] or '' }}" />
                                        </div> -->
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
                                        <th>广告位ID</th>
                                        <th>广告位平台</th>
                                        <th>广告位标识</th>
                                        <th>广告描述</th>
                                        <th>创建时间</th>
                                        <th>更新时间</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    @foreach($positions as $position)
                                        <tr>
                                            <td><input type="checkbox" value="{{ $position->id }}" name="id[]"/></td>
                                            <td>{{ $position->id }}</td>
                                            <td>{{ $position->platform->name??''}}</td>
                                            <td>{{ $position->mark }}</td>
                                            <td>{{ $position->describe }}</td>
                                            <td>{{ $position->created_at }}</td>
                                            <td>{{ $position->updated_at }}</td>
                                            <td><span class="label @if($position->disable==0) label-default @elseif($position->disable==1) label-success @endif">{{ trans_common_disable($position->disable) }}</span> </td>
                                            <td>
                                                <div class="btn-group-xs" >
                                                    <a class="btn btn-default" href="{{ route('admin.adposition.edit',['id'=>$position->id]) }}" data-toggle="tooltip" title="编辑广告位"><i class="fa fa-edit"></i></a>
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
                                    <a href="{{ route('admin.adposition.create') }}" class="btn btn-default btn-sm" data-toggle="tooltip" title="创建新用户"><i class="fa fa-plus"></i></a>
                                    <button class="btn btn-default btn-sm" data-toggle="tooltip" title="删除选中项" onclick="confirm_submit('item_form','{{  route('admin.adposition.delete') }}','确认删除选中项？')"><i class="fa fa-trash-o"></i></button>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="text-right">
                                    <span class="total-num">共 {{ $positions->total() }} 条数据</span>
                                    {!! str_replace('/?', '?', $positions->appends($filter)->links()) !!}
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
        set_active_menu('ad_manage',"{{ route('admin.adposition.index') }}");
    </script>
@endsection
