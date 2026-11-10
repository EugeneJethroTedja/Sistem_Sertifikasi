<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin – Sistem Sertifikat</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:   #0f1e35;
            --navy2:  #162845;
            --gold:   #c9a84c;
            --gold2:  #e8c97a;
            --cream:  #f7f3ec;
            --muted:  #8a97a8;
            --error:  #e05252;
        }

        body {
            min-height: 100vh;
            display: flex;
            font-family: 'DM Sans', sans-serif;
            background: var(--navy);
            overflow: hidden;
        }

        /* ── Sisi Kiri – Dekorasi ── */
        .left-panel {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, var(--navy2) 0%, #0a1525 100%);
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,168,76,.18) 0%, transparent 70%);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: .6; }
            50%       { transform: translate(-50%, -50%) scale(1.15); opacity: 1; }
        }

        /* Grid pattern */
        .left-panel::after {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(201,168,76,.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(201,168,76,.06) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .brand {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            padding: 2rem;
        }

        .brand-icon {
            width: 80px; height: 80px;
            border: 2px solid var(--gold);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            background: rgba(201,168,76,.1);
            backdrop-filter: blur(10px);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }

        .brand h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--gold2);
            line-height: 1.2;
            margin-bottom: .5rem;
        }

        .brand p {
            color: var(--muted);
            font-size: .9rem;
            font-weight: 300;
            letter-spacing: .5px;
        }

        .brand-divider {
            width: 60px; height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            margin: 1.2rem auto;
        }

        /* Floating shapes */
        .shape {
            position: absolute;
            border: 1px solid rgba(201,168,76,.15);
            border-radius: 50%;
            animation: rotate linear infinite;
        }
        .shape-1 { width: 250px; height: 250px; top: 10%; left: 10%; animation-duration: 20s; }
        .shape-2 { width: 150px; height: 150px; bottom: 15%; right: 15%; animation-duration: 15s; }
        .shape-3 { width: 80px;  height: 80px;  top: 70%; left: 20%; animation-duration: 10s; animation-direction: reverse; }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* ── Sisi Kanan – Form ── */
        .right-panel {
            width: 440px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--cream);
            padding: 3rem 2.5rem;
            position: relative;
        }

        .right-panel::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: linear-gradient(to bottom, transparent, var(--gold), transparent);
        }

        .form-box {
            width: 100%;
            animation: slideIn .5s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.9rem;
            color: var(--navy);
            margin-bottom: .3rem;
        }

        .form-subtitle {
            color: var(--muted);
            font-size: .875rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.3rem;
        }

        label {
            display: block;
            font-size: .8rem;
            font-weight: 500;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: .5rem;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: .8rem 1rem;
            border: 1.5px solid #ddd6cc;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: .95rem;
            color: var(--navy);
            background: white;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }

        input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,168,76,.15);
        }

        input.is-invalid {
            border-color: var(--error);
        }

        .invalid-feedback {
            color: var(--error);
            font-size: .8rem;
            margin-top: .3rem;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 3px solid var(--error);
            border-radius: 8px;
            padding: .75rem 1rem;
            color: var(--error);
            font-size: .875rem;
            margin-bottom: 1.5rem;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1.5rem;
        }

        .remember-row input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: var(--gold);
            cursor: pointer;
        }

        .remember-row label {
            text-transform: none;
            letter-spacing: 0;
            font-size: .875rem;
            font-weight: 400;
            color: #555;
            margin: 0;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: .9rem;
            background: linear-gradient(135deg, var(--navy2), var(--navy));
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: .95rem;
            font-weight: 500;
            letter-spacing: .5px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform .15s, box-shadow .15s;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(201,168,76,.2), transparent);
            transition: left .4s;
        }

        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(15,30,53,.3); }
        .btn-login:hover::before { left: 100%; }
        .btn-login:active { transform: translateY(0); }

        .form-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e8e0d5;
            color: var(--muted);
            font-size: .8rem;
        }

        .password-wrapper {
            position: relative;
        }

        .input-password {
            position: relative;
        }

        .input-password input {
            padding-right: 45px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 1rem;
            color: #888;
            user-select: none;
            transition: color .2s;
        }

        .toggle-password:hover {
            color: var(--gold);
        }

        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 2rem 1.5rem; }
            .right-panel::before { display: none; }
        }
    </style>
</head>
<body>

    <!-- Kiri: Branding -->
    <div class="left-panel">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="brand">
            <div class="brand-icon">🏛️</div>
            <h1>Sistem<br>Sertifikat</h1>
            <div class="brand-divider"></div>
            <p>Manajemen Peserta &amp; Penilaian<br>Asesmen SDM Aparatur</p>
        </div>
    </div>

    <!-- Kanan: Form Login -->
    <div class="right-panel">
        <div class="form-box">
            <h2 class="form-title">Selamat Datang</h2>
            <p class="form-subtitle">Silakan masuk ke akun administrator</p>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="username">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Masukkan username"
                        class="{{ $errors->has('username') ? 'is-invalid' : '' }}"
                        autocomplete="username"
                        autofocus
                    >
                </div>

                <div class="form-group password-wrapper">
                    <label for="password">Password</label>
                    <div class="input-password">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                        >
                        <span class="toggle-password" onclick="togglePassword()">👁</span>
                    </div>
                </div>

                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ingat saya</label>
                </div>

                <button type="submit" class="btn-login">Masuk →</button>
            </form>

            <div class="form-footer">
                &copy; {{ date('Y') }} Sistem Sertifikat · All rights reserved
            </div>
        </div>
    </div>
<script>
function togglePassword() {
    const passwordInput = document.getElementById("password");
    const toggleIcon = document.querySelector(".toggle-password");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.textContent = "👁";
    } else {
        passwordInput.type = "password";
        toggleIcon.textContent = "👁";
    }
}
</script>
</body>
</html>