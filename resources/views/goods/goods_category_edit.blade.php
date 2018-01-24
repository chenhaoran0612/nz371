@extends('layouts.main')

@section('extend_css')

@endsection

@section('content')
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">编辑商品分类</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">
                            <div class="form-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">分类名称 <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" value="{{$goodsCategory->name}}" >
                                            <input type="hidden" name="id" class="form-control" value="{{Hashid::encode($goodsCategory->id)}}" >
                                        </div>
                                    </div>

                                </div>
                                <hr>
                            </div>
                            <div class="form-actions m-t-40">
                                <div class="row">
                                    <div class="col-md-4 col-md-offset-10">
                                        <button type="submit" class="btn btn-info category_submit">提交</button>
                                        <button type="button" class="btn btn-default"  onclick="window.history.go(-1)">返回</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
  @endsection
  
@section('extend_js')
    <script type="text/javascript">

        $('.category_submit').click(function(){
            var name = $('[name=name]').val();
            var id = $('[name=id]').val();

            $.ajax({
                type: "POST",
                url: "/goods/category/edit",
                dataType: "json",
                data : {
                    name : name,
                    id : id,
                },
                success: function(d) {
                    if(d.result){
                        swal(d.message, "", "success");
                        window.location.href = '/goods/category'
                    }else{
                        $('.help-block').remove();
                        $.each(d.message, function(key,value){
                            $.each(value, function(k,v){
                                $('[name='+key+']').closest('div').append('<span class="help-block text-danger">'+v+'</span>');
                            });
                        })
                    }
                }
            });
        })

    </script>
@endsection