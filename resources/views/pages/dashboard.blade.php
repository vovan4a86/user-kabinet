@extends('template')
@section('content')
    <main>
        <section class="container">
            <div class="text-center">
                <div class="d-flex justify-content-between">
                    <h2>Личный кабинет</h2>
                    <form id="form-logout" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-dark" type="submit">Выйти</button>
                    </form>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        @include('pages.user_image')
                        <div class="custom-file text-left">
                            <input type="file" class="custom-file-input" id="userImage"
                                accept=".jpg,.jpeg,.png" data-id="{{ $user->id }}">
                            <label class="custom-file-label" for="userImage">Фото</label>
                        </div>
                    </div>
                    <form class="user-info col-sm-9 text-left" action="{{ route('ajax.userSaveInfo', $user->id) }}">
                        <label>
                            Имя:
                            <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                        </label>
                        <label>
                            Email:
                            <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                        </label>
                        <button type="submit" class="btn btn-success">Сохранить</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
@stop
