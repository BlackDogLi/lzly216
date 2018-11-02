@extends('home.layouts.main')

@section('content')
    <div class="ui container segment content">
        <h2 class="ui center aligned header">{{ $articleDetail->title }}</h2>
        <h4 class="ui horizontal divider header"><i class="tag icon"></i></h4>
        <div class="ui description markdown-body" id="{{$articleDetail->id}}">{!! $articleDetail->content !!}</div>
    </div>

    <div class="ui container content" itemprop="keywords">
        @foreach($articleDetail->tags as $tindex => $tag)
            <div class="ui label">{{ $tag->tags_name }}</div>&nbsp;
        @endforeach
    </div>

    <div class="ui container content">
        <div class="ui text content" style="padding: 1.5rem 0;">
            <h4 class="ui top attached block header">留言</h4>
            <div class="ui bottom attached segment">
                <div class="ui minimal comments">
                    @foreach($articleDetail->comments as $cindex => $comment)
                        <div class="comment">
                            <a class="avatar">
                                <img src="/Home/image/{{$comment->id%7}}.jpg"/>
                            </a>
                            <div class="content">
                                <a class="author">{{$comment->nickname}}:</a>
                                <div class="metadata">
                                    <span class="date"></span>
                                </div>
                                <div class="text">
                                    {{$comment->content}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="ui blue submit icon button" style="float: right;">留言</div>
        </div>

        <div class=" ui tiny modal">
            <i class="close icon"></i>
            <div class="header">留言</div>
            <div class="content">
                <form class="ui form">
                    <div class="inline field">
                        <label>姓名:</label>
                        <input type="text" name="username" placeholder="请输入姓名"/>
                    </div>
                    <div class="inline field">
                        <label>邮箱:</label>
                        <input type="text" name="email" placeholder="请输入邮箱"/>
                    </div>
                    <div class="required inline field">
                        <label>留言:</label>
                        <textarea rows="2" name="comment"></textarea>
                    </div>
                    <div class="inline field">
                        <div class="ui checkbox">
                            <input type="checkbox" name="nickstatus"/>
                            <label>开启匿名留言</label>
                        </div>
                    </div>
                    <div class="field">
                        <label style="color:#ff0000;">注:开启匿名之后用户所填写的姓名将不予展示</label>
                    </div>
                    <div class="ui error message">
                    </div>
                </form>
            </div>
            <div class="actions">
                <div class="ui cancel button">取消</div>
                <div class="ui submit button">确定</div>
            </div>
        </div>

        <script src="{{asset('/Home/js/comment.js')}}"></script>
    </div>
@endsection

