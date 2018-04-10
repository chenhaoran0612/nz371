@extends('layouts.main')

@section('extend_css')
    <link href="/libs/custom-select/custom-select.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">新增子账户</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">

                                <div class="form-body form-horizontal" id="user_form">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Email <span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="email">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">用户名 <span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="name">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">昵称 <span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="nick_name">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">密码 <span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <input type="password" name="password" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">确认密码 <span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <input type="password" name="comfirm_password" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">状态 <span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" checked class="js-switch" data-color="#41b3f9" name="status" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">用户组</label>
                                                <div class="col-md-9">
                                                    <select class="select2 m-b-10 select2-multiple" multiple="multiple" data-placeholder="请选择所属用户组" name="group_id">
                                                    @foreach ($userGroups as $userGroup)
                                                        <option value="{{Hashid::encode($userGroup->id)}}">{{$userGroup->name}}</option>
                                                    @endforeach
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                        
                                     
                                    
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">联系人</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="contacts">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">电话</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="phone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">地址</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="address">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="control-label col-md-2">LOGO</label>
                                            <div class="col-md-10 user-logo">
                                                @include('form.upload-image-common')
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="control-label col-md-2">备注</label>
                                            <div class="col-md-10">
                                                <textarea class="form-control" name="memo" rows="5"></textarea> 
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-4 col-md-offset-10">
                                                    <button type="submit" class="btn btn-info user_submit">提交</button>
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
    <script type="text/javascript" src="/libs/custom-select/custom-select.min.js"></script>
    <script src="/js/uploadImage.js"></script>
    <script type="text/javascript">
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
        });

        $(".select2").select2();

        //初始化图片
        var goodsImageUrl = '/user/logo';
        var parentClassName = 'user-logo';
        initUploadImage(parentClassName, goodsImageUrl, 180, 180);

        $('.user_submit').click(function(){
            var email = $('#user_form [name=email]').val();
            var name = $('#user_form [name=name]').val();
            var password = $('#user_form [name=password]').val();
            var comfirm_password = $('#user_form [name=comfirm_password]').val();
            var status = document.querySelector('#user_form [name=status]').checked;
            
            var contacts = $('#user_form [name=contacts]').val();
            var phone = $('#user_form [name=phone]').val();
            var address = $('#user_form [name=address]').val();
            var memo = $('#user_form [name=memo]').val();
            var logo = getUploadImage('user-logo');
            var group_id = $('#user_form [name=group_id]').val();
            var nick_name = $('#user_form [name=nick_name]').val();

            $.ajax({
                type: "POST",
                url: "/user/subaccount/create",
                dataType: "json",
                data : {
                    email : email,
                    name : name,
                    password :password,
                    comfirm_password : comfirm_password,
                    status : status,
                    contacts : contacts,
                    phone : phone,
                    memo : memo,
                    logo : logo,
                    address : address,
                    group_id : group_id,
                    nick_name : nick_name,
                },
                success: function(d) {
                    if(d.result){
                        toastr.success(d.message);
                        window.location.href = '/user/subaccount'
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

    </script>
@endsection