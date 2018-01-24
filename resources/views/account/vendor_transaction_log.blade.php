@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row bg-title" style="border-shadow:1px 0px 20px rgba(0, 0, 0, 0.08)">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">交易详情</h4>
            </div>
            <div class="float-btn-group">
                <div class="btn-list">
                    <a href="#" class="btn-float blue search waves-effect waves-light"><i class="fa fa-search"></i></a>
                 </div>
                <button class="btn-float btn-triger pink"><i class="icon-bars"></i></button>
            </div>
        </div>
        <div class="left-sidebar" style="display: block; ">
            <div class="slimScrollDiv"><div class="slimscrollright">
                    <div class="r-panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12" style="margin-top: 25px;">
                                <form action="" class="searchs">
                                    <div class="form-group col-sm-3">
                                        <label>交易类型</label>
                                        <div class="col-md-12-select">
                                            <select class="selectpicker bs-select-hidden" data-style="btn-info btn-outline" id="action" name="action">
                                                <option value="">请选择交易类型</option>
                                                @foreach ($actionMaps as $key  => $actionMap)
                                                    <option value="{{$key}}">{{$actionMap}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label>账户</label>
                                        <div class="col-md-12-select">
                                            <select class="selectpicker bs-select-hidden" data-style="btn-info btn-outline" id="payment_method_id" name="payment_method_id">
                                                <option value="">请选择账户</option>
                                                @foreach ($paymentMethods as $key  => $paymentMethod)
                                                    <option value="{{Hashid::encode($paymentMethod->id)}}">{{$paymentMethod->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-3">
                                        <label>付款方式</label>
                                        <div class="col-md-12-select">
                                            <select class="selectpicker bs-select-hidden" data-style="btn-info btn-outline" id="payment_method" name="payment_method">
                                                <option value="">请选择付款方式</option>
                                                @foreach ($paymentMethodMaps as $key  => $paymentMethodMap)
                                                    <option value="{{$key}}">{{$paymentMethodMap}}</option>
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
                                <th>交易类型</th>
                                <th>账户</th>
                                <th>付款方式</th>
                                <th>金额</th>
                                <th>交易号</th>
                                {{-- <th>操作人</th> --}}
                                <th>操作时间</th>
                                <th>付款时间</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transactionLogs as $transactionLog)
                            <tr>
                                <td>{{isset($actionMaps[$transactionLog->action]) ? $actionMaps[$transactionLog->action] : $transactionLog->action}}</td>
                                <td>{{isset($transactionLog->paymentMethod->name) ? $transactionLog->paymentMethod->name : ''}}</td>
                                <td>{{ isset($paymentMethodMaps[$transactionLog['payment_method']]) ? $paymentMethodMaps[$transactionLog['payment_method']] : '付款方式不存在' }}</td>
                                <td>{{isset($transactionLog->paymentMethod->currency)? $transactionLog->paymentMethod->currency : ''}} {{$transactionLog['amount']}}</td>
                                <td>{{$transactionLog['out_trade_no']}}</td>
                                {{-- <td>{{$transactionLog['operateUser']['name']}}</td> --}}
                                <td>{{$transactionLog['created_at']}}</td>
                                <td>{{$transactionLog['paid_at']}}</td>
                                <td>{!!$transactionLog['status']? "<span class='text-info'>完成</span>": "<span class='text-danger'>未完成</span>"!!}
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="col-sm-12">{!!$transactionLogs->render()!!}</div>
                        
                            
                    </div>
                </div>
            </div>
        </div>

    </div>
  @endsection
  
@section('extend_js')
<script type="text/javascript">
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
</script>

@endsection