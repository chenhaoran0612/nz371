<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ isset($article->title) ? $article->title : '中牟二高职业规划平台' }}</title>
    @yield('extend_css')
    <link href="/css/app.css" rel="stylesheet">
</head>
<body class="fix-header">
<div id="wrapper">
    @yield('content')
</div>
<script src="/js/app.js"></script>
<script src="/libs/pace/pace.min.js"></script>
@yield('extend_js')
</body>
</html>
