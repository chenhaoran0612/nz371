<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>中牟二高网站管理平台</title>
    <link href="/libs/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/libs/animate/animate.min.css" rel="stylesheet">
    @yield('extend_css')
    <link href="/css/app.css" rel="stylesheet">
    <style>
        .register-pannel-title{
            text-align: center;
            color: white;
            text-shadow: 2px 3px 4px rgba(0, 0, 0, 0.8);
        }
        .m-t{
            text-align: center;
            color: white;
            text-shadow: 2px 3px 4px rgba(0, 0, 0, 0.8);
        }
    </style>
</head>
<body class="bing-bg">
<div class="container animated fadeInDown">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 register-pannel">
            <div class="register-pannel-title" ><h1 class="logo-name">中牟二高网站管理平台</h1></div>

            <div class="panel panel-default register-pannel-default">
                <div class="panel-heading">注册</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">账户名称</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">邮箱地址(登录名)</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">密码</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">确认密码</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    注册
                                </button>
                                <a href="/login" class="btn btn-primary">
                                    返回
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <p>&nbsp;</p>
            <p class="m-t"> <small>FANSHI &copy; 2017</small> </p>
        </div>

    </div>
</div>
<script src="/js/app.js"></script>
</body>

</html>
