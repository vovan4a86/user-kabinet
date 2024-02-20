@extends('template')
@section('content')
    <main>
        <section class="product container">
            <div class="product__container">
                @include('blocks.bread')
            </div>
            <div class="product-head mb-4">
                <h2>{{ $product->name }}</h2>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    @if($img = $product->image()->first())
                        <img class="card-img-top"
                             src="{{ $img->image_src }}"
                             alt="{{ $product->name }}"/>
                    @else
                        <img class="card-img-top"
                             src="{{ Product::NO_IMAGE }}"
                             width="250" height="208" alt="{{ $product->name }}"/>
                    @endif
                </div>
                <div class="col-sm-9">
                    <div class="product-info">
                        <label>
                            Цена:
                            <span class="info-label">{{ $product->price }} ₽</span>
                        </label>
                        <label>
                            Наличие:
                            <span class="info-label">{{ $product->in_stock ? 'В наличии' : 'Под заказ' }}</span>
                        </label>
                        <div class="product-actions">
                            <button class="btn-buy btn btn-success">Купить</button>
                            <button class="btn-card btn btn-info">В корзину</button>
                            <a href="{{ route('catalog.add-opinion', $product->id) }}" class="btn-card btn btn-secondary">Оставить отзыв</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
