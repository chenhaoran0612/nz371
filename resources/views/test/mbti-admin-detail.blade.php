@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row bg-title" style="border-shadow:1px 0px 20px rgba(0, 0, 0, 0.08)">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">MBTI测试报告</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="panel">
                    <div class="table-responsive">
                        <h3>测试报告解读</h3>
                        <p style="padding: 10px">测试结果 ： {{$mbtiTest->result}}</p>

                        <p style="padding: 10px">性格特征 ： {{$mbtiTest->feature_data}}</p>

                        <p style="padding: 10px">推荐职业 ： {{$mbtiTest->job_data}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
  @endsection
  
@section('extend_js')
<script type="text/javascript">

    
</script>
@endsection