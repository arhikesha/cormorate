<div id="content-page" class='content group'>
    <div class="hentry group">
          <h3 class="title_page">Пользователи</h3>

        <div class="short-table white">

            <table style="width:100%" cellpadding="0" cellspacing="0">
                <thead>

                <th>Name</th>
                <th>Ling</th>
                <th>Удалить</th>

                </thead>

                @if($menu)

                    @include(env('THEME').'.admin.custom-menu-items',array('items'=>$menu->roots(),'paddingLeft'=>''))

                @endif

            </table>
        </div>
        {!! Html::link(route('admin.menus.create'),'Добавить пунтк',['class'=>'btn btn-green','type'=>'submit']) !!}


    </div>
</div>

