@if($menu)

    <!--   {/!! $menu->asUl() !!} - только надо
    !! - это значить что будут html теги
     $menu->asUl() - это метод скачаненого Menu
     $menu->asOl() - вывод нумеровогного меню
     -->
    <div class = "menu classic">
        <ul id="nav" class="menu">
            @include(env('THEME').'.customMenuItems',['items'=>$menu->roots()])
            <!--Если подключаешь \include То данные также сразу будут видны в файле котором подключаешь
            данный файл это customMenuItems
            -->
         </ul>
    </div>
@endif




