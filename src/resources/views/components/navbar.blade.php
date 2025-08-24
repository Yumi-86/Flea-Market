<nav class="navbar">
    <div class="navbar__search">
        <form action="{{ url('/') }}" method="GET" class="navbar__search-form">
            <input type="text" name="keyword" class="navbar__search-input" placeholder="なにをお探しですか？">
        </form>
    </div>
    <div class="navbar__menu">
        @auth
        <form method="POST" action="{{ route('logout') }}" class="navbar__action navbar__logout-form">
            @csrf
            <button type="submit" class="navbar__link navbar__logout-button">ログアウト</button>
        </form>
        @endauth

        @guest
        <div class="navbar__action">
            <a href="{{ route('login') }}" class="navbar__link navbar__login-button">ログイン</a>
        </div>
        @endguest

        <a href="{{ url('/mypage') }}" class="navbar__link navbar__link--mypage">マイページ</a>
        <a href="{{ url('/sell') }}" class="btn navbar__link navbar__link--sell">出品</a>
    </div>
</nav>