@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row bg-title" style="border-shadow:1px 0px 20px rgba(0, 0, 0, 0.08)">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">账户列表</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="table-responsive">
                        <table class="table table-hover manage-u-table">
                            <thead>
                            <tr>
                                <th>账户</th>
                                <th>金额</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($userAccounts as $userAccount)
                            <tr>
                                <td>{{$userAccount->getPaymentMethod->name}}</td>
                                <td>{{$userAccount->getPaymentMethod->currency}} {{$userAccount->amount}}</td>
                                @if (Auth::user()->role == 'vendor')
                                    <td><a href="/vendor/transaction/log?user_account_id={{Hashid::encode($userAccount->getPaymentMethod->id)}}">详情</a></td>
                                @elseif(Auth::user()->role == 'distributor')
                                    <td><a href="/distributor/transaction/log?user_account_id={{Hashid::encode($userAccount->getPaymentMethod->id)}}">详情</a></td>
                                @endif
                                
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
        console.log($(this).data('amount'));
        $('#edit [name=amount]').val($(this).data('amount'));
        $('#edit [name=id]').val($(this).data('id'));
        $('#edit').modal('show');
    })

    $('.edit-amount').click(function(){
        var amount = $('#edit [name=amount]').val();
        var id = $('#edit [name=id]').val();

        $.ajax({
            type: "get",
            url: "/account/amount/edit",
            dataType: "json",
            data :{
                id : id,
                amount : amount,
            },
            success: function(d) {
                if(d.result){
                    swal(d.message, "", "success");
                    window.location.reload();
                }else{
                    $.each(d.message, function(key,value){
                        $.each(value, function(k,v){
                            swal(v, "", "error");
                        })
                    })
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

</script>
@endsection