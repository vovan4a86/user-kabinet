@extends('template')
@section('content')
    <main>
        <section class="container">
            <div class="text-center">
                <h2>Dashboard</h2>
                <h3>Welcome <b>{{ $user->name }} ({{ $user->id }})</b></h3>
            </div>

            <div class="text-right">
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Выйти
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
{{--                <form id="form-logout" method="POST" action="{{ route('logout') }}">--}}
{{--                    @csrf--}}
{{--                    <button class="btn btn-default" type="submit">Выйти</button>--}}
{{--                </form>--}}
            </div>
        </section>
    </main>
@stop
