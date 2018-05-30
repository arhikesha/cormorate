@foreach($items as $item)
    <li {{ (URL::current() == $item->url) ? "class = active" : '' }}><!--
     подстветка активного меню URL -Фасад URL::current() - текущая страница
     -->
        <a href="{{ $item->url() }}">{{$item->title}}</a>
        @if($item->hasChildren())
            <ul class="sub-menu">
                @include(env('THEME').'.customMenuItems',['items'=>$item->children()])
            </ul>
        @endif
    </li>

@endforeach