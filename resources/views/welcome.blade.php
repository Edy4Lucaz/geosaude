<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoSaúde Angola</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero {
            background: linear-gradient(45deg, #198754, #20c997);
            color: white;
            padding: 100px 0;
        }

        .navbar {
            background-color: #146c43 !important;
        }

        .feature-icon {
            font-size: 2rem;
            color: #198754;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .pulse-icon {
            animation: pulse 2s infinite;
        }
    </style>
</head>

<body>
    @isset($configuracoesAtivas)
        @foreach($configuracoesAtivas as $alerta)
            @if($alerta->nivel_atual == 'pandemia')
                <div class="alert alert-danger shadow-lg border-0 mb-0 rounded-0 text-center fw-bold py-3"
                    style="background-color: #ff0000; color: white;">
                    <i class="fas fa-exclamation-triangle pulse-icon me-2"></i>
                    ALERTA CRÍTICO: Pandemia de {{ $alerta->doenca->nome_doenca }} em curso. Consulte o Radar Epidemiológico!
                </div>
            @endif
        @endforeach
    @endisset

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">GeoSaúde Angola</a>
            <div class="ms-auto">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-4 shadow-sm">
                        <i class="fas fa-user-lock me-1"></i> Login
                    </a>
                @else
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle fw-bold text-success px-3 shadow-sm"
                            type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-md me-1"></i> {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            @if(Auth::user()->role == 2)
                                <li>
                                    <a class="dropdown-item fw-bold text-success" href="{{ route('medico.index') }}">
                                        <i class="fas fa-user-md me-2"></i>Painel Clínico
                                    </a>
                                </li>
                            @endif
                            @if(Auth::user()->role == 1)
                                <li><a class="dropdown-item fw-bold text-danger" href="{{ route('admin.dashboard') }}"><i
                                            class="fas fa-cog me-2"></i>Gerir Sistema</a></li>
                            @endif
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
                @endguest
            </div>
        </div>
    </nav>

    <header class="hero text-center shadow">
        <div class="container">
            <h1 class="display-3 fw-bold">GeoSaúde Angola</h1>
            <p class="lead mb-4">Sistema Inteligente de Vigilância Epidemiológica e Georreferenciamento.</p>
            <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                <a href="{{ route('mapa.publico') }}" class="btn btn-light btn-lg fw-bold text-success px-5 shadow">
                    <i class="fas fa-map-marked-alt me-2"></i> Radar Epidemiológico
                </a>
                <a href="{{ route('noticias.index') }}" class="btn btn-warning btn-lg fw-bold px-5 shadow">
                    <i class="fas fa-newspaper me-2"></i> Notícias de Saúde
                </a>
                <a href="{{ route('prevencao.index') }}" class="btn btn-light text-success fw-bold shadow-sm">
                    <i class="fas fa-shield-alt me-1"></i> PREVENÇÃO
                </a>
            </div>
        </div>
    </header>

    <section class="container my-5 text-center">
        <div class="row">
            <div class="col-md-4">
                <div class="p-4 border rounded shadow-sm h-100 bg-white">
                    <i class="fas fa-chart-pie feature-icon mb-3"></i>
                    <h3>Monitorização</h3>
                    <p>Mapeamento em tempo real de surtos endémicos em todas as províncias angolanas.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border rounded shadow-sm h-100 bg-white">
                    <i class="fas fa-shield-virus feature-icon mb-3"></i>
                    <h3>Prevenção</h3>
                    <p>Alertas baseados em dados reais para proteção da população.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border rounded shadow-sm h-100 bg-white">
                    <i class="fas fa-user-md feature-icon mb-3"></i>
                    <h3>Dados Oficiais</h3>
                    <p>Acesso exclusivo para profissionais de saúde registarem ocorrências no terreno.</p>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>