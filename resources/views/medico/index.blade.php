<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Médico - GeoSaúde Angola</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --angola-green: #198754;
            --angola-blue: #0dcaf0;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: #146c43 !important;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #198754 0%, #146c43 100%);
            color: white;
            padding: 60px 0;
            border-radius: 0 0 50px 50px;
            margin-bottom: -50px;
        }

        .card-menu {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .card-menu:hover {
            transform: translateY(-10px);
            shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
        }

        .icon-box {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 20px;
        }

        .bg-soft-success {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .bg-soft-info {
            background-color: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="fas fa-microscope me-2"></i>GeoSaúde Angola
            </a>
            <div class="ms-auto">
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle fw-bold text-success px-3 shadow-sm"
                        type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-md me-1"></i> Dr(a). {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item" href="{{ url('/') }}"><i class="fas fa-home me-2"></i>Início</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i
                                        class="fas fa-sign-out-alt me-2"></i>Sair</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <header class="dashboard-header text-center shadow">
        <div class="container">
            <h1 class="display-5 fw-bold">Painel Clínico</h1>
            <p class="lead">Bem-vindo ao sistema de suporte à decisão e registo epidemiológico.</p>
        </div>
    </header>

    <main class="container mb-5">
        <div class="row justify-content-center g-4">

            <div class="col-md-5 col-lg-4">
                <div class="card card-menu shadow-sm h-100 p-4">
                    <div class="card-body text-center">
                        <div class="icon-box bg-soft-success">
                            <i class="fas fa-plus-circle fa-2x"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Registar Caso</h3>
                        <p class="text-muted mb-4">Notificar nova ocorrência de doença para o Radar Epidemiológico
                            Nacional.</p>
                        <a href="{{ route('casos.create') }}"
                            class="btn btn-success w-100 py-3 fw-bold rounded-pill shadow">
                            <i class="fas fa-file-medical-alt me-2"></i>NOVO REGISTO
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-5 col-lg-4">
                <div class="card card-menu shadow-sm h-100 p-4">
                    <div class="card-body text-center">
                        <div class="icon-box bg-soft-info">
                            <i class="fas fa-address-card fa-2x"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Ficha do Paciente</h3>
                        <p class="text-muted mb-4">Consultar histórico, dados demográficos e antecedentes clínicos
                            registados.</p>
                        <a href="{{ route('ficha.index') }}"
                            class="btn btn-info text-white w-100 py-3 fw-bold rounded-pill shadow">
                            <i class="fas fa-search me-2"></i>CONSULTAR FICHA
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-5 justify-content-center">
            <div class="col-md-10">
                <div class="alert alert-light border-0 shadow-sm rounded-4 p-4 d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-success fa-2x"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="fw-bold mb-1">Atenção ao Preenchimento</h6>
                        <p class="mb-0 small text-muted">Certifique-se de que os dados de georreferenciação estão
                            corretos.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center text-muted mt-5 pb-4">
        <small>&copy; 2026 GeoSaúde Angola - Sistema de Vigilância Epidemiológica</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>