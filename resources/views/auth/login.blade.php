<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoSaúde - Acesso Restrito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f7f6;
            height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .btn-success {
            border-radius: 8px;
            padding: 12px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-success">GEOSAÚDE</h2>
                    <p class="text-muted">Acesso para Profissionais e Administradores</p>
                </div>

                <div class="card login-card p-4">
                    <form action="{{ route('login.submit') }}" method="POST">
                        @csrf

                        @if($errors->any())
                            <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label small fw-bold">E-mail Profissional</label>
                            <input type="email" name="email" class="form-control" placeholder="exemplo@saude.gov.ao"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Palavra-passe</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Entrar no Sistema</button>
                        </div>
                    </form>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted small">← Voltar para
                        página inicial</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>