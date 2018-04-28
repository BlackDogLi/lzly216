<!--导航-->
@foreach ($nav as $item)
    <li class="item">
        <a href="{{$item['url']}}">{{$item['name']}}</a>
    </li>
@endforeach