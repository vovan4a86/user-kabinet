@extends('template')
@section('content')
    <main>
        <section class="catalog-view">
            <div class="catalog-view__container container">
                @include('blocks.bread')
                <div class="catalog-view__heading">
                    <div class="page-title">{{ $h1 }}</div>
                </div>
                @if(isset($categories) && count($categories))
                    <div class="catalog-cards d-flex">
                        @foreach($categories as $category)
                            <div class="card" style="width: 18rem;">
                                @if($category->image)
                                    <img class="card-img-top"
                                         src="{{ $category->thumb(2) }}"
                                         width="250" height="208" alt="{{ $category->name }}"/>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $category->name }}</h5>
                                    <p class="card-text"> {{ $category->announce }}</p>
                                    <a href="{{ $category->url }}" class="btn btn-primary">Перейти</a>
                                </div>
                            </div>
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
