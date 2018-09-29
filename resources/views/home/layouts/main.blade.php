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
    <title>
        @if(current_is('tags'))
            标签"{{ $tags->tags_name or '' }}" 的文章 -
        @elseif(current_is('article'))
            {{  $articleDetail->title or '' }} -
        @elseif(current_is('articleList'))
            {{ $category->category_name or '' }}分类 -
        @elseif(current_is('ctg'))
            采菊园 -
        @elseif(current_is('atww'))
            一路向西 -
        @endif
            {{ bloginfo('sitename') }}
    </title>
    <meta name="keywords" content="{{ bloginfo('keywords') }}"/>
    <meta name="description" content="{{ bloginfo('description') }}"/>
    <link rel="stylesheet" href="{{ asset('/Home/css/semantic.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/Home/css/markdown.css') }}">
    <link rel="stylesheet" href="{{ asset('/Home/css/style.css') }}" />
    <script src="{{ asset('/Home/js/jquery.js') }}"></script>
    <script src="{{ asset('/Home/js/semantic.min.js') }}"></script>

    @yield('head')
    @if(!empty(bloginfo('google_plus')))
        <link rel="author" href="{{bloginfo('google_plus')}}"/>
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <script>
        //百度统计代码
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?42aefae2e116b17fc588a5a6dc7f74a7";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();

    </script>
</head>
<body>
<div id="app">
    <!-- header -->
    <div id="header" class="ui vertical  masthead center aligned segment">
        <div class="ui borderless container">
            <div class="ui borderless pointing menu">
                <!-- logo -->
                <div class="header item">
                    <img class="logo" src="{{asset('Home/image/logo.png')}}"/>
                </div>
                <!-- nav -->
                <div class="item active"><a href="{{ url('/') }}">首页</a></div>
                @include('home.common.nav')
                @yield('nav')
            </div>
        </div>
    </div>

    <!-- content -->
    <div id="content" class="ui vertical stripe segment">
        <div class="container">
            @yield('content')
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
                    <a href="http://www.miitbeian.gov.cn/">备案号:{{ bloginfo('icp') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    $('.ui .menu .ui.dropdown').dropdown({
        on: 'hover'
    });
</script>
</html>
