@extends('template')
@section('content')
    <main>
        <div class="container">
            <div class="error-page">
                <div class="error-page__title">Отзыв успешно отправлен</div>
                <div class="error-page__text">После проверки отзыва администратором, он будет добавлен к остальным</div>
                <div class="error-page__action">
                    @if(isset($product_url))
                        <a class="btn btn-primary" href="{{ $product_url }}">
                            <span>Вернуться к товару</span>
                        </a>
                    @endif
                    <a class="btn btn-secondary" href="{{ route('main') }}">
                        <span>На главную</span>
                    </a>
                </div>
            </div>
        </div>
    </main>
@stop
