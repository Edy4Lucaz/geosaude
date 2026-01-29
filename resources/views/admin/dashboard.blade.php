<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoSaúde - Painel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .navbar {
            background-color: #146c43 !important;
        }

        .admin-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s;
        }

        .admin-card:hover {
            transform: translateY(-5px);
        }

        .bg-gradient-green {
            background: linear-gradient(45deg, #198754, #20c997);
            color: white;
        }

        .module-card {
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
        }

        .module-card:hover {
            background-color: #f8f9fa;
            border-color: #198754 !important;
        }
    </style>
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">GeoSaúde Angola <span class="badge bg-danger ms-2"
                    style="font-size: 0.6rem;">ADMIN</span></a>
            <div class="ms-auto">
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle fw-bold text-success px-3 shadow-sm"
                        type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-shield me-1"></i> {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item" href="{{ url('/') }}"><i class="fas fa-home me-2"></i>Ir para o
                                Portal</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-muted"><i
                                        class="fas fa-sign-out-alt me-2"></i>Sair</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold text-dark"><i class="fas fa-tachometer-alt me-2 text-success"></i>Gestão do Sistema
                </h2>
                <p class="text-muted">Bem-vindo ao centro de controlo epidemiológico.</p>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card admin-card shadow-sm bg-gradient-green p-3 text-center">
                    <h6 class="text-uppercase small">Profissionais</h6>
                    <h2 class="fw-bold">{{ $stats['total_medicos'] ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card admin-card shadow-sm bg-white p-3 text-center border-start border-success border-4">
                    <h6 class="text-uppercase text-muted small">Casos Totais</h6>
                    <h2 class="fw-bold text-success">{{ $stats['total_casos'] }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card admin-card shadow-sm bg-white p-3 text-center border-start border-warning border-4">
                    <h6 class="text-uppercase text-muted small">Patologias</h6>
                    <h2 class="fw-bold text-warning">{{ $stats['total_doencas'] }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card admin-card shadow-sm bg-dark p-3 text-center text-white">
                    <h6 class="text-uppercase small">Alertas Ativos</h6>
                    <h2 class="fw-bold">03</h2>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">Módulos de Gestão</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="{{ route('admin.usuarios') }}"
                                    class="btn btn-outline-success w-100 p-4 text-start shadow-sm rounded-4 module-card">
                                    <i class="fas fa-users-cog fs-3 mb-2"></i>
                                    <h5>Gerir Utilizadores</h5>
                                    <small class="text-muted d-block">Adicionar médicos e administradores.</small>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('admin.doencas') }}"
                                    class="btn btn-outline-primary w-100 p-4 text-start shadow-sm rounded-4 module-card">
                                    <i class="fas fa-virus fs-3 mb-2"></i>
                                    <h5>Configurar Doenças</h5>
                                    <small class="text-muted d-block">Editar sintomas e métodos de prevenção.</small>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative">
                                    <button id="btnRelatorio"
                                        class="btn btn-outline-warning w-100 p-4 text-start shadow-sm rounded-4 module-card text-dark">
                                        <i class="fas fa-file-export fs-3 mb-2"></i>
                                        <h5>Relatórios Estatísticos</h5>
                                        <small class="text-muted d-block">Gerar dados de surtos e casos.</small>
                                    </button>

                                    <div id="formatoRelatorio"
                                        class="mt-2 p-3 bg-white border rounded shadow-sm d-none">
                                        <p class="small fw-bold mb-2">Selecione o formato do relatório:</p>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.relatorios', ['format' => 'pdf']) }}"
                                                class="btn btn-sm btn-danger flex-grow-1">
                                                <i class="fas fa-file-pdf me-1"></i> PDF
                                            </a>
                                            <a href="{{ route('admin.relatorios', ['format' => 'csv']) }}"
                                                class="btn btn-sm btn-success flex-grow-1">
                                                <i class="fas fa-file-csv me-1"></i> CSV
                                            </a>
                                            <button type="button" class="btn btn-sm btn-light border"
                                                onclick="document.getElementById('formatoRelatorio').classList.add('d-none')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <small class="text-danger d-block mt-2" style="font-size: 0.7rem;">
                                            <i class="fas fa-user-secret me-1"></i> Anonimização de Dados Ativada (***)
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <script>
                                // Lógica para mostrar/esconder as opções de formato
                                document.getElementById('btnRelatorio').addEventListener('click', function (e) {
                                    e.preventDefault();
                                    const menu = document.getElementById('formatoRelatorio');
                                    menu.classList.toggle('d-none');
                                });
                            </script>
                            <div class="col-md-6">
                                <a href="{{ route('admin.alertas') }}"
                                    class="btn btn-outline-danger w-100 p-4 text-start shadow-sm rounded-4 module-card">
                                    <i class="fas fa-bell fs-3 mb-2"></i>
                                    <h5>Definir Alertas</h5>
                                    <small class="text-muted d-block">Alertar população sobre novos surtos.</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-dark text-white fw-bold">Atividade Recente (Audit)</div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($stats['ultimos_logs'] as $log)
                                <li class="list-group-item small">
                                    <strong class="text-success">{{ $log->user->name ?? 'Utilizador Removido' }}</strong>:
                                    {{ $log->acao }}
                                    <br>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>{{ $log->created_at->diffForHumans() }}
                                    </small>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">Nenhuma atividade registada.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="card-footer bg-white text-center">
                        <a href="{{ route('admin.logs') }}" class="small text-success text-decoration-none fw-bold">Ver
                            todos os logs históricos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>