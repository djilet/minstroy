<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta charset="utf-8"/>
    
    <title>@yield('title', 'Панель администратора')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="shortcut icon" href="{{ asset('assets/admin') }}/images/favicon.png" type="image/x-icon"/>    <!-- Favicon -->
    <link rel="apple-touch-icon-precomposed" href="{{ asset('assets/admin') }}/images/apple-touch-icon-57-precomposed.png">    <!-- For iPhone -->
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('assets/admin') }}/images/apple-touch-icon-114-precomposed.png">    <!-- For iPhone 4 Retina display -->
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('assets/admin') }}/images/apple-touch-icon-72-precomposed.png">    <!-- For iPad -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('assets/admin') }}/images/apple-touch-icon-144-precomposed.png">    <!-- For iPad Retina display -->

    <!-- CORE CSS FRAMEWORK - START -->
    <link href="{{ asset('assets/admin') }}/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{ asset('assets/admin') }}/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/admin') }}/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/admin') }}/fonts/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/admin') }}/css/animate.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/admin') }}/plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" type="text/css"/>
    <!-- CORE CSS FRAMEWORK - END -->


    <!-- OTHER CSS INCLUDED ON THIS PAGE - START -->
    <link href="{{ asset('assets/admin') }}/plugins/icheck/skins/minimal/_all.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin') }}/plugins/ios-switch/css/switch.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin') }}/plugins/messenger/css/messenger.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin') }}/plugins/messenger/css/messenger-theme-flat.css" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/admin') }}/plugins/image-cropper/css/cropper.css" rel="stylesheet" type="text/css"/>
    <!-- OTHER CSS INCLUDED ON THIS PAGE - END -->

    <!-- CORE CSS TEMPLATE - START -->
    <link href="{{ asset('assets/admin') }}/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/admin') }}/css/responsive.css" rel="stylesheet" type="text/css"/>
    <!-- CORE CSS TEMPLATE - END -->

</head>
<!-- END HEAD -->

<!-- BEGIN BODY -->
<body class=" "><!-- START TOPBAR -->
<div class='page-topbar '>
    <div class='logo-area'>

    </div>
    <div class='quick-area'>
        <div class='pull-left'>
            <ul class="info-menu left-links list-inline list-unstyled">
                <li class="sidebar-toggle-wrap">
                    <a href="#" data-toggle="sidebar" class="sidebar_toggle">
                        <i class="fa fa-bars"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class='pull-right'>
            <ul class="info-menu right-links list-inline list-unstyled">
                <li class="profile">
                    <a href="#" data-toggle="dropdown" class="toggle">
                        @if(Auth::user()->image)
                            <img src="data/profile/profile.png" alt="user-image" class="img-circle img-inline">
                        @endif
                        <span>
                            {{ Auth::user()->name }}
                            <i class="fa fa-angle-down"></i>
                        </span>
                    </a>
                    <ul class="dropdown-menu profile animated fadeIn">
                        <li>
                            <a href="{{ route("admin.profile.edit", Auth::user()->id) }}">
                                <i class="fa fa-user"></i>
                                Profile
                            </a>
                        </li>
                        <li class="last">
                            <a href="{{ route("admin.logout") }}">
                                <i class="fa fa-lock"></i>
                                Logout
                            </a>
                        </li>
                        
                    </ul>
                </li>

                @if(count(editor_locales()) > 1)
                    <li>
                        <form action="{{ route('admin.editor.lang') }}" method="post">
                            <select name="editorLang" class="form-control" id="select-data-language" onchange="$(this).closest('form').submit();">
                                @foreach(editor_locales() as $locale)
                                    <option value="{{ $locale->slug }}"{{ $locale->selected ? ' selected' : '' }}>{{ $locale->title }}</option>
                                @endforeach
                            </select>
                            @csrf
                        </form>
                    </li>
                @endif
                
            </ul>
        </div>
    </div>

