@extends('layouts.outside')
<style type="text/css">
    #wrapper{
        padding: 0px;
    }
    .panel-body img{
        max-width: 100%;
    }
    .article-div{
        width: 100%;
    }
    .title{
        font-size: 14px;
    }
    .article-author{
        float: left;
        font-size: 8px;
    }
    .article-title{
        font-size: 14px;
        font-weight: 500;
        color: black;
    }
    .carousel-inner>.item{
        transition: transform .6s ease-in-out;
        transform:translate3d(0,0,0);
        backface-visibility:hidden;
        height: 240px;
    }
    .news-slide{
        height: 240px;
    }
    .overlaybgs{
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        width: 100%;
        height: 240px;
    }
    .news-contents{
        position: absolute;
        z-index: 10;
        width: 100%;
        top: 0;
        padding: 30px;
        font-weight: bold;
    }
    .content-div{
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
        width: 100%;
        height: 80px;
    }
    .category-div{
        width: 100%;
        height: 200px;
        padding: 0 10px;
        margin-bottom: 10px;
    }
    .content-container{
        width: 33%;
        height: 70px;
        float: left;
    }
    p{
        margin-bottom: 4px!important;
    }
    .image-div{
        width: 40px;
        height: 40px;
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
    }
</style>
<style type="text/css">
    
</style>
@section('content')

<div class="col-lg-12 col-sm-12 col-xs-12" style="padding: 0">
    <div class="news-slide">
        <div class="vcarousel slide">
            <div class="carousel-inner">
                @foreach($banners as $one)
                <div class="item @if(isset($banners[0]) && $banners[0]['id'] == $one['id']) active @endif" >
                    <a href="{{$one['link_url']}}">
                        <div class="overlaybgs" style="background-image: url({{$one['images']}});">
                        </div>
                        <div class="news-contents">
                            <h2 style="font-weight: bold;">{{$one['title']}}</h2>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12 col-sm-12 col-xs-12" style="padding: 0 ; clear: both;">
    <div class="vtabs">
        <ul class="nav tabs-vertical">
            @foreach($categories as $category)
                <li class="tab @if ($categories[0]['id'] == $category['id']) active @endif">
                    <a style="background: {{$category->content_color}};padding: 15px;margin-bottom: 0;width: 90px;" data-toggle="tab" href="#category-{{$category['id']}}" aria-expanded="false"> 
                        <span class="visible-xs">{{$category['category_name']}}</span> 
                    </a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content" style="padding: 0;width: 100%;overflow: hidden;">
            @foreach($categories as $category)
                <div id="category-{{$category['id']}}" class="tab-pane">
                    <div class="table">
                        <table class="table">
                            <tbody>
                                @foreach($category->article as $one)
                                    <tr style="font-size: 8px;">
                                        <td><div style="background-image: url({{$one['image']}});" class="image-div"></div>
                                        </td>
                                        <td>{{$one['title']}}</td>
                                        <td style="width: 90px">{{ substr($one['created_at'] , 0 ,10)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{--<div style="clear: both;">
@foreach($categories as $category)
    <div class="category-div" >
        <p class="title" style="border-bottom: 1px solid {{$category->content_color}};">{{$category->category_name}}</p>
            
            @foreach($category->article as $one)
                <div class="article-div" >
                    <a href="/article/view/{{$one['id']}}">
                        <div class="content-container">
                            <div class="content-div" style="background-image: url({{$one['image'] ? $one['image'] :'/images/no_pic.jpg'}});">
                            </div>
                            <p class="article-title" >{{$one['title']}}</p>
                            <p class="article-author">{{$one['author']}}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        
    </div>
@endforeach
</div>
--}}
@endsection
@section('extend_js')
<script type="text/javascript">
 $('.carousel-inner').carousel('cycle');
 window.onload  = function (){
    $('#category-{{$categories[0]["id"]}}').addClass('active');
 }
</script>
@endsection