@extends('layouts.main')

@section('extend_css')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">编辑文章分类</h4>
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
                                            <label class="control-label">文章分类名称 <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" value="{{$category->category_name}}" >
                                            <input type="hidden" name="id" class="form-control" value="{{$category->id}}" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">文章分类颜色 <span class="text-danger">*</span></label>
                                            <select class="form-control" tabindex="1" name="color">
                                                <option value="">请选择文章色调</option>
                                                @foreach ($colorMap as $key => $value)
                                                    <option value="{{$key}}" {{$key == $category->content_color ? 'selected' : ''}} >{{$value}}</option>
                                                @endforeach
                                            </select>
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
            var category_name = $('[name=name]').val();
            var content_color = $('[name=color]').val();
            var id = $('[name=id]').val();

            $.ajax({
                type: "POST",
                url: "/article/category/edit",
                dataType: "json",
                data : {
                    category_name : category_name,
                    id : id,
                    content_color : content_color,
                },
                success: function(d) {
                    if(d.result){
                        swal(d.message, "", "success");
                        window.location.href = '/article/category'
                    }else{
                        $('.help-block').remove();
                        $.each(d.message, function(key,value){
                            $.each(value, function(k,v){
                                toastr.warning(v);
                            });
                        })
                    }
                }
            });
        })

    </script>
@endsection