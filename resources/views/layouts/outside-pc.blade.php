<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="sharecontent" data-msg-img="http://www.nz371.com/imgs/logo.png" data-msg-title="中牟二高SMART-BRAIN平台" data-msg-content="经典文章，一览无余" data-msg-callBack="" data-line-img="http://www.nz371.com/imgs/logo.png" data-line-title="中牟二高SMART-BRAIN平台" data-line-callBack=""/>
    <title>{{ isset($article->title) ? $article->title : '中牟二高SMART-BRAIN平台' }}</title>
    @yield('extend_css')
    <link href="/css/app-pc.css" rel="stylesheet">
    <script src="/js/jquery.js"></script>
    <script src="/js/bootstrap.js"></script>
    <script src="/js/wow.js"></script>
    <script src="/js/jquery-vegas.js"></script>
    <script src="/js/jquery-easing.js"></script>
    <script src="/js/detectmobilebrowser.js"></script>
    <script src="/js/scrollstop.js"></script>
    <script src="/js/owl-carousel.js"></script>
    <script src="/js/lightbox.js"></script>
    <script src="/js/fitvids.js"></script>
    <script src="/js/initialise-functions.js"></script>
    <script src="/js/functions.js"></script>
    @yield('extend_js')
</head>

	@yield('content')

</html>
