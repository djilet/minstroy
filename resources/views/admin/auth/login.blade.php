<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>

    <title>@lang('admin/app.title')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('assets/admin') }}/images/favicon.png" type="image/x-icon" />
    <link rel="apple-touch-icon-precomposed" href="{{ asset('assets/admin') }}/images/apple-touch-icon-57-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('assets/admin') }}/images/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('assets/admin') }}/images/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('assets/admin') }}/images/apple-touch-icon-144-precomposed.png">

    <link href="{{ asset('assets/admin') }}/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/admin') }}/css/responsive.css" rel="stylesheet" type="text/css"/>

</head>

<body class=" login_page">

<div class="login-wrapper">
    <div id="login" class="login loginpage col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-2 col-xs-8">
        <h1><a href="{{ route('admin.login') }}" tabindex="-1">FokCMS</a></h1>

        <form action="{{ route('admin.login') }}" method="post" autocomplete="off">
            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <p>
                <label for="email">Логин<br />
                <input type="text" name="email" id="email" class="input" value="{{ old('email') }}" size="20" /></label>
            </p>
            <p>
                <label for="password">Пароль<br />
                    <input type="password" name="password" id="password" class="input" value="" size="20" /></label>
            </p>

            <p class="forgetmenot">
                <label class="icheck-label form-label" for="remember">
                    <input name="remember" type="checkbox" id="remember" class="skin-square-orange"  {{ old('remember') ? 'checked' : 'checked' }}>
                    Запомнить меня
                </label>
            </p>
            <p class="submit">
                <input type="submit" id="wp-submit" class="btn btn-orange btn-block" value="Войти" />
            </p>
            @csrf
        </form>

        <p id="nav">
            <a class="pull-left" href="forgot_password.php">Забыли пароль?</a>
        </p>

    </div>
</div>

<link href="{{ asset('assets/admin') }}/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="{{ asset('assets/admin') }}/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/admin') }}/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/admin') }}/fonts/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/admin') }}/css/animate.min.css" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/admin') }}/plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/admin') }}/plugins/icheck/skins/square/orange.css" rel="stylesheet" type="text/css" media="screen"/>

<script src="{{ asset('assets/admin') }}/js/jquery-1.11.2.min.js" type="text/javascript"></script>
<script src="{{ asset('assets/admin') }}/js/jquery.easing.min.js" type="text/javascript"></script>
<script src="{{ asset('assets/admin') }}/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="{{ asset('assets/admin') }}/plugins/pace/pace.min.js" type="text/javascript"></script>
<script src="{{ asset('assets/admin') }}/plugins/perfect-scrollbar/perfect-scrollbar.min.js" type="text/javascript"></script>
<script src="{{ asset('assets/admin') }}/plugins/viewport/viewportchecker.js" type="text/javascript"></script>
<script src="{{ asset('assets/admin') }}/plugins/icheck/icheck.min.js" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END -->
<script src="{{ asset('assets/admin') }}/js/scripts.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#Login').focus();
    });
</script>
</body>
</html>