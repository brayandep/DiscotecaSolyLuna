<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SOL & LUNA</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f7fb;
            color: #0a1b2e;
        }

        .login-wrapper {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1.2fr;
        }

        /* PANEL IZQUIERDO */
        .login-left {
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 0;
        }

        .top-header {
            border-bottom: 1px solid #e4ebf3;
            padding: 18px 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
        }

        .brand {
            display: flex;
            flex-direction: column;
        }

        .brand-title {
            font-size: 22px;
            font-weight: 900;
            color: #0a1b2e;
            letter-spacing: 1px;
        }

        .brand-subtitle {
            font-size: 13px;
            color: #7b8794;
            margin-top: 4px;
        }

        .header-badge {
            font-size: 13px;
            font-weight: 700;
            color: #b67b14;
            background: #fff7e8;
            border: 1px solid rgba(246,178,60,.45);
            padding: 8px 14px;
            border-radius: 999px;
        }

        .login-content {
            width: 100%;
            max-width: 620px;
            margin: 0 auto;
            padding: 40px 48px 30px;
        }

        .section-label {
            color: #d99522;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .main-title {
            font-size: 62px;
            line-height: 1.02;
            font-weight: 900;
            margin: 0 0 20px 0;
            color: #081a32;
        }

        .main-description {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 28px;
            line-height: 1.5;
        }

        .alert {
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
        }

        .alert-error {
            background: #fdecea;
            color: #b02a37;
            border: 1px solid #f5c2c7;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 15px;
            font-weight: 800;
            margin-bottom: 8px;
            color: #0a1b2e;
        }

        .form-control {
            width: 100%;
            height: 54px;
            border: 1px solid #dbe5ef;
            border-radius: 14px;
            padding: 0 16px;
            font-size: 15px;
            outline: none;
            transition: .2s;
            background: #fff;
        }

        .form-control:focus {
            border-color: #1d6fa5;
            box-shadow: 0 0 0 4px rgba(29,111,165,.12);
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 8px 0 24px;
            color: #334155;
            font-size: 15px;
        }

        .btn-login {
            width: 100%;
            height: 56px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #0e6c9e, #124a76);
            color: #fff;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 12px 24px rgba(18,74,118,.18);
            transition: .2s;
        }

        .btn-login:hover {
            transform: translateY(-1px);
        }

        .contact-footer {
            margin-top: 26px;
            background: linear-gradient(135deg, #fff7e8, #ffffff);
            border: 1px solid rgba(246,178,60,.45);
            border-radius: 18px;
            padding: 18px;
        }

        .contact-footer-title {
            font-size: 16px;
            font-weight: 900;
            color: #0a1b2e;
            margin-bottom: 14px;
        }

        .contact-list {
            display: grid;
            gap: 10px;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: #334155;
            font-size: 14px;
            line-height: 1.5;
        }

        .contact-icon {
            width: 28px;
            min-width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #0f5f90;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: bold;
        }

        .bottom-note {
            margin-top: 14px;
            font-size: 13px;
            color: #64748b;
            text-align: center;
        }

        /* PANEL DERECHO */
        .login-right {
            position: relative;
            background:
                linear-gradient(rgba(4, 18, 35, 0.55), rgba(4, 18, 35, 0.72)),
                url('https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?auto=format&fit=crop&w=1400&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            padding: 70px;
        }

        .hero-box {
            max-width: 620px;
            color: white;
        }

        .hero-tag {
            display: inline-block;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 4px;
            color: #f6b23c;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .hero-title {
            font-size: 64px;
            line-height: 1.05;
            font-weight: 900;
            margin: 0 0 20px;
        }

        .hero-text {
            font-size: 19px;
            color: rgba(255,255,255,.92);
            line-height: 1.6;
            max-width: 520px;
        }

        .hero-info {
            margin-top: 26px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .hero-chip {
            border: 1px solid rgba(255,255,255,.18);
            background: rgba(255,255,255,.08);
            color: white;
            border-radius: 999px;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 700;
            backdrop-filter: blur(6px);
        }

        @media (max-width: 1100px) {
            .login-wrapper {
                grid-template-columns: 1fr;
            }

            .login-right {
                display: none;
            }

            .main-title {
                font-size: 48px;
            }

            .top-header {
                padding: 16px 24px;
            }

            .login-content {
                padding: 30px 24px;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- IZQUIERDA -->
    <div class="login-left">

        <div>
            <div class="top-header">
                <div class="brand">
                    <div class="brand-title">SOL & LUNA</div>
                    <div class="brand-subtitle">Sistema administrativo</div>
                </div>

                <div class="header-badge">Discoteca</div>
            </div>

            <div class="login-content">
                <div class="section-label">Discoteca</div>

                <h1 class="main-title">Bienvenido a<br>SOL & LUNA</h1>

                <p class="main-description">
                    Controla stock, trabajadores, caja y ventas desde un solo sistema.
                </p>

                @if ($errors->any())
                    <div class="alert alert-error">
                        {{ $errors->first() }}
                    </div>
                @endif

<form method="POST" action="{{ route('login.process') }}">                    @csrf

                    <div class="form-group">
                        <label for="username">Usuario</label>
                        <input
                            type="text"
                            name="username"
                            id="username"
                            class="form-control"
                            placeholder="Ingresa tu usuario"
                            value="{{ old('username') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Ingresa tu contraseña"
                            required
                        >
                    </div>

                    <div class="remember-row">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember" style="margin:0; font-weight:500;">Recordar sesión</label>
                    </div>

                    <button type="submit" class="btn-login">
                        Iniciar sesión
                    </button>
                </form>

                <div class="contact-footer">
                    <div class="contact-footer-title">Información del local</div>

                    <div class="contact-list">
                        <div class="contact-item">
                            <div class="contact-icon">W</div>
                            <div>
                                <strong>WhatsApp:</strong> 62787384
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">📍</div>
                            <div>
                                <strong>Ubicación:</strong> Capitán Ustariz km 8/2
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">🕒</div>
                            <div>
                                <strong>Atención:</strong> lunes a domingo a partir de las 7:00 PM
                            </div>
                        </div>
                    </div>

                    <div class="bottom-note">
                        Acceso exclusivo para personal autorizado de SOL & LUNA.
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- DERECHA -->
    <div class="login-right">
        <div class="hero-box">
            <div class="hero-tag">SOL · LUNA · NOCHE</div>

            <h2 class="hero-title">
                Todo el control de la discoteca en un solo lugar
            </h2>

            <p class="hero-text">
                Gestiona ventas, stock, pagos, trabajadores y control administrativo
                de forma rápida, ordenada y segura.
            </p>

            <div class="hero-info">
                <div class="hero-chip">Stock e inventario</div>
                <div class="hero-chip">Ventas y caja</div>
                <div class="hero-chip">Trabajadores</div>
                <div class="hero-chip">Horas extras</div>
            </div>
        </div>
    </div>

</div>

</body>
</html>