@extends('template')
@section('content')
    <main>
        <section class="product container">
            <div class="product__container">
                @include('blocks.bread')
            </div>
            <div class="product-head mb-4">
                <h2>Оставить отзыв о товаре {{ $product->name }}</h2>
            </div>
            <div class="row">
                <form id="send-opinion" action="{{ route('ajax.send-opinion') }}" class="d-flex flex-column">
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <label>
                        Оценка:
                        <select name="rate" class="form-control">
                            <option value="0" selected>0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </label>
                    <label>
                        Достоинства:
                        <textarea name="plus" cols="30" rows="3" class="form-control"></textarea>
                    </label>
                    <label>
                        Недостатки:
                        <textarea name="minus" cols="30" rows="3" class="form-control"></textarea>
                    </label>
                    <label>
                        Комментарий:
                        <textarea name="comment" cols="30" rows="3" class="form-control"></textarea>
                    </label>
                    <button type="submit" class="btn btn-success">Отправить</button>
                </form>
            </div>
        </section>
    </main>
@endsection
