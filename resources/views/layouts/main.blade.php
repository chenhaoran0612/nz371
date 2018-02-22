<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>中牟第二高级中学-职业规划系统</title>
    @yield('extend_css')
    <link href="/css/app.css" rel="stylesheet">
</head>
<body class="fix-header">
<div id="wrapper">
    @include('common.nav')
    @include('common.topbar')
    <div id="page-wrapper">
        @yield('content')
        @include('common.footer')
    </div>
    
</div>
<script src="/js/app.js"></script>
<script src="/libs/pace/pace.min.js"></script>
@yield('extend_js')
</body>
</html>
