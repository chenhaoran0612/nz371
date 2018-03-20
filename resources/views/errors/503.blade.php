@extends('layouts.outside')
@section('content')
<section id="wrapper" class="error-page">
  <div class="error-box">
    <div class="error-body text-center">
      <h1 class="text-warning">503</h1>
      <h3 class="text-uppercase">服务器升级中</h3>
      <p class="text-muted m-t-30 m-b-30">请稍后重试。</p>
      <button onclick="history.back()" class="btn btn-info btn-rounded waves-effect waves-light m-b-40">返回上一级</button> </div>
  </div>
</section>
@endsection