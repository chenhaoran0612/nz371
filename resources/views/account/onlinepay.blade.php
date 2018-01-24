@extends('layouts.main')

@section('extend_css')
<link href="/libs/icheck/skins/square/blue.css" rel="stylesheet">
<style type="text/css">
    img{ border:1px solid #ccc; }
    .payment{width: 280px; float: left; margin-left: 30px;}
    .payment-left{width: 30px;float: left; display: block; margin-top: 5px; margin-right: 5px;}
    .payment-right{width: 250px; float: left; display: block; text-align: left; border: 1px solid #e3e3e3; height: 40px; padding:2px;}
    .payment-right label{ float: right; line-height: 35px; color:#3097D1; margin-right: 10px;}
</style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">账户充值</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">
                            <div class="form-body form-horizontal" id="user_form">
                                <div class="row">
                                
                                <form action="/user/recharge" method="post" target="_blank" onsubmit="return check()">
                                    <div class="col-md-6">
                                        <label class="col-md-3">充值金额</label>
                                        <div class="input-group com-md-9">
                                            {{-- <div class="input-group-addon">USD</div> --}}
                                            <input type="text" class="form-control" name="amount" onchange="clearNoNum(this)" placeholder="充值金额">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-12 m-t-15">
                                        <label class="col-md-3">支付方式</label>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr class="m-t-15">
                                    
                                    <div class="col-md-12 text-right">

                                        @foreach ($payments as $payment)
                                        <div class="payment">
                                            <div class="form-group">
                                                <div class="payment-left">
                                                    <input type="radio" class="iCheck" name="id" value="{{Hashid::encode($payment['id'])}}" checked>
                                                </div>
                                                <div class="payment-right">
                                                    <img src="/imgs/{{$payment['method']}}.png" width="100" >
                                                    <label>{{$payment->name}}</label>
                                                </div>
                                                
                                                
                                            </div>
                                        </div>
                                        @endforeach

                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-offset-11">
                                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                                <button type="submit" class="btn btn-primary">提交</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

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
<script src="/libs/icheck/icheck.min.js"></script>

<script type="text/javascript">
    $('.iCheck').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%',
    });

    @if (Session::get('result') === false)
        toastr.error('{{ session('message') }}');
    @elseif(Session::get('result') === true)
        toastr.success('{{ session('message') }}');
    @endif

    function check()
    {
        var amount = $('[name=amount]').val();
        if (!amount || amount == 0) {
            toastr.error('金额不能为空');
            return false;
        }
        return true;
    }

    function clearNoNum(obj) {
        obj.value = obj.value.replace(/[^\d.]/g,""); //清除"数字"和"."以外的字符  
        obj.value = obj.value.replace(/^\./g,""); //验证第一个字符是数字而不是  
        obj.value = obj.value.replace(/\.{2,}/g,"."); //只保留第一个. 清除多余的  
        obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");  
        obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3'); //只能输入两个小数
        if (obj.value) {
            $(obj).val(parseFloat(obj.value).toFixed(2));
        } else {
            $(obj).val('0.00');
        }
    }
</script>
@endsection