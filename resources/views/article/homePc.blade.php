@extends('layouts.outside')
<link href="/css/index.css" rel="stylesheet">

<style type="text/css">
    .image-div{
        width: 100%;
        height: 150px;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        -webkit-transition: all .2s ease-in-out;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 0 40px 2px #e8ecec;
    }
    .image-div:hover{
        height: 180px;
    }
    @-webkit-keyframes tiao_fly {
          0% {
               font-size: 20px;
          }
          50% {
               font-size: 3px;
          }
     }

     @keyframes tiao_fly {
          0% {
               font-size: 20px;
          }
          50% {
               font-size: 3px;
          }
     }
     .highlight-post h2{
        text-shadow:2px 2px mintcream;
     }

     .title {
          animation: tiao_fly 1s 0s alternate infinite;
          -webkit-animation: tiao_fly 1s 0s alternate infinite;
          animation-iteration-count:1;
          -webkit-animation-animation-iteration-count:1;
          color: black;
     }
     .overlay-dark-5:before{
        opacity: 0.1;
     }

</style>
@section('content')

<div class="highlight-post overlay-dark-5" style="background-image:url('{{$banners[0]['images']}}');margin: 0;background-attachment:inherit">
        <div class="container-fluid" style="padding: 0;float: left;top: -150px;position: relative;">
            <div class="intro">
                <h2 class="text-center title">{{$banners[0]['title']}}</h2>
            </div>
        </div>
</div>

<div class="recent-posts" style="padding: 0;margin:0;background-image: url('/images/book.jpg'); background-size: cover;">
        <div class="container-fluid">
            <div class="row headline">
                <div class="col-sm-12 heading">
                    <h1 class="text-center" style="font-weight: 800;">美文精选</h1></div>
            </div>
            <div class="row posts-wrapper col-2">
                @foreach($categories as $category)
                    <section>
                        <h3 style="line-height: 5">{{$category['category_name']}}</h3>
                        <div class="row" style="margin: 0;">
                            @foreach($category->article as $one)
                            <div class="col-sm-6 col-md-3" onclick="openUrl({{$one['id']}})">
                                <div class="blog block text-center">
                                    <div class="image-div" style="background-image: url({{$one['image'] ? $one['image'] : '/images/no_pic.jpg'}});">
                                    </div>
                                    <p style="font-size: 20px;font-weight: bold;margin-top: 15px;">{{$one['title']}}</p>
                                </div>
                            </div>
                            @endforeach
                        </div><!-- / row -->
                    </section>
                @endforeach
                <div class="clearfix"></div>
            </div>
        </div>
</div>
<footer class="site-footer">
        <ul class="list-inline text-center footer-menu">
            <li><a href="#">关于</a></li>
            <li><a href="#">联系我们</a></li>
        </ul>
        <p class="text-center copyright">Powered by Haoran</p>
</footer>

@endsection
@section('extend_js')
<script type="text/javascript">
$('.carousel-inner').carousel('cycle');
function openUrl(id){
   window.location.href = '/article/view/' + id ;
}
</script>
@endsection