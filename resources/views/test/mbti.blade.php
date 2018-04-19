@extends('layouts.main')
@section('extend_css')
<style href="/libs/icheck/skins/square/blue.css" rel="stylesheet" type="text/css"></style>
<style type="text/css">
    .icheckbox_square-blue, .iradio_square-blue{
        margin: 10px!important;
    }
    hr{
        margin-top: 0!important;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">MBTI测试</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <h1 style="text-align: center;padding-top: 30px;font-weight: bold;">MBTI测试</h1>
                    <p style="text-align: center;padding: 10px;font-weight: 10px;color: red">
                        引导语：“我性格内向/外向，适合什么工作？”“哪些职业正好匹配我的性格？”“以我的个性从事什么行业好？”“我性格中的优势和劣势是什么？”“我是不是该继续现在从事的职业？” 不论是正待走进职场的毕业生，还是工作了一段时间的人，面对这类问题都会感到困惑——性格因素和职业选择之间到底存在什么样的关联呢？
                        通过MBTI测试你将会有新的了解。
                    </p>
                    @foreach($questions as $question)
                        <div class="col-sm-12 panel panel-info">
                            <h3>{{$question['name']}}</h3>
                            <hr>
                            @foreach($question['questionList'] as $one )
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="radio-list">
                                            <div class="form-group">
                                                <label class="control-label">{{$one['name']}}</label> <br/>
                                                 @foreach($one['questions'] as $que)
                                                     <input type="radio" name="{{$question['name'] .'-' .$one['name']}}" class="form-control iCheck " value="{{$que['type']}}">{{$que['question']}}
                                                     <br>
                                                 @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="form-actions m-t-40 p-b-20">
                        <div class="row">
                            <div class="col-md-4 col-md-offset-10">
                                <button type="submit" class="btn btn-info test_submit">提交</button>
                                <button type="button" class="btn btn-default"  onclick="window.history.go(-1)">返回</button>
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
        increaseArea: '20%',
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
                var typeArr = [];
                $('.iCheck').each(function(){
                    if(true == $(this).is(':checked')){
                        typeArr.push($(this).val());
                    }
                });
                // if(typeArr.length != 36){
                //     swal("请填写完整36道题！", "", "error");
                // }

                var arr = [];
                typeArr.sort();
                for (var i = 0; i < typeArr.length; i ++) {
                    var count = 0;
                    for (var j = i; j < typeArr.length; j++) {
                       if (typeArr[i] === typeArr[j]) {
                         count++;
                       }
                    }
                    arr.push({
                       type: typeArr[i],
                       count: count
                    })
                    i+=count;
                }
                

                $.ajax({
                    type: "POST",
                    url: "/test/mbti/submit",
                    dataType: "json",
                    data : {
                        data : arr,
                    },
                    success: function(d) {
                        if(d.result){
                            swal(d.message, "", "success");
                            window.location.href = '/test/mbti/report';
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