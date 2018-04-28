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
    <link rel="stylesheet" href="{{ asset('/home/css/style.css') }}" />
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
                <li class="item active"><a href="{{ url('/') }}">首页</a></li>
                @include('home.common.nav')
                @yield('nav')
                <li class="item"><a href="#">PHP</a></li>
                <li class="item"><a href="#">Laravel</a></li>
            </div>
        </div>
    </div>

    <!-- content -->
    <div id="content" class="ui vertical stripe segment">
        @yield('content')

        <div class="ui two stackable grid container">
            <div class="row">
                <div class="ten wide column segment">
                    <div class="ui text container">
                        <h2 class="ui header">PHP浮点数的运算</h2>
                        <p>We can give your company superpowers to do things that they never thought possible. Let us delight your customers and empower your needs...through pure data analytics.</p>
                    </div>
                    @foreach ($article as $item)
                        <div class="ui text container">
                            <h2 class="ui header">{{$item['title']}}</h2>
                            {!!str_limit($item['markdown'], 300, '... ...')!!}
                        </div>
                    @endforeach
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
        <!-- AD -->
        <div class="ui alternate vertical segment">
            <div class="ui two center aligned stackable grid container">
                <div class="row">
                    <div class="column">
                        <h1 class="ui header">采菊园</h1>
                    </div>
                    <div class="column">
                        <h1 class="ui header">一路向西</h1>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- footer -->
    <div id="footer" class="ui vertical footer segment">
        <div class="ui container">
            <div class="ui stackable inverted divided equal height stackable grid">
                <div class="three wide column">
                    <h4 class="ui header">关于我</h4>
                    <div class="ui link list">
                        <a href="#" class="item">站点地图</a>
                        <a href="#" class="item">联系我</a>
                    </div>
                </div>
                <div class="three wide column">
                    <h4 class="ui header">关于我</h4>
                    <div class="ui link list">
                        <a href="#" class="item">站点地图</a>
                        <a href="#" class="item">联系我</a>
                    </div>
                </div>
                <div class="seven wide column">
                    <h4 class="ui header">关于我</h4>
                    <p>版权归@山人所有</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>