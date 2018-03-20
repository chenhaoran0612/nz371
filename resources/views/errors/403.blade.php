@extends('layouts.outside')

@section('content')
<section class="error-page">
  <div class="error-box">
    <div class="error-body text-center">
      <h1 class="text-info">403</h1>
      <h3 class="text-uppercase">禁止访问</h3>
      <p class="text-muted text-uppercase">由于权限不足，您的访问请求已被禁止。</p>
      <button onclick="history.back()" class="btn btn-info btn-rounded waves-effect waves-light m-b-40">返回上一级</button> </div>
  </div>
</section>
@endsection
