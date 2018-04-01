@extends('layouts.outside')
<style type="text/css">
    #wrapper{
        padding: 0px;
    }
    .panel-body img{
        max-width: 100%;
    }
    .author{
        float: right;
    }
    .category-title{
        font-size: 22px;
        font-weight: bold;

    }
    .article-author{
        float: right;
        font-size: 8px;
    }
    .article-div{
        padding: 10px;
    }
    .article-title{
        font-size: 8px;
        color: black;
    }
</style>
@section('content')
<div class="col-sm-12" style="background-size: contain;background-position: center;background-repeat: no-repeat;background-image: url('{{$banner['images']}}');height: 200px;" onclick="window.location.href = '{{$banner['link_url']}}'">
    
</div>

@foreach($categories as $category)
    <div class="col-sm-12" style="background-color: {{$category->content_color}}">
        <p class="category-title">{{$category->category_name}}</p>
        <div class="col-sm-12">
            @foreach($category->article as $one)
                @if($one['status'] == 'publish')
                    <div class="col-sm-3 article-div">
                        <a class="article-title" href="/article/view/{{$one['id']}}">{{$one['title']}}</a>
                        <p class="article-author">{{$one['author']}}</p>
                    </div>
                @endif
                

            @endforeach
        </div>
    </div>
@endforeach

@endsection
@section('extend_js')
@endsection