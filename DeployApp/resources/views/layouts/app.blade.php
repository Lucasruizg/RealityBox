<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RealityBox</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="container nav">
            <a href="{{ route('home') }}" class="logo">RealityBox</a>

            <nav>
                <a href="{{ route('home') }}">Inicio</a>
                <a href="{{ route('products.index') }}">Cajas</a>
                <a href="{{ route('test') }}">Test</a>
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="nav-logout">
                        @csrf
                        <button type="submit">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Registro</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>
