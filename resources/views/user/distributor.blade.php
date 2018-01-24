@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row bg-title" style="border-shadow:1px 0px 20px rgba(0, 0, 0, 0.08)">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">经销商管理</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="float-btn-group">
                    <div class="btn-list">
                        <a class="btn-float blue waves-effect waves-light" href="/user/distributor/create"><i class="fa fa-plus"></i></a>
                        <a href="#" class="btn-float blue search waves-effect waves-light"><i class="fa fa-search"></i></a>
                        <a href="#" class="btn-float blue download waves-effect waves-light"><i class="fa fa-download"></i></a>
                        <a href="#" class="btn-float blue waves-effect waves-light" data-toggle="modal" data-target="#upload"><i class="fa fa-upload"></i></a>
                     </div>
                    <button class="btn-float btn-triger pink"><i class="icon-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="left-sidebar" style="display: block; ">
            <div class="slimScrollDiv"><div class="slimscrollright">
                    <div class="r-panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12" style="margin-top: 25px;">
                                <form action="" class="searchs">
                                    <div class="form-group col-sm-3">
                                        <label >用户编码</label>
                                        <input type="text" class="form-control" id="user_code" name="user_code">
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label >用户名</label>
                                        <input type="text" class="form-control" id="name" name='name'>
                                    </div>

                                    <div class="form-group col-sm-3">
                                        <label>级别</label>
                                        <div class="col-md-12-select">
                                            <select class="selectpicker bs-select-hidden" data-style="btn-info btn-outline" id="level" name="level">
                                                <option value="">请选择级别</option>
                                                @foreach (Auth::user()->levelMap as $key  => $level)
                                                    <option value="{{$key}}">{{$level}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label>信用等级</label>
                                        <div class="col-md-12-select">
                                            <select class="selectpicker bs-select-hidden" data-style="btn-info btn-outline" id="credit" name="credit">
                                                <option value="">请选择信用等级</option>
                                                @foreach (Auth::user()->creditMap as $key  => $level)
                                                    <option value="{{$key}}">{{$level}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group col-sm-3">
                                        <label for="selectpicker">状态</label>
                                        <div class="col-md-12-select">
                                            <select class="selectpicker bs-select-hidden" data-style="btn-info btn-outline" id="status" name="status">
                                                <option value="">请选择状态</option>
                                                <option value="1">开启</option>
                                                <option value="0">关闭</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 search-button-div"><a class="btn btn-warning waves-effect waves-light pull-right search-close">取消</a> <button type="submit" class="btn btn-info waves-effect waves-light pull-right m-r-10">搜索</button></div>
                                </form>        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="table-responsive">
                        <table class="table table-hover manage-u-table">
                            <thead>
                            <tr>
                                <th class="text-center">编码</th>
                                <th>用户名 </th>
                                <th>EMAIL</th>
                                <th>级别</th>
                                <th>信用等级</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td class="text-center">{{$user->user_code}}</td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->levelMap[$user->level]}}</td>
                                <td>{{$user->creditMap[$user->credit]}}</td>
                                <td>{{$user->status? "开启" : "关闭"}}</td>
                                <td>
                                    <a type="button" href="/user/distributor/edit?id={{Hashid::encode($user->id)}}" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="ti-pencil-alt"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="col-sm-12">{!!$users->render()!!}</div>
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- 批量上传模态框（Modal） -->
        <div class="modal fade" id="upload" tabindex="-1" role="dialog" aria-labelledby="uploadLabel" aria-hidden="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
                        <h3 class="modal-title" style="text-align: center;" id="uploadLabel">批量上传</h3>
                    </div>
                    <div class="modal-body" style="overflow: hidden;">
                        <form method="POST" action="{{ url('/user/distributor/upload') }}" enctype="multipart/form-data">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <span class="btn btn-default btn-file"><span class="fileinput-new">选择文件</span><span class="fileinput-exists">重新选项</span><input type="file" name="upload"></span>
                                        <span class="fileinput-filename"></span>
                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
                                    </div>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" name="submit" value="上传" class="btn btn-primary once-click"/>
                                    
                                    <a href="/template/distributor.xlsx" type="button" class="btn btn-link pull-right"><li class="fa fa-download"></li>模板下载</a>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal -->
        </div>
    </div>


    
  @endsection
  
@section('extend_js')
<script type="text/javascript">
    $(".search").click(function(){
        if($(".left-sidebar").hasClass('shw-left-rside')){
            $(".left-sidebar").removeClass('shw-left-rside');
        }else{
            $(".left-sidebar").addClass('shw-left-rside');
        }
        $(".shw-left-rside").width($(".bg-title").width() + 20);
    });


    $(".search-close").click(function(){
        $(".left-sidebar").removeClass('shw-left-rside');
    });


    $('.download').click(function () {
        var search = window.location.search;
        swal({
          dangerMode: true,
          title: "确定要下载当前搜索数据吗?",
          type: "info",
          icon: "info",
          buttons: [ "取消" ,"确认"],
        })
        .then(willDelete => {
          if (willDelete) {
            window.open('/user/distributor/download' + search);
          }
        });


    });

</script>
@endsection