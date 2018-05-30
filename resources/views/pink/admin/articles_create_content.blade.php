
    <div id="content-page" class="content group">
        <div class="hentry group">
            {!! Form::open(['url'=>(isset($article->id)) ? route('admin.articles.update',['articles'=>$article->alias]) : route('admin.articles.store'),'class'=>'contact-form','method'=>'POST','enctype'=>'multipart/form-data'])  !!}
           <ul>
               <li class = "text-field">
                   <label for="name-contact-us">
                       <span class="label">Название</span>
                       <br/>
                       <span class="sublabel">Заголовок материала</span><br />
                   </label>
                   <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                       {!! Form::text('title',isset($article->title) ? $article->title : old('title'),['placeholder'=>'Введите название страницы']) !!}
                   </div>
               </li>

               <li class = "text-field">
                   <label for="name-contact-us">
                       <span class="label">Ключевые слова</span>
                       <br/>
                       <span class="sublabel">Заголовок материала</span><br />
                   </label>
                   <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                       {!! Form::text('keywords',isset($article->keywords) ? $article->keywords : old('keywords'),['placeholder'=>'Введите ключевые слова']) !!}
                   </div>
               </li>

               <li class = "text-field">
                   <label for="name-contact-us">
                       <span class="label">Мета описание</span>
                       <br/>
                       <span class="sublabel">Заголовок материала</span><br />
                   </label>
                   <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                       {!! Form::text('meta_desc',isset($article->meta_desc) ? $article->meta_desc : old('meta_desc'),['placeholder'=>'Введите ключевые слова']) !!}
                   </div>
               </li>

               <li class = "text-field">
                   <label for="name-contact-us">
                       <span class="label">Псевдоним</span>
                       <br/>
                       <span class="sublabel">Введите всевдоним</span><br />
                   </label>
                   <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                       {!! Form::text('alias',isset($article->alias) ? $article->alias : old('alias'),['placeholder'=>'Введите псевдоним']) !!}
                   </div>
               </li>

               <li class = "textarea-field">
                   <label for="message-contact-us">
                       <span class="label">Краткое описание</span>
                   </label>
                   <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                       {!! Form::textarea('desc',isset($article->desc) ? $article->desc : old('desc'),['placeholder'=>'Краткое описание','id'=>'editor']) !!}
                   </div>
                   <div class="msg-error"></div>
               </li>

               <li class = "textarea-field">
                   <label for="message-contact-us">
                       <span class="label">Описание</span>
                   </label>
                   <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                       {!! Form::textarea('text',isset($article->text) ? $article->text : old('text'),['placeholder'=>'Введите описанние','id'=>'editor2']) !!}
                   </div>
                   <div class="msg-error"></div>
               </li>

               @if(isset($article->img->path))

                   <li class="textarea-field">
                       <label>
                           <span class="label">Изображение материала</span>
                       </label>

                       {!! Html::image(asset(env("THEME")).'/images/articles/'.$article->img->mini) !!}
                       {!! Form::hidden('old_image',$article->img->path) !!}

                   </li>

               @endif

               <li class = "text-field">
                   <label for="name-contact-us">
                       <span class="label">Изображение</span>
                       <br />
                       <span class="label">Изображение материала</span>
                   </label>
                   <div class="input-prepend">
                       {!! Form::file('image',['class'=>'filestyle','data-button']) !!}
                   </div>
                   <div class="msg-error"></div>
               </li>

               <li class = "text-field">
                   <label for="name-contact-us">
                       <span class="label">Категория</span>
                       <br/>
                       <span class="sublabel">Категория материала</span><br />
                   </label>
                   <div class="input-prepend"><span class="add-on"></span>
                       {!! Form::select('category_id',$categories,isset($article->category_id) ? $article->category_id :'') !!}
                   </div>
               </li>

               @if(isset($article->id))
                   <!-- если это редактирование а не создание нового материла method = "PUT" -используется для редактирования
                   ,это скрытое поле, МЕТОL PUT НЕЛЬЗЯ ИСПОЛЬЗОВАТЬ В ФАЙРФОКСЕ!!!
                   --->
                   <input type="hidden" name="_method" value="PUT">

               @endif

               <li class="submit-button">
                   {!! Form::button('Сохранить',['class'=>'btn btn-green','type'=>'submit']) !!}
               </li>

           </ul>

      {!! Form::close() !!}
            <script>
                CKEDITOR.replace('editor');
                CKEDITOR.replace('editor2');
            </script>


        </div>
    </div>

















