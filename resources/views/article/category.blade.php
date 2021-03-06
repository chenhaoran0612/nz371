@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row bg-title" style="border-shadow:1px 0px 20px rgba(0, 0, 0, 0.08)">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">文章分类</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="float-btn-group">
                    <div class="btn-list">
                        <a class="btn-float blue waves-effect waves-light" href="/article/category/create"><i class="fa fa-plus"></i></a>
                        <a href="#" class="btn-float blue search waves-effect waves-light"><i class="fa fa-search"></i></a>
                     </div>
                    <button class="btn-float btn-triger pink"><i class="icon-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="left-sidebar" style="display: block; ">
            <div class="slimScrollDiv"><div class="slimscrollright">
                    <div class="r-panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12 m-t-20">
                                <form action="" class="searchs">
                                    <div class="form-group col-sm-3">
                                        <label >分类编码</label>
                                        <input type="text" class="form-control" id="id" name="id">
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label >分类名称</label>
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
                        <table class="table table-hover manage-u-table">
                            <thead>
                            <tr>
                                <th class="text-center">编码</th>
                                <th>文章分类名称</th>
                                <th>文章颜色</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td class="text-center">{{$category->id}}</td>
                                <td>{{$category->category_name}}</td>
                                <td>{{ $colorMap[$category->content_color] }}</td>
                                <td>
                                    <a type="button" href="/article/category/edit?id={{$category->id}}" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="ti-pencil-alt"></i></a>
                                    <button type="button" class="btn btn-danger btn-outline btn-circle btn-sm m-r-5 delete" data-id="{{$category->id}}"><i class="ti-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="col-sm-12">{!!$categories->render()!!}</div>
                    </div>
                </div>
            </div>
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
                url: "/category/delete",
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

    $(".search-close").click(function(){
        $(".left-sidebar").removeClass('shw-left-rside');
    });

</script>
@endsection