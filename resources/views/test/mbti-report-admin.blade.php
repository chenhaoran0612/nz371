@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row bg-title" style="border-shadow:1px 0px 20px rgba(0, 0, 0, 0.08)">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">MBTI测试报告列表</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="float-btn-group">
                    <div class="btn-list">
                        <a href="#" class="btn-float blue search waves-effect waves-light"><i class="fa fa-search"></i></a>
                     </div>
                    <button class="btn-float btn-triger pink"><i class="icon-bars"></i></button>
                </div>
            </div>

            <div class="left-sidebar" style="display: block; ">
                <div class="slimScrollDiv"><div class="slimscrollright">
                        <div class="r-panel-body">
                            <div class="row">
                                <div class="col-sm-12 col-xs-12 m-t-20">
                                    <form action="" class="searchs">
                                        <div class="form-group col-sm-3">
                                            <label >学生昵称</label>
                                            <input type="text" class="form-control" id="nick_name" name="nick_name">
                                        </div>
                                        
                                        <div class="col-sm-12 search-button-div"><a class="btn btn-warning waves-effect waves-light pull-right search-close">取消</a> <button type="submit" class="btn btn-info waves-effect waves-light pull-right m-r-10">搜索</button></div>
                                    </form>        
                                </div>
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
                                <th>测试人</th>
                                <th>测试时间</th>
                                <th>测试结果</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($mbtiTests as $mbtiTest)
                            <tr>
                                <td>{{$mbtiTest->test_name}}</td>
                                <td>{{$mbtiTest->created_at}}</td>
                                <td>{{$mbtiTest->result}}</td>
                                <td>
                                    <a type="button" href="/test/mbti/admin/detail?id={{$mbtiTest->id}}" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="ti-eye"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-12">{!!$mbtiTests->render()!!}</div>

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


    $(".search-close").click(function(){
        $(".left-sidebar").removeClass('shw-left-rside');
    });
    

</script>
@endsection