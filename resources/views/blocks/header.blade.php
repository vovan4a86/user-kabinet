<header>
    <div class="container header d-flex justify-content-between my-4">
        <div class="logo">Logo</div>
        <div class="site-name"><a href="/">Site Name</a></div>
        @if (Route::has('login'))
            <div class="user">
                @auth
                    <div>{{ Auth::user()->name }}</div>
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
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
