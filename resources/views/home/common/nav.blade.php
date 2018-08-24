<!--导航-->
@foreach ($nav as $item)
    @if(isset($item['children']) && !empty($item['children']))
        <div class="ui dropdown item">
            <a href="{{$item['url']}}">{{$item['name']}}</a>
            <i class="dropdown icon"></i>
            <div class="menu">
                @foreach($item['children'] as $value)
                    @if (isset($value['children']) && !empty($value['children']))
                        <div class="item">
                            <i class="dropdown icon"></i>
                            <a href="{{route('articleList',[$value->id])}}">{{$value['category_name']}}</a>
                            <div class="menu transition hidden">
                                @foreach($value['children'] as $val)
                                <div class="item">
                                    <a href="{{route('articleList',[$val->id])}}">{{$val['category_name']}}</a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="item">
                            <a href="{{route('articleList',[$value->id])}}">{{$value['category_name']}}</a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @else
        <div class="item">
            <a href="{{$item['url']}}">{{$item['name']}}</a>
        </div>
    @endif
@endforeach
