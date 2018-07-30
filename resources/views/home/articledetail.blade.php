@extends('home.layouts.main')

@section('content')
    <div class="ui container segment content">
        <h2 class="ui center aligned header">{{ $articleDetail->title }}</h2>
        <h4 class="ui horizontal divider header"><i class="tag icon"></i></h4>
        <div class="ui description markdown-body">{!! $articleDetail->content !!}</div>
    </div>

    <div class="ui container" itemprop="keywords">
        @foreach($articleDetail->tags as $tindex => $tag)
            <div class="ui label">{{ $tag->tags_name }}</div>&nbsp;
        @endforeach
    </div>
@endsection