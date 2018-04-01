@extends('layouts.main')
@section('extend_css')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Banner管理</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade active in" id="basic">
                                        <div class="col-md-6" style="display: none;">
                                            <div class="form-group">
                                                <label class="control-label">ID <span class="text-danger">*</span></label>
                                                <input type="text" id="id" name="id" class="form-control" value="{{$banner ? $banner->id : ''}}" >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Banner标题 <span class="text-danger">*</span></label>
                                                <input type="text" id="title" name="title" class="form-control" value="{{$banner ? $banner->title : ''}}" >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">跳转地址 <span class="text-danger">*</span></label>
                                                <input type="text" id="link_url" name="link_url" class="form-control" value="{{$banner ? $banner->link_url : ''}}" >
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="tab-pane row goodsImageParent">
                                                    <div class="col-md-12 form-group">
                                                        <label class="control-label">Banner图片</label>
                                                        <hr>
                                                        <div class="banner-images" >
                                                            @include('form.upload-image-common')
                                                        </div>
                                                        <input type="hidden" name="images" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            </div>
                            <div class="form-actions m-t-40">
                                <div class="row">
                                    <div class="col-md-4 col-md-offset-10">
                                        <button type="submit" class="btn btn-info banner_submit">提交</button>
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
    <script src="/js/uploadImage.js"></script>
    <script type="text/javascript">
        //初始化图片
        var goodsImageUrl = '/banner/images?id=' + {{ $banner?  $banner->id : ''}};
        var parentClassName = 'banner-images';
        initUploadImage(parentClassName, goodsImageUrl, 180, 180);
       
        $('.banner_submit').click(function(){
            var images = getUploadImage('banner-images');
            var title = $('[name=title]').val();
            var id = $('[name=id]').val();
            var link_url = $('[name=link_url]').val();
            $.ajax({
                type: "POST",
                url: "/banner/create",
                dataType: "json",
                data : {
                    id : id,
                    title : title,
                    images : images,
                    link_url : link_url
                },
                success: function(d) {
                    if(d.result){
                        swal(d.message, "", "success");
                        window.location.href = '/banner/index'
                    }else{
                        $('.help-block').remove();

                        if(typeof(d.message) == 'string'){
                            toastr.warning(d.message);
                        }else{
                            $.each(d.message, function(key,value){
                                $.each(value, function(k,v){
                                    $('[name='+key+']').closest('div').parent().find('.control-label').append('<span class="text-danger">'+v+'</span>');
                                });
                            });
                        }
                    }
                }
            });
        });


    </script>
@endsection