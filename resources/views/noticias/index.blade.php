<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoSaúde - Notícias de Saúde</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Ajuste do verde para combinar exatamente com o radar */
        .navbar { background-color: #198754 !important; }
        .card-img-top { height: 200px; object-fit: cover; }
        .navbar-brand { font-weight: bold; letter-spacing: 1px; color: white !important; }
        .nav-link { color: rgba(255,255,255,0.8) !important; font-weight: 500; }
        .nav-link:hover { color: #fff !important; }
        
        .news-header {
            background: #fff;
            padding: 40px 0;
            margin-bottom: 30px;
            border-bottom: 1px solid #dee2e6;
        }
    </style>
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="fas fa-heartbeat me-2"></i>GeoSaúde Angola
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('prevencao.index') }}">Prevenção</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('mapa.publico') }}" class="btn btn-light btn-sm shadow-sm px-3 fw-bold text-success">
                        <i class="fas fa-map-marker-alt me-1"></i> Monitor Epidemiológico
                    </a>

                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-4">
                            Login
                        </a>
                    @else
                        <div class="dropdown">
                            <button class="btn btn-success btn-sm dropdown-toggle border-light" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line me-2"></i>Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Sair</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <div class="news-header">
        <div class="container text-center text-md-start">
            <h1 class="display-5 fw-bold text-dark">Notícias de Saúde em Tempo Real</h1>
            <p class="lead text-muted">Informações atualizadas sobre Saúde a nivel do Mundo</p>
        </div>
    </div>

    <div class="container">
        <div class="row">
            @if(isset($noticias) && count($noticias) > 0)
                @foreach($noticias as $noticia)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            @if(isset($noticia['urlToImage']) && $noticia['urlToImage'])
                                <img src="{{ $noticia['urlToImage'] }}" class="card-img-top" alt="Notícia">
                            @else
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-3x opacity-25"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-dark">{{ Str::limit($noticia['title'], 65) }}</h5>
                                <p class="card-text text-muted small">
                                    {{ Str::limit($noticia['description'] ?? 'Sem descrição disponível.', 110) }}
                                </p>
                            </div>
                            <div class="card-footer bg-white border-0 pb-3">
                                <a href="{{ $noticia['url'] }}" target="_blank" class="btn btn-success btn-sm w-100 fw-bold">
                                    Ler notícia completa <i class="fas fa-external-link-alt ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center py-5">
                    <div class="alert alert-info border-0 shadow-sm">
                        <i class="fas fa-info-circle me-2"></i> Não foi possível carregar as notícias de momento.
                    </div>
                </div>
            @endif
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0 small">© 2026 GeoSaúde Angola - Ministério da Saúde</p>
            <p class="small text-muted">Dados fornecidos por NewsAPI.org</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>