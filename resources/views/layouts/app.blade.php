<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SOL & LUNA</title>

    <style>
        :root {
            --navy: #061527;
            --navy-2: #081d34;
            --navy-3: #0e2d4d;
            --gold: #f6b23c;
            --gold-2: #d99522;
            --bg: #f4f7fb;
            --text: #07182d;
            --muted: #6f7f91;
            --line: #dde6ef;
            --danger: #c0392b;
            --success: #157347;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 290px;
            background:
                radial-gradient(circle at top left, rgba(246,178,60,.18), transparent 30%),
                linear-gradient(180deg, #04101f 0%, #061527 55%, #03101d 100%);
            color: white;
            padding: 24px 16px;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
            border-right: 1px solid rgba(246,178,60,.22);
        }
        .btn-edit {
    background: linear-gradient(135deg, #0f5c8c, #12395a);
    color: white;
    border: 1px solid rgba(246,178,60,.35);
    box-shadow: 0 8px 18px rgba(15,92,140,.18);
}

        .brand-box {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 10px 8px 24px 8px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            margin-bottom: 22px;
        }

        .logo-circle {
            width: 54px;
            height: 54px;
            border-radius: 18px;
            background: linear-gradient(135deg, #101c2d, #030912);
            border: 1px solid rgba(246,178,60,.75);
            box-shadow: 0 0 25px rgba(246,178,60,.18);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            font-size: 25px;
        }

        .brand-title {
            font-size: 24px;
            color: var(--gold);
            font-weight: 800;
            letter-spacing: 1px;
        }

        .brand-subtitle {
            font-size: 11px;
            color: #b6c4d5;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 4px;
        }

        .menu-title {
            color: var(--gold);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 3px;
            margin: 26px 10px 10px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #dbe7f3;
            text-decoration: none;
            padding: 14px 14px;
            border-radius: 12px;
            margin-bottom: 7px;
            transition: .2s;
            font-size: 15px;
        }

        .menu a:hover,
        .menu a.active {
            background: linear-gradient(90deg, rgba(246,178,60,.22), rgba(18,57,90,.78));
            box-shadow: inset 3px 0 0 var(--gold);
            color: white;
        }

        .menu a.disabled {
            opacity: .45;
            pointer-events: none;
        }

        .user-box {
            margin-top: 40px;
            padding: 16px;
            border-radius: 18px;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: #101c2d;
            border: 1px solid var(--gold);
            color: var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        .content {
            margin-left: 290px;
            width: calc(100% - 290px);
            min-height: 100vh;
        }

        .topbar {
            background: rgba(255,255,255,.88);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--line);
            padding: 24px 38px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .page-title {
            margin: 0;
            font-size: 28px;
            color: var(--text);
        }

        .page-subtitle {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 15px;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .main {
            padding: 32px 38px;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 26px;
            box-shadow: 0 18px 45px rgba(5, 18, 32, .08);
            border: 1px solid rgba(221,230,239,.9);
        }

        .btn {
            border: none;
            outline: none;
            padding: 13px 20px;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 700;
            font-size: 14px;
            transition: .2s;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            color: white;
            background: linear-gradient(135deg, #061527, #0e2d4d);
            border: 1px solid rgba(246,178,60,.55);
            box-shadow: 0 10px 20px rgba(6,21,39,.18), 0 0 0 3px rgba(246,178,60,.08);
        }

        .btn-gold {
            background: linear-gradient(135deg, #f6b23c, #d99522);
            color: #061527;
        }

        .btn-light {
            background: white;
            color: var(--text);
            border: 1px solid var(--line);
        }

        .btn-danger {
            background: #fff3f2;
            color: var(--danger);
            border: 1px solid #ffd1cd;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 14px;
            margin-bottom: 18px;
            font-weight: 600;
        }

        .success {
            background: #e7f8ec;
            color: var(--success);
            border: 1px solid #b8edc9;
        }

        .error {
            background: #fdecea;
            color: #b02a37;
            border: 1px solid #f5c2c7;
        }

        .input,
        select,
        textarea {
            width: 100%;
            border: 1px solid #dce5ee;
            border-radius: 13px;
            padding: 15px 16px;
            font-size: 15px;
            outline: none;
            background: white;
            color: var(--text);
            transition: .2s;
        }

        .input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 4px rgba(246,178,60,.13);
        }

        label {
            font-weight: 800;
            font-size: 14px;
            color: #15263b;
            display: block;
            margin-bottom: 8px;
        }

        .required {
            color: #e74c3c;
        }

        .help {
            font-size: 13px;
            color: var(--muted);
            margin-top: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #e5edf5;
            text-align: left;
        }

        th {
            background: #eef4fa;
            font-size: 13px;
            text-transform: uppercase;
        }

        .badge {
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .ok {
            background: #e7f8ec;
            color: #157347;
        }

        .warning {
            background: #fff3cd;
            color: #9a6500;
        }

        .danger {
            background: #fdecea;
            color: #b02a37;
        }
    </style>
</head>
<body>

<div class="layout">
    <aside class="sidebar">
        <div class="brand-box">
            <div class="logo-circle">☀︎</div>
            <div>
                <div class="brand-title">SOL & LUNA</div>
                <div class="brand-subtitle">Panel administrativo</div>
            </div>
        </div>

        <div class="menu">
            <div class="menu-title">PEDIDOS</div>
            <a href="{{ route('ventas.index') }}" class="{{ request()->routeIs('ventas.*') ? 'active' : '' }}">
    <span>🛒</span> Ventas
</a>
<a href="{{ route('ventas.reporte') }}" class="{{ request()->routeIs('ventas.reporte') ? 'active' : '' }}">
    <span>▥</span> Reporte de ventas
</a>
            

            <div class="menu-title">INVENTARIO</div>
            <a href="{{ route('productos.index') }}" class="{{ request()->routeIs('productos.index') ? 'active' : '' }}">
                <span>▣</span> Productos
            </a>
            <a href="{{ route('productos.create') }}" class="{{ request()->routeIs('productos.create') ? 'active' : '' }}">
                <span>＋</span> Registrar producto
            </a>
            

            <div class="menu-title">PIEZAS</div>
            <a href="{{ route('trabajadores.index') }}" class="{{ request()->routeIs('trabajadores.*') ? 'active' : '' }}">
                <span>☻</span> Trabajadores
            </a>
            <a href="{{ route('horas-extras.index') }}" class="{{ request()->routeIs('horas-extras.*') ? 'active' : '' }}">
                <span>◴</span> Registro de Piezas
            </a>

          
        </div>
<p>
</p>
<p>
</p>
<p>
</p>

        <div class="user-box">
            <div class="avatar">AD</div>
            <div>
                <div style="font-weight:800;">{{ Auth::user()->name ?? 'Admin Sol & Luna' }}</div>
                <div style="font-size:13px; color:#9fb3c8;">{{ Auth::user()->role ?? 'Administrador' }}</div>
            </div>
        </div>
    </aside>

    <section class="content">
        <header class="topbar">
            <div>
                <h1 class="page-title">@yield('title')</h1>
                <p class="page-subtitle">@yield('subtitle')</p>
            </div>

            <div class="top-actions">
                @yield('actions')

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-danger">Cerrar sesión</button>
                </form>
            </div>
        </header>

        <main class="main">
            @if(session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </main>
    </section>
</div>

</body>
</html>