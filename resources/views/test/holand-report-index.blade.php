@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row bg-title" style="border-shadow:1px 0px 20px rgba(0, 0, 0, 0.08)">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">霍兰德测试报告列表</h4>
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
                                <th>测试时间</th>
                                <th>报告状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($holandTests as $holandTest)
                            <tr>
                                <td>{{$holandTest->test_name}}</td>
                                <td>{{$holandTest->created_at}}</td>
                                <td>{{$holandTest->statusValue}}</td>
                                <td>
                                    @if($holandTest->status == 'success')
                                        <a type="button" href="/test/holand/report/detail?id={{$holandTest->id}}" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="ti-eye"></i></a>
                                    @endif
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