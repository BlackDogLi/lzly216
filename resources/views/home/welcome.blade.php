@extends('home.layouts.main')

@section('content')
    <div class="ui two stackable grid container">
        <div class="row">
            <div class="ten wide column segment">
                <div class="ui text container">
                    <div class="ui items">
                        <div class="item">
                            <div class="content">
                                <a class="header">PHP浮点数的运算</a>
                                <div class="description">
                                    <p>We can give your company superpowers to do things that they never thought possible. Let us delight your customers and empower your needs...through pure data analytics.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach ($article as $item)
                        <div class="ui items">
                            <div class="item">
                                <div class="content">
                                    <a class="header"href="{{ route('article', [$item->flag]) }}">{{$item['title']}}</a>
                                    <div class="description">{!!str_limit($item['markdown'], 300, '... ...')!!}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
                <div class="ui bottom attached segment"></div>
            </div>
        </div>
        <div class="column">
            <div class="ui text content">
                <h4 class="ui top attached block header">Laravel</h4>
                <div class="ui bottom attached segment"></div>
            </div>
        </div>
        <div class="column">
            <div class="ui text content">
                <h4 class="ui top attached block header">Nginx</h4>
                <div class="ui bottom attached segment"></div>
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