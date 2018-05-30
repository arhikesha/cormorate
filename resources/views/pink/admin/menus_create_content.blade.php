<div id="content-page" class='content group'>
    <div class="hentry group">
        {!! Form::open(['url'=>(isset($menu->id)) ? route('admin.menus.update',['menus'=>$menu->id]) : route('admin.menus.store'),'class'=>'contact-form','method'=>'POST','enctype'=>'multipart/form-data']) !!}

                <ul>

                    <li class = "text-field">
                        <label for="name-contact-us">
                            <span class="label">Загловок</span>
                            <br/>
                            <span class="sublabel">Заголовок пункта</span><br />
                        </label>
                        <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                            {!! Form::text('title',isset($menu->title) ? $menu->title : old('title'),['placeholder'=>'Введите название меню']) !!}
                        </div>
                    </li>

                    <li class = "text-field">
                        <label for="name-contact-us">
                            <span class="label">Родительский пунк меню</span>
                            <br/>
                            <span class="sublabel">Родитель</span><br />
                        </label>
                        <div class="input-prepend">
                            {!! Form::select('parent',$menus,isset($menu->parent) ? $menu->parent :null) !!}
                        </div>
                    </li>


                </ul>

        <h2>Тип меню</h2>

            <div id="accordion">

                <h3>{!! Form::radio('type','customLink',(isset($type) && $type == 'customLink' ) ? true : false,['class'=>'radioMenu'] ) !!}
                    <span class="label">Пользовательская ссылка:</span>
                </h3>

                <ul>

                    <li class = "text-field">
                        <label for="name-contact-us">
                            <span class="label">Путь до ссылки:</span>
                            <br/>
                            <span class="sublabel">Путь до ссылки:</span><br />
                        </label>
                        <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                            {!! Form::text('custom_link',(isset($menu->path)   && $type == 'customLink') ? $menu->path : null ) !!}
                        </div>
                    </li>
                    <div style="clear:both;"></div>
                </ul>




                    <h3>{!! Form::radio('type','blogLink',(isset($type) && $type == 'blogLink' ) ? true : false ,['class'=>'radioMenu'] ) !!}
                        <span class="label">Раздел блог:</span>
                    </h3>

                <ul>

                    <li class = "text-field">
                        <label for="name-contact-us">
                            <span class="label">Ссылка на категорию блога</span>
                            <br/>
                            <span class="sublabel">Ссылка на категорию блога</span><br />
                        </label>
                        <div class="input-prepend">
                            {!! Form::select('category_alias',$categories,(isset($option) && $option) ? $option:false)  !!}
                        </div>
                    </li>

                    <li class = "text-field">
                        <label for="name-contact-us">
                            <span class="label">Ссылка на материал блога</span>
                            <br/>
                            <span class="sublabel">Ссылка на материал блога</span><br />
                        </label>
                        <div class="input-prepend">
                            {!! Form::select('articles_alias',$article,(isset($option) && $option) ? $option:false,['placeholder'=>'Не используется'] )!!}<!--1-name,2-value и текст,3-условие --->
                        </div>
                    </li>

                    <div style="clear:both;"></div>
                </ul>



                    <h3>{!! Form::radio('type','portfolioLink',(isset($type) && $type == 'portfolioLink' ) ? true : false,['class'=>'radioMenu'] ) !!}
                        <span class="label">Раздел портфолио:</span>
                    </h3>

                <ul>

                    <li class = "text-field">
                        <label for="name-contact-us">
                            <span class="label">Ссылка на запись портфолио</span>
                            <br/>
                            <span class="sublabel">Ссылка на запись портфолио</span><br />
                        </label>
                        <div class="input-prepend">
                            {!! Form::select('portfolio_alias',$portfolio,(isset($option) && $option) ? $option:false,['placeholder'=>'Не используется'] ) !!}

                        </div>
                    </li>

                    <li class = "text-field">
                        <label for="name-contact-us">
                            <span class="label">Портфолио</span>
                            <br/>
                            <span class="sublabel">Портфолио</span><br />
                        </label>
                        <div class="input-prepend">
                            {!! Form::select('filter_alias',$filters,(isset($option) && $option) ? $option:false) !!}
                        </div>
                    </li>


                </ul>

            </div>

        <br />

        @if(isset($menu->id))
            <input type="hidden" name="_method" value="PUT">

        @endif

        <ul>
            <li class="submit-button">
                {!! Form::button('Сохранить',['class'=>'btn btn-green','type'=>'submit']) !!}
            </li>
        </ul>

        {!! Form::close() !!}


     </div>
</div>

<script>

    jQuery(function ($) {

        $('#accordion').accordion({
            activate:function (e, obj) {
                 obj.newPanel.prev().find('input[type=radio]').attr('checked','checked');
            }
        });
        var active = 0;
        $('#accordion input[type=radio]').each(function (ind,it) {

            if($(this).prop('checked')){
                active = ind;
            }
        });

        $('#accordion').accordion('option','active', active);
    })

</script>