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
    <div id="header">
        <!-- logo -->
        <div id="logo"></div>
        <!-- nav -->
        <nav class="ui secondary  pointing menu">
            <a class="item active">首页</a>
            <a class="item">PHP</a>
            <a class="item">Laravel</a>
        </nav>
    </div>

    <!-- content -->
    <div id="content"></div>

    <!-- footer -->
    <div id="content"></div>
</div>
</body>
</html>