@extends('home.layouts.main')

@section('content')
    <div class="ui container content">
        <div class="ui styled fluid accordion">
            @foreach ($articleList as $item)
                <div class="title">
                    <i class="dropdown icon"></i>{{$item['title']}}
                </div>
                <div class="content">
                    <div class="transition hidden markdown-body">{!! $item['content'] !!}</div>
                </div>
            @endforeach
        </div>
    </div>
    <script>
        $('.ui.container .accordion').accordion({selector: {trigger: '.title .icon'}});
    </script>
@endsection
