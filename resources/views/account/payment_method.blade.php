@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row bg-title" style="border-shadow:1px 0px 20px rgba(0, 0, 0, 0.08)">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">账户配置</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="float-btn-group">
                    <div class="btn-list">
                        <a class="btn-float blue waves-effect waves-light" href="/account/paymentmethod/create"><i class="fa fa-plus"></i></a>
                     </div>
                    <button class="btn-float btn-triger pink"><i class="icon-bars"></i></button>
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
                                <th>支付别名</th>
                                <th>支付方式</th>
                                <th>币种</th>
                                <th>状态</th>
                                <th width="10%">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($paymentMethods as $paymentMethod)
                            <tr>
                                <td>{{$paymentMethod->name}}</td>
                                <td>{{$paymentMethod->paymentMethodValue}}</td>
                                <td>{{$paymentMethod->currency}}</td>

                                <td>
                                    <input type="checkbox" class="js-switch" data-color="#41b3f9" name="status" {{$paymentMethod->status ? 'checked' : ''}} data-id="{{Hashid::encode($paymentMethod->id)}}"/>
                                    
                                </td>
                                <td><a type="button" class="btn btn-info btn-outline btn-circle btn-sm m-r-5" href="/account/paymentmethod/save?id={{Hashid::encode($paymentMethod->id)}}"><i class="ti-pencil-alt"></i></a></td>
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
<script src="/libs/switchery/dist/switchery.js"></script>
<script type="text/javascript">
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());


        $(this)[0].onchange = function() {
            if ($(this)[0].checked) {
                var status = 1
            } else {
                var status = 0;
            }
            var id = $(this).data('id');

            $.ajax({
                type: "POST",
                url: "/account/paymentmethod/status/save",
                dataType: "json",
                data : {
                    status : status,
                    id : id,
                },
                success: function(d) {
                    if(d.result){
                        toastr.success(d.message);
                    }else{
                        toastr.error(d.message);
                    }
                }
            });
        };


    });

</script>
@endsection