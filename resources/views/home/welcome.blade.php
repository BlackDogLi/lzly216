@extends('home.layouts.main')

@section('content')
    <div class="ui two stackable grid container">
        <div class="row">
            <div class="ten wide column segment">
                <div class="ui text container">
                    @foreach ($article as $item)
                        <div class="ui items">
                            <div class="item">
                                <div class="content">
                                    <a class="header"href="{{ route('article', [$item->flag]) }}">
                                        <i class="pointing right icon"></i>
                                        {{$item['title']}}
                                    </a>
                                    <div class="ui inverted divider"></div>
                                    <div class="description">{{str_limit($item['markdown'],300,'...')}}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="six wide column segment" style="padding: 0 4rem;">
                <div class="ui text content">
                    <h4 class="ui top attached block header">热点文章</h4>
                    <div class="ui bottom attached segment">
                        <div class="ui items">
                            @foreach ($hotArticle as $item)
                                <div class="item">
                                    <a href="{{ route('article', [$item->flag]) }}">{{ $item['title'] }}</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 采俱园 -->
    <div class="ui alternate vertical segment">
        <div class="ui center aligned stackable grid container">
            <div class="row">
                <div class="column">
                    <h1 class="ui header">采菊园</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="ui three column vertical stackable grid container segment">
        <div class="column">
            <div class="ui text content">
                <h4 class="ui top attached block header">PHP</h4>
                <div class="ui bottom attached segment">
                    <div class="ui items">
                        @foreach($phpArticle as $item)
                            <div class="item"><a href="{{ route('article', [$item->flag]) }}" >{{ $item->title }}</a></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="ui text content">
                <h4 class="ui top attached block header">SERVER</h4>
                <div class="ui bottom attached segment">
                    <div class="ui items">
                        @foreach($serverArticle as $item)
                            <div class="item"><a href="{{ route('article', [$item->flag]) }}" >{{ $item['title'] }}</a></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="ui text content">
                <h4 class="ui top attached block header">DATA</h4>
                <div class="ui bottom attached segment">
                    <div class="ui items">
                        @foreach($dataArticle as $item)
                            <div class="item"><a href="{{ route('article', [$item->flag]) }}" >{{ $item['title'] }}</a></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 一路向西 -->
    <div class="ui borderless alternate vertical segment">
        <div class="ui center aligned stackable grid container">
            <div class="row">
                <div class="column">
                    <h1 class="ui header">一路向西</h1>
                </div>
            </div>
        </div>
    </div>
@endsection