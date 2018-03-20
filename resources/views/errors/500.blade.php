@extends('layouts.outside')
@section('content')
<section id="wrapper" class="error-page">
  <div class="error-box">
    <div class="error-body text-center">
      <h1>500</h1>
      <h3 class="text-uppercase">服务器异常</h3>
      <p class="text-muted m-t-30 m-b-30">服务器出现异常，请及时联系管理员（chenhaoran@lanxion.com）或稍后访问。</p>
      <button onclick="history.back()" class="btn btn-info btn-rounded waves-effect waves-light m-b-40">返回上一级</button> </div>
  </div>
</section>
@endsection