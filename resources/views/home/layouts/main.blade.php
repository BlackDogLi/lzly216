<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-dns-prefetch-control" content="on"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="address=no"/>
    <meta name="format-detection" content="email=no"/>
    <title>@if(current_is('tags'))标签"{{ $tags->tags_name or '' }}" 的文章 - @elseif(current_is('post')){{ $post->title or '' }} - @elseif(current_is('category'))分类 "{{ $category->category_name or '' }}" 的文章 - @endif {{ bloginfo('site_name') }}</title>
    <meta name="keywords" content="{{ bloginfo('keywords') }}"/>
    <meta name="description" content="{{ bloginfo('description') }}"/>
    <link rel="stylesheet" href="{{ asset('/home/css/semantic.min.css') }}" />
    @yield('head')
    @if(!empty(bloginfo('google_plus')))
        <link rel="author" href="{{bloginfo('google_plus')}}"/>
    @endif
    <script src="{{ asset('/home/js/semantic.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
</head>
<body>
<div id="app">
    <!-- header -->
    <div id="header" class="ui vertical  masthead center aligned segment">
        <div class="ui borderless container">
            <div class="ui borderless pointing menu">
                <!-- logo -->
                <div class="header item">
                    <img class="logo" src="{{asset('home/image/logo.png')}}"/>
                </div>
                <!-- nav -->
                <a class="item active">首页</a>
                <a class="item">PHP</a>
                <a class="item">Laravel</a>
            </div>
        </div>
    </div>

    <!-- content -->
    <div id="content" class="ui vertical stripe segment">
        <div class="ui two stackable grid container">
            <div class="row">
                <div class="ten wide column segment">
                    <div class="ui text container">
                        <h2 class="ui header">PHP浮点数的运算</h2>
                        <p>We can give your company superpowers to do things that they never thought possible. Let us delight your customers and empower your needs...through pure data analytics.</p>
                    </div>
                </div>
                <div class="six wide column segment">
                    <div class="ui text content">
                        <h4 class="ui top attached block header">最近发表</h4>
                        <div class="ui bottom attached segment">
                            <ul>
                                <li><a>PHP浮点数的运算</a></li>
                                <li><a>PHP精密计算函数</a></li>
                            </ul>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <div id="content"></div>
</div>
</body>
</html>