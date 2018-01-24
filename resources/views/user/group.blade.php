@extends('layouts.main')

@section('extend_css')
<style href="/libs/icheck/skins/square/blue.css" rel="stylesheet" type="text/css"></style>

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row bg-title" style="margin-bottom: 1px;">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">用户组管理</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <a class="btn-info btn-circle pull-right m-l-20 add-group"><i class="fa fa-plus text-white"></i></a>
            </div>
        </div>

        <div class="row">
            <div class="panel row">
                <div class="table-responsive">

                    <div class="vtabs col-sm-12 row">
                        <ul class="nav tabs-vertical m-t-20">
                            @foreach ($groups as $key => $group)
                            <li class="tab {{$key? '' : 'active'}}">
                                <a data-toggle="tab" href="#home{{$key}}" aria-expanded="{{$key? 'false' : 'true'}}">
                                    <span class="hidden-xs">{{$group->name}}
                                        <span class=" pull-right">
                                            <span class="fa fa-edit edit" data-id="{{Hashid::encode($group->id)}}" data-name="{{$group->name}}"></span>
                                            <span class="fa fa-trash delete" data-id="{{Hashid::encode($group->id)}}"></span>
                                        </span>
                                    </span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        
                        <div class="tab-content">

                        @foreach ($groups as $key => $group)
                            <div id="home{{$key}}" class="tab-pane {{$key?'':'active'}} panel panel-default permission row">

                                <div class="panel-heading" style="padding-top: 0px;">
                                    <h4 class="panel-title">{{$group->name}}权限分配</h4>
                                </div>
                                <div class="panel-wrapper collapse in row">

                                    <div class="panel-body">
                                        <div class="form-group col-sm-12">
                                            <input type="checkbox" name="check-all" class="iCheck"> 全选
                                        </div>
                                        
                                        @foreach ($normal_permission as $key => $permission_list)
                                        <div class="form-group col-sm-12">
                                        <h3 class=" text-left">{{$key}}</h3>
                                        <div class="m-t-20">
                                            @foreach ($permission_list as $keys => $element)
                                            <div class="m-b-15 pull-left" style="width: 150px;" ><input type="checkbox" name="permission[]" class="iCheck" id='{{$keys}}' value="{{$keys}}" {{in_array($keys, $group->userPermission)?'checked':''}}> <span>{{$element}}</span> </div>
                                            @endforeach
                                        </div>
                                        </div>
                                        @endforeach
                                        
                                    </div>
                                    <input type="hidden" name="editor_id" value="" id="editor_id">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary push_user_permission once-click" data-id="{{Hashid::encode($group->id)}}">提交</button>
                                </div>

                            </div>
                        @endforeach

                        </div>
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


    $('.iCheck').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%',
    });


    var checkAll;
    var checkboxes;
    $("input[name=check-all]").on('ifClicked', function(event) {
        var checkboxes = $(this).closest('.permission').find("input[name='permission[]']");
        if (checkboxes.filter(':checked').length == checkboxes.length) {
            checkboxes.iCheck('uncheck');
        } else {
            checkboxes.iCheck('check');
        }
    });

    $("input[name='permission[]']").on('ifUnchecked ifChecked', function(event){
        checkAll = $(this).closest('.permission').find("input[name=check-all]");
        checkboxes = $(this).closest('.permission').find("input[name='permission[]']");
        if(checkboxes.filter(':checked').length == checkboxes.length) {
            checkAll.iCheck('check');
        } else {
            checkAll.iCheck('uncheck');
        }
    });


    $('.permission').each(function(k,v){
        checkAll = $(v).find("input[name=check-all]");
        checkboxes = $(v).find("input[name='permission[]']");
        if(checkboxes.filter(':checked').length == checkboxes.length) {
            checkAll.iCheck('check');
        } else {
            checkAll.iCheck('uncheck');
        }
    })


    $('.add-group').click(function(){
        swal("新增用户组", {
            content: "input",
            buttons: ["取消","确认"],
        })
        .then((value) => {
            if (value) {
                $.ajax({
                    type: "post",
                    url: "/user/group/create",
                    dataType: "json",
                    data :{
                        name : value,
                    },
                    success: function(r) {
                        console.log(r);
                        if(r.result){
                            swal(r.message, "", "success");
                            window.location.reload();
                        }else{
                            swal(r.message['name'][0], "", "error");
                        }
                    }
                });
            }
        });
    })

    $('.edit').click(function(){
        var id = $(this).data('id');
        var name = $(this).data('name');
        swal("编辑用户组", {
            content: {
                element: "input",
                attributes: {
                    type: "text",
                    value: name,
                },
            },
            buttons: ["取消","确认"],
        })
        .then((value) => {
            if (value) {
                $.ajax({
                    type: "post",
                    url: "/user/group/edit",
                    dataType: "json",
                    data :{
                        id : id,
                        name : value,
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
    })


    //用户权限提交
    $(".push_user_permission").click(
        function (){
            var permission = '';
            var id = $(this).data('id');
            $(this).closest('.permission').find("input[name='permission[]']").each(function(){
                if(true == $(this).is(':checked')){
                    permission += $(this).val() + ',';
                }
            });
            $.ajax({
                type : "POST",
                url : "/user/group/permission/save",
                dataType : "json",
                data : {
                    id : id,
                    permission : permission,
                },
                success : function(d) {
                   if(d.result){
                        toastr.success(d.message);
                        setTimeout(window.location.reload(),1000);
                    } else {
                        toastr.warning('d.message');
                    }
                }
            })
        }
    );


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
                    url: "/user/group/delete",
                    dataType: "json",
                    data :{
                        id : id,
                    },
                    success: function(r) {
                        if(r.result){
                            swal(r.message, "", "success");
                            // swal("成功", r.message, "success");
                            window.location.reload();
                        }else{
                            swal(r.message, "", "error");
                            // swal("失败", r.message, "error");
                        }
                    }
                });
              }
        });
    });

</script>
@endsection