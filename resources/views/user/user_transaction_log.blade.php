@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row bg-title" style="border-shadow:1px 0px 20px rgba(0, 0, 0, 0.08)">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">交易详情</h4>
            </div>
        </div>

        {{-- <div class="col-md-3 row m-b-20">
            <div class="white-box" style="margin-bottom: 0px;">
                <h2 class="m-t-0">{{$user->name}} {{$user->level ? $user->levelMap[$user->level] : ''}}</h2>
                <span class="pull-right">
                    <a class="btn btn-primary btn-sm" href="/user/transaction/onlinepay">充值</a>
                </span>
                <span class="font-500 text-danger">USD {{$user->amount}}</span>
            </div>
        </div> --}}

        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="table-responsive">
                        <table class="table table-hover manage-u-table">
                            <thead>
                            <tr>
                                <th>交易类型</th>
                                <th>付款方式</th>
                                <th>金额</th>
                                <th>交易号</th>
                                <th>操作人</th>
                                <th>操作时间</th>
                                <th>付款时间</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transactionLogs as $transactionLog)
                            <tr>
                                <td>{{$transactionLog['actionMap']}}</td>
                                <td>{{$transactionLog['paymentMethodMap']}}</td>
                                <td>USD {{$transactionLog['amount']}}</td>
                                <td>{{$transactionLog['out_trade_no']}}</td>
                                <td>{{$transactionLog['operateUser']['name']}}</td>
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

@endsection