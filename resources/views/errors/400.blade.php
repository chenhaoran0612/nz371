@extends('layouts.outside')
@section('content')
<section id="wrapper" class="error-page">
  <div class="error-box">
    <div class="error-body text-center">
      <h1 class="text-danger">400</h1>
      <h3 class="text-uppercase">页面已飞往月球</h3>
      <p class="text-muted m-t-30 m-b-30">您访问的网址已经在月球着落，请联系管理员找回。</p>
      <button onclick="history.back()" class="btn btn-info btn-rounded waves-effect waves-light m-b-40">返回上一级</button> </div>
  </div>
</section>
@endsection