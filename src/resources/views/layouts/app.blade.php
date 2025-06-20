<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECHフリマ</title>
    <link href="https://fonts.googleapis.com/css?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/base/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header-inner">
            <div class="header-logo">
                <a href="/" class="header-logo__link">
                    <img src="{{ asset('images/coachtech-logo.svg') }}" alt="COACHTECHロゴ" class="header-logo__img">
                </a>
            </div>
            @if(!Request::is('login') && !Request::is('register'))
            @include('components.navbar')
            @endif
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>