@extends('layouts.main')
@section('extend_css')
@endsection
@section('content')
<div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">系统基础信息</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">
                                <div class="form-body form-horizontal" id="user_form">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">认证手机 <span class="text-danger">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" readonly name="verify_phone" value="{{$infos->phone}}">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-1">
                                                <button class="btn btn-warning change-phone" >更换手机号</button>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">支付密码 <span class="text-danger">*</span></label>
                                                    <div class="col-md-8">
                                                        <input type="password" name="password" class="form-control" value="{{$infos->payment_password}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button class="btn btn-warning change-password">修改支付密码</button>
                                            </div>
                                        </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 修改手机号 -->
<div class="modal fade" id="change-phone" tabindex="-1" role="dialog" aria-labelledby="phoneLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
                <h3 class="modal-title" style="text-align: center;">修改认证手机</h3>
            </div>
            <div class="modal-body" style="overflow: hidden;">
                <div style="padding: 30px 0 ;">
                    <div class="col-sm-12">
                        <label class="control-label col-md-3">原手机号</label>
                        <div class="col-md-6">
                            <div class="form-group">
                            <input type="text" class="form-control" readonly value="{{$infos->phone}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input type="button"  class="btn btn-info verify-code" value="获取验证码"></input>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="control-label col-md-3">验证码<span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <input type="text" class="form-control" id="verify_code">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="control-label col-md-3">新手机号<span class="text-danger">*</span></label>
                        <div class=" col-md-9">
                            <div class="form-group input-group">
                                <div class="input-group-addon">+086</div>
                                <input type="text" class="form-control" name="text" placeholder="新手机号" id="new_phone_number">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary change-phone-submit">提交</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>


<!-- 修改支付密码 -->
<div class="modal fade" id="change-password" tabindex="-1" role="dialog" aria-labelledby="phoneLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
                <h3 class="modal-title" style="text-align: center;">修改支付密码</h3>
            </div>
            <div class="modal-body" style="overflow: hidden;">
                <div style="padding: 30px 0 ;">
                    <div class="col-sm-12">
                        <label class="control-label col-md-3">手机号</label>
                        <div class="col-md-6">
                            <div class="form-group">
                            <input type="text" class="form-control" readonly value="{{$infos->phone}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input type="button"  class="btn btn-info verify-code" value="获取验证码"></input>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="control-label col-md-3">验证码<span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <input type="text" class="form-control" id="verify_code_password">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="control-label col-md-3">新支付密码<span class="text-danger">*</span></label>
                        <div class=" col-md-9">
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="至少为6位" id="new_password">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary change-password-submit">提交</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>

@endsection
  
@section('extend_js')
    <script type="text/javascript">
        $('.change-phone').click(function(){
            $('#change-phone').modal('show');
        });

        $('.change-password').click(function(){
            $('#change-password').modal('show');
        });


        $('.change-phone-submit').click(function(){
            var newPhoneNumber = $('#new_phone_number').val();
            var verifyCode = $('#verify_code').val();
            $.ajax({
                type: "GET",
                url: "/system/change-phone",
                dataType: "json",
                data : {
                    'verify_code' : verifyCode,
                    'new_phone_number' : newPhoneNumber
                },
                success: function(d) {
                    if(d.result){
                        toastr.success(d.message);
                        clearTimeout();
                        window.location.reload();
                    }else{
                        toastr.warning(d.message);
                    }
                }
            });
        });

        $('.change-password-submit').click(function(){
            var newPassword = $('#new_password').val();
            var verifyCode = $('#verify_code_password').val();
            $.ajax({
                type: "GET",
                url: "/system/change-payment-password",
                dataType: "json",
                data : {
                    'verify_code' : verifyCode,
                    'new_password' : newPassword
                },
                success: function(d) {
                    if(d.result){
                        toastr.success(d.message);
                        clearTimeout();
                        window.location.reload();
                    }else{
                        toastr.warning(d.message);
                    }
                }
            });
        });

        

        $('.verify-code').click(function(){
            settime(this);
            $.ajax({
                type: "GET",
                url: "/system/verify-code",
                dataType: "json",
                data : {},
                success: function(d) {
                    if(d.result){
                        toastr.success(d.message);
                    }else{
                        toastr.warning(d.message);
                    }
                }
            });
        });
        var countdown=60; 
        function settime(val) { 
            if (countdown == 0) { 
                val.removeAttribute("disabled");    
                val.value = "获取验证码"; 
                countdown = 60; 
                clearTimeout();
            } else { 
                val.setAttribute("disabled", true); 
                val.value = "重新发送(" + countdown + ")"; 
                countdown--; 
                setTimeout(function() { 
                    settime(val) 
                },1000);
            }  
        } 

    </script>
@endsection