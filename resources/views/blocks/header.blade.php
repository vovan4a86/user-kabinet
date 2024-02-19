<header>
    <div class="container header d-flex justify-content-between my-4">
        <div class="logo"><img src="/static/images/logo.png" alt="logo" height="50"></div>
        <div class="site-name"><a href="/">Мой магазин</a> | <a href="{{ route('catalog.index') }}">Каталог</a></div>
        @if (Route::has('login'))
            <div class="user">
                @auth
                    @include('blocks.header_user')
                    <a href="{{ url('/dashboard') }}">Личный кабинет</a>
                @else
                    <a href="{{ route('login') }}" class="d-block btn btn-sm btn-success">Войти</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="d-block btn btn-sm btn-info mt-1">Регистрация</a>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</header>
