<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Prevenção - GeoSaúde Angola</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card-doenca { transition: transform 0.3s ease; border: none; }
        .card-doenca:hover { transform: translateY(-5px); }
        .icon-box { width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 15px; }
        .btn-oms { font-size: 0.8rem; border-radius: 20px; text-transform: uppercase; font-weight: bold; }
    </style>
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-success shadow mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="fas fa-arrow-left me-2"></i> Voltar ao Radar
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark">Guia de Prevenção Comunitária</h2>
            <div class="mx-auto bg-success" style="height: 4px; width: 60px; border-radius: 2px;"></div>
            <p class="text-muted mt-3">Medidas de proteção e boas práticas recomendadas por autoridades nacionais e internacionais.</p>
        </div>

        <div class="row">
            @foreach($doencas as $doenca)
                @php
                    // Lógica para variar cores e ícones conforme a doença
                    $nome = strtolower($doenca->nome_doenca);
                    $config = [
                        'color' => '#6c757d', 
                        'icon' => 'fa-shield-virus', 
                        'link_oms' => 'https://www.who.int/pt/health-topics'
                    ];

                    if (str_contains($nome, 'malária')) {
                        $config = ['color' => '#0d6efd', 'icon' => 'fa-mosquitos', 'link_oms' => 'https://www.who.int/news-room/fact-sheets/detail/malaria'];
                    } elseif (str_contains($nome, 'cólera')) {
                        $config = ['color' => '#dc3545', 'icon' => 'fa-faucet-drip', 'link_oms' => 'https://www.who.int/news-room/fact-sheets/detail/cholera'];
                    } elseif (str_contains($nome, 'covid')) {
                        $config = ['color' => '#6610f2', 'icon' => 'fa-head-side-mask', 'link_oms' => 'https://www.who.int/health-topics/coronavirus'];
                    } elseif (str_contains($nome, 'dengue')) {
                        $config = ['color' => '#fd7e14', 'icon' => 'fa-bug', 'link_oms' => 'https://www.who.int/news-room/fact-sheets/detail/dengue-and-severe-dengue'];
                    }
                @endphp

                <div class="col-md-6 mb-4">
                    <div class="card card-doenca shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box text-white me-3" style="background-color: {{ $config['color'] }};">
                                    <i class="fas {{ $config['icon'] }} fa-2xl"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-0 text-uppercase" style="color: {{ $config['color'] }};">{{ $doenca->nome_doenca }}</h4>
                                    <span class="badge rounded-pill bg-light text-dark border">Protocolo Ativo</span>
                                </div>
                            </div>
                            
                            <h6 class="fw-bold mb-3"><i class="fas fa-hand-holding-medical me-2 text-success"></i>AÇÕES RECOMENDADAS:</h6>
                            <div class="card-text text-secondary mb-4" style="white-space: pre-line; line-height: 1.6;">
                                {{ $doenca->prevencao ?? 'Diretrizes em atualização técnica.' }}
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-start pt-3 border-top">
                                <a href="{{ $config['link_oms'] }}" target="_blank" class="btn btn-outline-primary btn-oms">
                                    <i class="fas fa-globe me-1"></i> Guia Prático OMS
                                </a>
                                <button class="btn btn-light btn-oms text-muted" disabled>
                                    <i class="fas fa-file-pdf me-1"></i> Protocolo MINSA
                                </button>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-0 py-3 px-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="fas fa-sync-alt me-1"></i> Atualizado hoje</small>
                                <i class="fas fa-check-circle text-success" title="Validado"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row mt-5 mb-5">
            <div class="col-12">
                <div class="p-5 bg-white rounded shadow-sm border-start border-success border-5">
                    <h3 class="fw-bold text-success mb-4"><i class="fas fa-heartbeat me-2"></i> Boas Práticas Gerais para a Sua Saúde</h3>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <h5><i class="fas fa-hand-sparkles text-primary me-2"></i> Higiene das Mãos</h5>
                            <p class="small text-muted">Lave as mãos com água e sabão frequentemente para evitar doenças diarreicas e respiratórias.</p>
                        </div>
                        <div class="col-md-4">
                            <h5><i class="fas fa-tint text-info me-2"></i> Água Potável</h5>
                            <p class="small text-muted">Consuma apenas água tratada ou fervida. Mantenha os recipientes de armazenamento sempre fechados.</p>
                        </div>
                        <div class="col-md-4">
                            <h5><i class="fas fa-trash-alt text-warning me-2"></i> Gestão de Resíduos</h5>
                            <p class="small text-muted">Mantenha o lixo tapado e evite águas paradas perto de casa para combater o mosquito transmissor da Malária.</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="https://www.who.int/pt/emergencies/diseases/novel-coronavirus-2019/advice-for-public" target="_blank" class="text-decoration-none fw-bold">
                            Ver mais dicas de higiene no site oficial da OMS <i class="fas fa-external-link-alt ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center pb-5 text-muted">
        <small>&copy; {{ date('Y') }} GeoSaúde Angola - Dados integrados com o MINSA</small>
    </footer>

</body>
</html>