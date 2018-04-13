@extends('layouts.main')
@section('extend_css')
<style href="/libs/icheck/skins/square/blue.css" rel="stylesheet" type="text/css"></style>
<style type="text/css">
    .icheckbox_square-blue, .iradio_square-blue{
        right: 15px;
        bottom: 3px;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">霍兰德职业倾向测试</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <h1 style="text-align: center;padding-top: 30px;font-weight: bold;">霍兰德职业倾向测试</h1>
                    <p style="text-align: center;padding: 10px;font-weight: 10px;color: red">你将参与测试的这个《职业兴趣倾向测验》，可以帮助你做一次简单的兴趣测评，从而更加清楚自己的兴趣特征。请根据对每一题目的第一印象作答，不必仔细推敲，答案没有好坏、对错之分。根据自己的实际情况回答(符合你的情况请在题干前面的方框内打勾)</p>
                    <div class="panel-body">
                            @foreach($questions as $question)
                                <div class="col-md-12">
                                      <input id="{{$question->id}}" data-id="{{$question->id}}" type="checkbox" class="iCheck">
                                      <label style="font-size: 20px" for="{{$question->id}}"> {{$question->content}} </label>                                      
                                </div>
                            @endforeach
                            <div class="row">
                                <div class="col-md-2 col-md-offset-10" style="margin-top: 20px;">
                                    <button class="btn btn-info test_submit">提交</button>
                                    <button class="btn btn-default"  onclick="window.history.go(-1)">退出</button>
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
    $('.iCheck').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '30%',
    });
    $('.test_submit').click(function(){
        
        swal({
          dangerMode: true,
          title: "确定提交，提交后将无法修改?",
          type: "info",
          icon: "info",
          buttons: [ "取消" ,"确认"],
        })
        .then(willDelete => {
          if (willDelete) {
                var ids = '';
                $('.iCheck').each(function(){
                    if(true == $(this).is(':checked')){
                        ids += $(this).data('id') + ',';
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "/test/holand/submit",
                    dataType: "json",
                    data : {
                        ids : ids,
                    },
                    success: function(d) {
                        if(d.result){
                            swal(d.message, "", "success");
                            window.location.href = '/test/holand/report';
                        }else{
                            swal(d.message, "", "error");
                        }
                    }
                });
          }
        });
    });

</script>

@endsection