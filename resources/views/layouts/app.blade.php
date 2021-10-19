<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('meta_title', config('app.name', 'MeteorCMS'))</title>
    <meta name="keywords" content="@yield('meta_keywords')">
    <meta name="description" content="@yield('meta_description')">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'MeteorCMS') }}
                    </a>
                    
                </div>

                <div class="right">
                    <select name="lang">
                        @foreach(locales() as $locale)
                            <option value="{{ $locale->slug }}"{{ $locale->selected ? ' selected' : '' }}>{{ $locale->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </nav>
        
        <div class="row">
            
            <div class="col-md-2">
                <h2>Main меню</h2>
                <ul>
                    @foreach(pages('main') as $mp)
                        <li>
                            <a href="{{ url($mp->url) }}"{{ $mp->targetAttr() }}>
                                {{ $mp->title }}
                            </a>
                            
                            @if(count($mp->children) > 0)
                                <ul>
                                    @foreach($mp->children as $mpp)
                                        <li>
                                            <a href="{{ url($mpp->url) }}">
                                                {{ $mpp->title }}
                                            </a>
                                            
                                            @if(count($mpp->children) > 0)
                                                <ul>
                                                    @foreach($mpp->children as $mppp)
                                                        <li>
                                                            <a href="{{ url($mppp->url) }}">
                                                                {{ $mppp->title }}
                                                            </a>

                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                            
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            
                        </li>
                    @endforeach
                </ul>

                <br>
                <h2>Bottom меню</h2>
                <ul>
                    @foreach(pages('bottom') as $mp)
                        <li>
                            <a href="{{ url($mp->url) }}">
                                {{ $mp->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <section id="content" class="col-md-10">
                @yield('content')
            </section>
            
        </div>

    </div>

    <!-- Scripts -->
    {{--<script src="{{ asset('js/app.js') }}"></script>--}}
</body>
</html>
