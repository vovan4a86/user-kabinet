@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="{{ route('admin.catalog') }}"><i class="fa fa-list"></i> Каталог</a></li>
        @foreach($catalog->getParents(false, true) as $parent)
            <li><a href="{{ route('admin.catalog.products', [$parent->id]) }}">{{ $parent->name }}</a></li>
        @endforeach
        <li class="active">{{ $catalog->id ? $catalog->name : 'Новый раздел' }}</li>

    </ol>
@stop
@section('page_name')
    <h1>Каталог
        <small>{{ $catalog->id ? $catalog->name : 'Новый раздел' }}</small>
    </h1>
@stop

<form action="{{ route('admin.catalog.catalogSave') }}" onsubmit="return catalogSave(this, event)">
    <input type="hidden" name="id" value="{{ $catalog->id }}">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">Параметры</a></li>
            <li><a href="#tab_2" data-toggle="tab">Тексты</a></li>
            @if($catalog->id)
                <li class="pull-right">
                    <a href="{{ $catalog->url }}" target="_blank">Посмотреть</a>
                </li>
            @endif
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">

                {!! Form::groupText('name', $catalog->name, 'Название') !!}
                {!! Form::groupText('h1', $catalog->h1, 'H1') !!}

                @if($catalog->parent_id == 5)
                    {!! Form::groupText('h1_add', $catalog->h1_add, 'Перевод в скобках') !!}
                @endif

                {!! Form::groupSelect('parent_id', ['0' => '---корень---'] + $catalogs->pluck('name', 'id')->all(),
                    $catalog->parent_id, 'Родительский раздел') !!}
                {!! Form::groupText('alias', $catalog->alias, 'Alias') !!}
                {!! Form::groupText('title', $catalog->title, 'Title') !!}
                {!! Form::groupText('keywords', $catalog->keywords, 'keywords') !!}
                {!! Form::groupText('description', $catalog->description, 'description') !!}
                {!! Form::groupText('og_title', $catalog->og_title, 'OpenGraph title') !!}
                {!! Form::groupText('og_description', $catalog->og_description, 'OpenGraph description') !!}

                <div class="form-group" style="display: flex; column-gap: 30px;">
                    <div>
                        <label for="article-image">Изображение раздела (253x160)</label>
                        <input id="article-image" type="file" name="image" value=""
                               onchange="return newsImageAttache(this, event)">
                        <div id="article-image-block">
                            @if ($catalog->image)
                                <img class="img-polaroid"
                                     src="{{ $catalog->image_src }}" height="100"
                                     data-image="{{ $catalog->image_src }}"
                                     onclick="return popupImage($(this).data('image'))" alt="">
                                <a class="images_del" href="{{ route('admin.catalog.catalogImageDel', [$catalog->id]) }}"
                                   onclick="return catalogImageDel(this)">
                                    <span class="glyphicon glyphicon-trash text-red"></span>
                                </a>
                            @else
                                <p class="text-yellow">Изображение не загружено.</p>
                            @endif
                        </div>
                    </div>
                </div>
                {!! Form::hidden('published', 0) !!}
                {!! Form::groupCheckbox('published', 1, $catalog->published, 'Показывать раздел') !!}

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <span class="box-title">Шаблон автооптимизации для товаров (см. Настройки)</span>
                    </div>
                    <div class="box-body">
                        {!! Form::groupText('product_title_template', $catalog->product_title_template, 'Шаблон title') !!}
                        <div class="small">Шаблон title по умолчанию</div>
                        <div class="small">{{ $catalog->getDefaultTitleTemplate() }}</div>
                        {!! Form::groupText('product_description_template', $catalog->product_description_template, 'Шаблон description') !!}
                        <div class="small">Шаблон description по умолчанию</div>
                        <div class="small">{{ $catalog->getDefaultDescriptionTemplate() }}</div>

                        {!! Form::groupRichtext('product_text_template', $catalog->product_text_template, 'Шаблон текста') !!}
                    </div>
                    <div class="box-footer">
                        Коды замены:
                        <ul>
                            <li>{name} - название товара</li>
                            <li>{h1} - H1 товара</li>
                            <li>{lower_name} - название товара в нижнем регистре</li>
                            <li>{article} - поле товара - Артикул</li>
                            <li>{price} - поле товара - Цена</li>
                            <li>{old_price} - поле товара - Старая цена</li>
                            <li>{is_discount} - поле товара - Скидка</li>
                            <li>{is_new} - поле товара - Новинка</li>
                            <li>{is_hit} - поле товара - Хит</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="tab_2">
                {!! Form::groupRichtext('announce', $catalog->announce, 'Анонс', ['rows' => 3]) !!}
                {!! Form::groupRichtext('text', $catalog->text, 'Основной текст', ['rows' => 3]) !!}
            </div>
        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
</form>
