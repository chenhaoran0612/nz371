@extends('layouts.main')

@section('extend_css')

@endsection

@section('content')
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">新增账户配置</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">

                                <div class="form-body form-horizontal" id="paymentmethod_form">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">支付别名  <span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">支付方式  <span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <select name="method" class="form-control">
                                                        <option value="">请选择支付方式</option>
                                                    @foreach ($paymentMethodMap as $key => $paymentmethod)
                                                        <option value="{{$key}}">{{$paymentmethod}}</option>
                                                    @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        
                                        <div class="insert-table">
                                            
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">币种</label>
                                                <div class="col-md-9">
                                                    <select name="currency" class="selectpicker bs-select-hidden" data-style="btn-info btn-outline">
                                                        @foreach ($currencyMap as $currency)
                                                            <option value="{{$currency}}">{{$currency}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-4 col-md-offset-10">
                                                    <button type="submit" class="btn btn-info paymentmethod_submit">提交</button>
                                                    <button type="button" class="btn btn-default"  onclick="window.history.go(-1)">返回</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> </div>
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
    <script src="/libs/switchery/dist/switchery.min.js"></script>
    <script src="/js/uploadImage.js"></script>
    <script type="text/javascript">
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
        });

        $('.paymentmethod_submit').click(function(){
            var data = {};
            data['config'] = {};
            $('.parame').each(function(k,v){
                data['config'][$(this).data('name')] = $(this).val();
            });
            data['name'] = $('[name=name]').val();
            data['method'] = $('[name=method]').val();
            data['currency'] = $('[name=currency]').val();

            $.ajax({
                type: "POST",
                url: "/account/paymentmethod/create",
                dataType: "json",
                data : data,
                success: function(d) {
                    if(d.result){
                        toastr.success(d.message);
                        window.location.href = '/account/paymentmethod'
                    }else{
                        $('.help-block').remove();
                        $.each(d.message, function(key,value){
                            $.each(value, function(k,v){
                                $('[name='+key+']').closest('div').append('<span class="help-block text-danger">'+v+'</span>');
                            });
                        })
                    }
                }
            });
        })

        $('[name=method]').change(function(){
            var payment_method = $(this).val();
            $.ajax({
                type: "get",
                url: "/get/paymentmethod",
                dataType: "json",
                data : {
                    payment_method : payment_method,
                },
                success: function(d) {
                    var html ='';
                    $('.insert-table').empty();
                    $.each(d, function(k, v){
                        html += '<div class="col-md-6"><div class="form-group"><label class="control-label col-md-3">'+v+'</label><div class="col-md-9"><input type="text" class="form-control parame" data-name="'+k+'"></div></div></div>';
                    });
                    $('.insert-table').append(html);
                }
            });
        });

    </script>
@endsection