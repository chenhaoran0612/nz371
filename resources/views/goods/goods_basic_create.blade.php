@extends('layouts.main')

@section('extend_css')

@endsection

@section('content')
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">新增商品</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">
                            <div class="form-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">标题 <span class="text-danger">*</span></label>
                                            <input type="text" id="title" name="title" class="form-control" >
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>价格 <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-addon">{{$user->getPaymentMethod->currency}}</div>
                                                <input type="text" class="form-control" id="price" name="price" onchange="clearNoNum(this)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">长 <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" id="length" name="length" class="form-control">
                                                <div class="input-group-addon">cm</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">宽 <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" id="width" name="width" class="form-control">
                                                <div class="input-group-addon">cm</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">高 <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" id="height" name="height" class="form-control">
                                                <div class="input-group-addon">cm</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">重量 <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" id="weight" name="weight" class="form-control">
                                                <div class="input-group-addon">g</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Barcode</label>
                                            <input type="text" id="barcode" name="barcode" class="form-control">
                                        </div>
                                    </div>
                                
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">新旧程度</label>
                                            <select class="form-control" tabindex="1" name="condition">
                                                @foreach ($newMap as $key => $map)
                                                <option value="{{$key}}">{{$map}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12 ">
                                        <div class="form-group">
                                            <label class="control-label">商品详情</label>
                                            <textarea class="form-control" rows="6" name="description"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="tab-pane row goodsImageParent">
                                                <div class="col-md-2">
                                                    <label class="control-label">商品主图</label>
                                                    <hr>
                                                    <div class="main-image" style="border: 1px solid #c0c0c0;">
                                                        <img src="/imgs/no_pic.jpg" alt="暂无设置" width="100%">
                                                        <input type="hidden" name="main_image" class="main_image">

                                                    </div>
                                                </div>
                                                <div class="col-md-10">
                                                    <label class="control-label">商品图片</label>
                                                    <hr>
                                                    <div class="goods-images" >
                                                        @include('form.goods_images')
                                                    </div>
                                                    <input type="hidden" name="images" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 m-t-20">
                                        <div class="table-responsive">
                                            <label class="control-label">商品属性 <a href="javascript:void(0)" onclick="addAttribute()"><li class="fa fa-plus"> </li></a></label>
                                            <table class="table table-bordered td-padding">
                                                <tbody class="attribute_tbody">
                                                    {{-- <tr>
                                                        <td>
                                                            <input type="text" name="attribute_name" class="form-control" placeholder="属性名">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="attribute_value" class="form-control" placeholder="属性值">
                                                        </td>
                                                        <td>
                                                            <a  href="javascript:void(0)" onclick="delTr(this)"><i class="fa fa-trash-o fa-lg p-t-10"> </i></a>
                                                        </td>
                                                    </tr> --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                                <hr>
                            </div>
                            <div class="form-actions m-t-40">
                                {{-- <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> 保存</button>
                                <button type="button" class="btn btn-default">返回</button> --}}
                                <div class="row">
                                    <div class="col-md-4 col-md-offset-10">
                                        <button type="submit" class="btn btn-info goods_submit">提交</button>
                                        <button type="button" class="btn btn-default"  onclick="window.history.go(-1)">返回</button>
                                    </div>
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
    <script src="/js/uploadImage.js"></script>
    <script type="text/javascript">
        //初始化图片
        var goodsImageUrl = '/goods/images';
        var parentClassName = 'goods-images';
        initUploadImage(parentClassName, goodsImageUrl, 180, 180);

        //设置主图
        $('body').on('click', '.btn-setmainimg', function(){
            var image = $(this).parents('.template-download').find('.preview').find('a').attr('href');
            var main_image = $($(this).closest('.goodsImageParent') ).find('.main-image');
            main_image.find('img').attr('src', image);
            main_image.find('.main_image').val(image);
        })


        $('.goods_submit').click(function(){
            var title = $('[name=title]').val();
            var price = $('[name=price]').val();
            var length = $('[name=length]').val();
            var width = $('[name=width]').val();
            var height = $('[name=height]').val();
            var weight = $('[name=weight]').val();
            var barcode = $('[name=barcode]').val();
            var condition = $('[name=condition]').val();
            var description = $('[name=description]').val();
            // var memo = $('[name=memo]').val();
            var images = getUploadImage('goods-images');
            var main_image = $('[name=main_image]').val();

            var attribute = {};
            $('.attribute_tbody tr').each(function(k, v){
                attribute[$(v).find('.attribute_name').val()] = $(v).find('.attribute_value').val();
            });
            attribute = JSON.stringify(attribute);

            $.ajax({
                type: "POST",
                url: "/goods/basic/create",
                dataType: "json",
                data : {
                    title : title,
                    price : price,
                    length : length,
                    width : width,
                    height : height,
                    weight : weight,
                    barcode : barcode,
                    condition : condition,
                    description : description,
                    // memo : memo,
                    images : images,
                    main_image : main_image,
                    attribute : attribute,
                },
                success: function(d) {
                    if(d.result){
                        // toastr.success(d.message);
                        swal(d.message, "", "success");
                        window.location.href = '/goods/basic'
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

        function delTr(obj)
        {
            $(obj).closest("tr").remove();
        }
        
        function addAttribute()
        {
            $('.attribute_tbody').append('<tr><td><input type="text" class="form-control attribute_name" placeholder="属性名"></td><td><input type="text" class="form-control attribute_value" placeholder="属性值"></td><td><a href="javascript:void(0)" onclick="delTr(this)"><i class="fa fa-trash-o fa-lg p-t-10"> </i></a></td></tr>');
        }


    </script>
@endsection