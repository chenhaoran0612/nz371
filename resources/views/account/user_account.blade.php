@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row bg-title" style="border-shadow:1px 0px 20px rgba(0, 0, 0, 0.08)">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">账户列表</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="float-btn-group">
                    <div class="btn-list">
                        <a href="#" class="btn-float blue search waves-effect waves-light"><i class="fa fa-search"></i></a>
                    </div>
                    <button class="btn-float btn-triger pink"><i class="icon-bars"></i></button>
                </div>
            </div>
        </div>
        <div class="left-sidebar" style="display: block; ">
            <div class="slimScrollDiv"><div class="slimscrollright">
                    <div class="r-panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12" style="margin-top: 25px;">
                                <form action="" class="searchs">
                                    <div class="form-group col-sm-3">
                                        <label >用户编码</label>
                                        <input type="text" class="form-control" id="user_code" name="user_code">
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label >用户名</label>
                                        <input type="text" class="form-control" id="name" name='name'>
                                    </div>

                                    <div class="form-group col-sm-3">
                                        <label>用户类型</label>
                                        <div class="col-md-12-select">
                                            <select class="selectpicker bs-select-hidden" data-style="btn-info btn-outline" id="level" name="role">
                                                <option value="">请选择用户类型</option>
                                                <option value="distributor">经销商</option>
                                                <option value="vendor">供应商</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-3">
                                        <label>账户</label>
                                        <div class="col-md-12-select">
                                            <select class="selectpicker bs-select-hidden" data-style="btn-info btn-outline" id="level" name="payment_method_id">
                                                <option value="">请选择账户</option>
                                                @foreach ($paymentMethods as $paymentMethod)
                                                    <option value="{{Hashid::encode($paymentMethod->id)}}">{{$paymentMethod->name}}</option>
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
                                <th>用户编码</th>
                                <th>用户名</th>
                                <th>类型</th>
                                <th>账户</th>
                                <th>状态</th>
                                <th>金额</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($userAccounts as $userAccount)
                            <tr>
                                <td>{{$userAccount->user->user_code}}</td>
                                <td><span>{{$userAccount->user->name}}</span></td>
                                <td>{{$userAccount->user->getUserType()}}</td>
                                <td>{{$userAccount->getPaymentMethod->name}}</td>
                                <td><span class="{{$userAccount->user->status ? '' : 'text-danger'}}">{{$userAccount->user->status ? '开启' : '关闭'}}</span></td>
                                <td>{{$userAccount->getPaymentMethod->currency}} {{$userAccount->amount}}</td>
                                <td>
                                    <a type="button" href="#" class="btn btn-info btn-outline btn-circle btn-sm m-r-5 edit" data-id="{{Hashid::encode($userAccount->id)}}" data-amount="{{$userAccount->amount}}" data-currency="{{$userAccount->getPaymentMethod->currency}}"><i class="ti-pencil-alt"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="col-sm-12">{!!$userAccounts->render()!!}</div>
                        
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- 批量上传模态框（Modal） -->
    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="uploadLabel" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
                    <h3 class="modal-title" style="text-align: center;" id="uploadLabel">用户金额修改</h3>
                </div>

                <div class="modal-body" style="overflow: hidden;">
                    <div class="col-sm-12">
                        <label class="control-label col-md-3">交易类型 <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <select name="action" class="form-control">
                                    @foreach ($actionMap as $key => $map)
                                        <option value="{{$key}}">{{$map}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <label class="control-label col-md-3">结算方式 <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <select name="payment_method" class="form-control">
                                    <option value="">请选择</option>
                                    <option value="alipay">国际支付宝</option>
                                    <option value="bank_transfer">银行转账</option>
                                    <option value="other">其他</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <label class="control-label col-md-3">交易号 <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <div class="form-group">
                            <input type="text" class="form-control" name="out_trade_no">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <label class="control-label col-md-3">金额 <span class="text-danger">*</span></label>
                        <div class=" col-md-9">
                            <div class="form-group input-group">
                                <div class="input-group-addon"></div>
                                <input type="text" class="form-control" name="amount" onchange="clearNoNum(this)" placeholder="充值金额">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <label class="control-label col-md-3">支付密码 <span class="text-danger">*</span></label>
                        <div class=" col-md-9">
                            <div class="form-group">
                                <input type="password" class="form-control" name="payment_password" placeholder="支付密码">
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">备注</label>
                            <div class="col-md-9">
                                <textarea name="memo" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="hidden" name="id">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary edit-amount">提交</button>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
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

    $('.edit').click(function(){
        // $('#edit [name=amount]').val($(this).data('amount'));
        $('#edit [name=id]').val($(this).data('id'));
        $('.input-group-addon').html($(this).data('currency'));
        $('#edit').modal('show');
    })

    $('.edit-amount').click(function(){
        var id = $('#edit [name=id]').val();
        var amount = $('#edit [name=amount]').val();
        var action = $('#edit [name=action]').val();
        var payment_method = $('#edit [name=payment_method]').val();

        var payment_password =$('#edit [name=payment_password]').val(); 
        var out_trade_no = $('#edit [name=out_trade_no]').val();
        var memo = $('#edit [name=memo]').val();


        $.ajax({
            type: "get",
            url: "/account/amount/edit",
            dataType: "json",
            data :{
                id : id,
                amount : amount,
                action : action,
                payment_method : payment_method,
                out_trade_no : out_trade_no,
                memo : memo,
                payment_password : payment_password,
            },
            success: function(d) {
                if(d.result){
                    swal(d.message, "", "success");
                    window.location.reload();
                }else{
                    if(typeof(d.message) == 'string'){
                        swal(d.message, "", "error");
                    }else{
                        $.each(d.message, function(key,value){
                            $.each(value, function(k,v){
                                swal(v, "", "error");
                            })
                        })
                    }
                    
                }
            }
        });
    })


    $('.delete').click(function () {

        var id = $(this).data('id')
        swal({
          dangerMode: true,
          title: "确定要删除吗?",
          type: "warning",
          icon: "warning",
          buttons: ["取消","确认"],
        })
        .then(willDelete => {
            if (willDelete) {
                $.ajax({
                    type: "post",
                    url: "/user/distributor/delete",
                    dataType: "json",
                    data :{
                        id : id,
                    },
                    success: function(r) {
                        if(r.result){
                            swal(r.message, "", "success");
                            window.location.reload();
                        }else{
                            swal(r.message, "", "error");
                        }
                    }
                });
            }
        });
    });

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