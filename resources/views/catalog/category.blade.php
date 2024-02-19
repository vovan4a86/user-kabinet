@extends('template')
@section('content')
    <main>
        <section class="catalog-view">
            <div class="catalog-view__container container">
                @include('blocks.bread')
                <div class="catalog-view__heading">
                    <div class="page-title">{{ $h1 }}</div>
                </div>
                @if(isset($products) && count($products))
                    <div class="catalog-cards d-flex">
                        @foreach($products as $product)
                            <a href="{{ $product->url }}" class="product-card card">
                                @if($img = $product->image()->first())
                                    <img class="card-img-top"
                                         src="{{ $img->thumb(2) }}"
                                         width="250" height="208" alt="{{ $product->name }}"/>
                                @else
                                    <img class="card-img-top"
                                         src="{{ \Fanky\Admin\Models\Product::NO_IMAGE }}"
                                         width="250" height="208" alt="{{ $product->name }}"/>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
        <div class="catalog-text container">
            {!! $text !!}
        </div>
    </main>
@endsection
