@extends('layouts.main')

@section('extend_css')

@endsection

@section('content')
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">新增/编辑文章</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        
                        <div class="panel-body">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6" style="display: none;">
                                        <div class="form-group">
                                            <label class="control-label">ID <span class="text-danger">*</span></label>
                                            <input type="text" id="title" name="id" class="form-control" value="{!!isset($article->id)? $article->id : "" !!}" >
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">标题 <span class="text-danger">*</span></label>
                                            <input type="text" id="title" name="title" class="form-control" value="{!!isset($article->title)? $article->title : "" !!}" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">文章分类</label>
                                            <select class="form-control" tabindex="1" name="article_category_id">
                                                <option value="">请选择文章分类</option>
                                                @foreach ($categories as $one)
                                                    <option value="{{$one['id']}}" {{ $article['article_category_id'] == $one['id'] ? 'selected' : "" }}>{{$one['category_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">作者</label>
                                            <input type="text" id="author" name="author" class="form-control" readonly value="{{Auth::user()->name}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 form-group">
                                        <div class="form-group">
                                            <label class="control-label">文章内容</label>
                                            <script type="text/plain" id="editor"  name="content" style="width:100%; height: 600px;">
                                                {!!isset($article->content)? $article->content : "" !!}
                                            </script>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>
                                <div class="row">
                                    <div class="col-md-4 col-md-offset-10">
                                        <button type="submit" class="btn btn-info article_submit">提交</button>
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
<script type="text/javascript" charset="utf-8" src="/libs/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/libs/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">
@if ( session('result'))
    toastr.success("{{session('message')}}");
@endif
    var ue = UE.getEditor('editor');
</script>


<script type="text/javascript">
$('.article_submit').click(function(){
    var title = $('[name=title]').val();
    var content = ue.getContent();
    var article_category_id = $('[name=article_category_id]').val();
    var id = $('[name=id]').val();

    $.ajax({
        type: "POST",
        url: "/article/create",
        dataType: "json",
        data : {
            title : title,
            content : content,
            article_category_id : article_category_id,
            id : id
        },
        success: function(d) {
            if(d.result){
                swal(d.message, "", "success");
                window.location.href = '/article/create?id=' + d.id;
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

 
});
</script>

@endsection