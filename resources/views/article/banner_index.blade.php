@extends('layouts.main')

<style type="text/css">
    .imageDiv{
        width: 40px;
        height: 40px;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }
</style>
@section('content')
    <div class="container-fluid">
        <div class="row bg-title" style="border-shadow:1px 0px 20px rgba(0, 0, 0, 0.08)">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Banner列表</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="float-btn-group" style="display: block;">
                    <div class="btn-list">
                        <a class="btn-float blue waves-effect waves-light" href="/banner/create"><i class="fa fa-plus" style="margin-top: 4px;"></i></a>
                        <a href="#" class="btn-float blue search waves-effect waves-light"><i class="fa fa-search" style="margin-top: 4px;"></i></a>
                     </div>
                    <button class="btn-float btn-triger pink"><i class="icon-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="left-sidebar" style="display: block; ">
            <div class="slimScrollDiv">
                <div class="slimscrollright">
                    <div class="r-panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12 m-t-20">
                                <form action="" class="searchs">
                                    <div class="form-group col-sm-3">
                                        <label >Banner编码</label>
                                        <input type="text" class="form-control" id="id" name="id">
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label >Banner名称</label>
                                        <input type="text" class="form-control" id="name" name='name'>
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
                        <table class="table table-hover manage-u-table" style="font-size: 14px">
                            <thead>
                            <tr>
                                <th class="text-center">编码</th>
                                <th>Banner名称</th>
                                <th>顺序</th>
                                <th>图片</th>
                                <th>文章地址</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($banners as $banner)
                            <tr>
                                <td class="text-center">{{$banner->id}}</td>
                                <td>{{$banner->title}}</td>
                                <td>{{$banner->index}}</td>
                                <td>
                                    <div class="imageDiv" style="background-image: url({{$banner->images}});">
                                        
                                    </div>
                                </td>

                                <td><a class="btn btn-info" href="{{$banner->link_url}}" target="_blank">查看</a></td>

                                <td>{{$banner->created_at}}</td>
                                <td>
                                    <a type="button" href="/banner/create?id={{$banner->id}}" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="ti-pencil-alt"></i></a>
                                    <button type="button" class="btn btn-danger btn-outline btn-circle btn-sm m-r-5 delete" data-id="{{$banner->id}}"><i class="ti-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  @endsection
  
@section('extend_js')
<script type="text/javascript">
    $('.delete').click(function () {
        var id = $(this).data('id')
        swal({
          dangerMode: true,
          title: "确定要删除吗?",
          type: "warning",
          icon: "warning",
          buttons: ["取消","确认"],
        })
        .then(willDelete => {
          if (willDelete) {

            $.ajax({
                type: "post",
                url: "/banner/delete",
                dataType: "json",
                data :{
                    id : id,
                },
                success: function(r) {
                    if(r.result){
                        swal(r.message, "", "success");
                        window.location.reload();
                    }else{
                        swal(r.message, "", "error");
                    }
                }
            });
          }
        });
    });
    
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

</script>
@endsection