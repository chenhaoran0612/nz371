@extends('layouts.outside')
<style type="text/css">
    body{
        background: {{$article->getBackgroundColorAttribue()}}!important
    }
</style>
@section('content')
<div class="col-sm-12">
    <div style="padding: 10px;">
        <h3>{{$article->title}}</h3>
        <small>作者:{{$article->author}}</small>
        <br>
        <small>发布时间:{{$article->created_at}}</small>
        <hr>
        <div class="inline-editor note-air-editor note-editable panel-body">
            <div class="content">
                {!!$article->content!!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('extend_js')
@endsection