</div>
<!-- END TOPBAR -->
<!-- START CONTAINER -->
<div class="page-container row-fluid">

    @auth
        <!-- SIDEBAR - START -->
        <div class="page-sidebar ">
    
    
            <!-- MAIN MENU - START -->
            <div class="page-sidebar-wrapper" id="main-menu-wrapper">
    
                <!-- USER INFO - START -->
                <div class="profile-info row">

                    @if(Auth::user()->image)
                        <div class="profile-image col-md-4 col-sm-4 col-xs-4">
                            <a href="ui-profile.html">
                                <img src="data/profile/profile.png" class="img-responsive img-circle">
                            </a>
                        </div>
                    @endif
    
                    <div class="profile-details col-md-8 col-sm-8 col-xs-8">
    
                        <h3>
                            <a href="{{ route("admin.profile.edit", Auth::user()->id) }}">{{ Auth::user()->name }}</a>
    
                            <!-- Available statuses: online, idle, busy, away and offline -->
                            <span class="profile-status online"></span>
                        </h3>
    
                        <p class="profile-title">@lang('admin/role.'.Auth::user()->role)</p>
    
                    </div>
    
                </div>
                <!-- USER INFO - END -->

                <ul class="wraplist" style="height: auto;">
                    <li{!! request()->routeIs('admin.dashboard') || 
                            request()->routeIs('admin.page.*') ||
                            request()->routeIs('admin.link.*') ? ' class="open"' : ''!!}>
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-sitemap"></i>
                            <span class="title">Структура сайта</span>
                        </a>
                    </li>
                </ul>

                <ul class="wraplist" style="height: auto;">
                    <li{!! request()->routeIs('admin.profile.*') ? ' class="open"' : ''!!}>
                        <a href="{{ route('admin.profile.index') }}">
                            <i class="fa fa-user"></i>
                            <span class="title">Администраторы</span>
                        </a>
                    </li>
                </ul>
    
            </div>
            
        </div>
        <!--  SIDEBAR - END -->
    @endauth
    
    <!-- START CONTENT -->
    <section id="main-content" class=" ">
        <section class="wrapper">

            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <div class="page-title">

                    <div class="pull-left">
                        <h1 class="title">Dashboard</h1>
                    </div>

                </div>
            </div>
            <div class="clearfix"></div>


            <div class="col-lg-12">
                
                @yield('content')
                
            </div>


        </section>
    </section>


</div>
<!-- END CONTAINER -->
@stack('footer.css')

<!-- CORE JS FRAMEWORK - START -->
<script src="{{ asset('assets/admin') }}/js/jquery-1.11.2.min.js" type="text/javascript"></script>
<script src="{{ asset('assets/admin') }}/js/jquery.easing.min.js" type="text/javascript"></script>
<script src="{{ asset('assets/admin') }}/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="{{ asset('assets/admin') }}/plugins/pace/pace.min.js" type="text/javascript"></script>
<script src="{{ asset('assets/admin') }}/plugins/perfect-scrollbar/perfect-scrollbar.min.js" type="text/javascript"></script>
<script src="{{ asset('assets/admin') }}/plugins/viewport/viewportchecker.js" type="text/javascript"></script>
<script src="{{ asset('assets/admin') }}/plugins/messenger/js/messenger.js" type="text/javascript"></script>
<!-- CORE JS FRAMEWORK - END -->

<!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START -->
<script src="{{ asset('assets/admin') }}/plugins/jquery-ui/smoothness/jquery-ui.min.js" type="text/javascript"></script>
<script src="{{ asset('assets/admin') }}/plugins/icheck/icheck.min.js" type="text/javascript"></script>
<!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END -->

<!-- CORE TEMPLATE JS - START -->
<script src="{{ asset('assets/admin') }}/js/scripts.js" type="text/javascript"></script>
<script src="{{ asset('assets/admin') }}/js/admin.js" type="text/javascript"></script>
<!-- END CORE TEMPLATE JS - END -->

@stack('footer.scripts')

</body>
</html>