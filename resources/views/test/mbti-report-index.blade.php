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
            <div class="col-md-12">
                <div class="panel">
                    <div class="table-responsive">
                        <table class="table table-hover manage-u-table" style="font-size: 14px">
                            <thead>
                            <tr>
                                <th>测试人</th>
                                <th>测试结果</th>
                                <th>测试时间</th>
                                
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($mbtiTests as $mbtiTest)
                            <tr>
                                <td>{{$mbtiTest->test_name}}</td>
                                <td>{{$mbtiTest->result}}</td>
                                <td>{{$mbtiTest->created_at}}</td>
                                <td>
                                    <a type="button" href="/test/mbti/report/detail?id={{$mbtiTest->id}}" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="ti-eye"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
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