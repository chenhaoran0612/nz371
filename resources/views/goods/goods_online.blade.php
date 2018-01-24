@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row bg-title" style="border-shadow:1px 0px 20px rgba(0, 0, 0, 0.08)">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">商品列表</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="float-btn-group">
                    <div class="btn-list">
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
                                        <label >商品编码</label>
                                        <input type="text" class="form-control" id="id" name="id">
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label >标题</label>
                                        <input type="text" class="form-control" id="title" name='title'>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label>供应商</label>
                                        <div class="col-md-12-select">
                                            <select class="selectpicker bs-select-hidden" data-style="btn-info btn-outline" id="vendor_id" name="vendor_id">
                                                <option value="">请选择供应商</option>
                                                @foreach ($vendors as $vendor)
                                                    <option value="{{Hashid::encode($vendor->id)}}">{{$vendor->name}}</option>
                                                @endforeach
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
                                <th>SKU</th>
                                <th>标题</th>
                                <th>价格</th>
                                <th>在线状态</th>
                                <th>经销商</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($goodsOnline as $goods)
                            <tr>
                                <td class="text-center">{{Hashid::encode($goods->id)}}</td>
                                <td>{{$goods->item_number}}</td>
                                <td>{{empty($goods->title)?$goods->goodsBasic->title:$goods->title}}</td>
                                <td>
                                    @if ($goods->price)
                                        {{$goods->currency}} {{$goods->price}}
                                    @endif
                                </td>
                                <td><input type="checkbox" class="js-switch" data-color="#41b3f9" name="status" {{$goods->online_status == 1 ? 'checked' : ''}} data-id="{{Hashid::encode($goods->id)}}"/></td>
                                <td>{{$goods->vendor->name}}</td>
                                <td>
                                    <a type="button" href="/goods/online/edit?id={{Hashid::encode($goods->id)}}" class="btn btn-info btn-outline btn-circle btn-sm m-r-5" ><i class="ti-pencil-alt"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="col-sm-12">{!!$goodsOnline->render()!!}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    
  @endsection
  
@section('extend_js')
<script src="/libs/switchery/dist/switchery.js"></script>

<script type="text/javascript">
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());

        $(this)[0].onchange = function() {
            if ($(this)[0].checked) {
                var status = 1
            } else {
                var status = 2;
            }
            var id = $(this).data('id');

            $.ajax({
                type: "POST",
                url: "/goods/online/status",
                dataType: "json",
                data : {
                    status : status,
                    id : id,
                },
                success: function(d) {
                    if(d.result){
                        toastr.success(d.message);
                    }else{
                        toastr.error(d.message);
                        window.location.reload();
                    }
                }
            });
        };
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

    $('.goods-push').click(function(){
        var id = $(this).data('id');
        $.ajax({
            type: "post",
            url: "/goods/online/push",
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
    })

</script>
@endsection