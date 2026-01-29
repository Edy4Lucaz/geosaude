<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoSaúde - Controlo de Alertas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .navbar { background-color: #146c43 !important; }
        .card-alerta { border-radius: 15px; border: none; transition: 0.3s; }
        .card-alerta:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        /* Cores Customizadas para Pandemia */
        .bg-pandemia { background-color: #dc3545 !important; color: white !important; }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-arrow-left me-2"></i> Voltar ao Painel
            </a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="fw-bold text-dark">Configuração de Alertas Epidemiológicos</h2>
                <p class="text-muted">Defina o nível de perigo e o modo de ativação para cada patologia.</p>
            </div>
        </div>

        <div class="card card-alerta shadow-sm mb-5 bg-white">
            <div class="card-body p-4">
                <h5 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-2"></i> Legenda de Níveis</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="p-2 border-start border-success border-4 bg-light rounded shadow-sm">
                            <strong class="text-success small">ENDEMIA</strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-2 border-start border-warning border-4 bg-light rounded shadow-sm">
                            <strong class="text-warning small">SURTO</strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-2 border-start border-danger border-4 bg-light rounded shadow-sm">
                            <strong class="text-danger small">EPIDEMIA</strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-2 border-start border-dark border-4 bg-danger text-white rounded shadow-sm">
                            <strong class="small">PANDEMIA</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($configuracoes as $config)
            <div class="col-md-6 col-lg-4 mb-4">
                @php
                    // Lógica de cor dinâmica baseada no nível (usada se o alerta estiver ativo)
                    $cardColor = 'bg-white';
                    if($config->alerta_ativo) {
                        $cardColor = match($config->nivel_atual) {
                            'surto' => 'border-warning',
                            'epidemia' => 'border-danger',
                            'pandemia' => 'bg-pandemia',
                            default => 'bg-white'
                        };
                    }
                @endphp

                <div class="card card-alerta shadow-sm h-100 {{ $cardColor }} {{ $config->nivel_atual == 'pandemia' && $config->alerta_ativo ? '' : 'border-top border-4' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="fw-bold mb-0">{{ $config->doenca->nome_doenca }}</h5>
                            <span class="badge {{ $config->alerta_ativo ? 'bg-success' : 'bg-secondary' }}">
                                {{ $config->alerta_ativo ? 'Mapa: LIGADO' : 'Mapa: DESLIGADO' }}
                            </span>
                        </div>

                        <form action="{{ route('admin.alertas.update', $config->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nível Epidemiológico Atual</label>
                                <select name="nivel_atual" class="form-select form-select-sm shadow-sm">
                                    <option value="endemia" {{ $config->nivel_atual == 'endemia' ? 'selected' : '' }}>Endemia (Normalidade)</option>
                                    <option value="surto" {{ $config->nivel_atual == 'surto' ? 'selected' : '' }}>Surto (Localizado)</option>
                                    <option value="epidemia" {{ $config->nivel_atual == 'epidemia' ? 'selected' : '' }}>Epidemia (Regional/Nacional)</option>
                                    <option value="pandemia" {{ $config->nivel_atual == 'pandemia' ? 'selected' : '' }}>Pandemia (Crítico)</option>
                                </select>
                            </div>

                            <hr class="my-3 opacity-25">

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Modo de Ativação</label>
                                <select name="modo" class="form-select form-select-sm">
                                    <option value="manual" {{ $config->modo == 'manual' ? 'selected' : '' }}>Manual (Sempre visível)</option>
                                    <option value="automatico" {{ $config->modo == 'automatico' ? 'selected' : '' }}>Automático (Por nº casos)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Limite para Gatilho Automático</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                                    <input type="number" name="threshold_surto" class="form-control" value="{{ $config->threshold_surto }}">
                                </div>
                            </div>

                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" name="alerta_ativo" value="1" {{ $config->alerta_ativo ? 'checked' : '' }}>
                                <label class="form-check-label small fw-bold">Exibir Alerta no Portal Público</label>
                            </div>

                            <button type="submit" class="btn {{ $config->nivel_atual == 'pandemia' && $config->alerta_ativo ? 'btn-light text-danger' : 'btn-dark' }} btn-sm w-100 fw-bold shadow-sm">
                                <i class="fas fa-save me-1"></i> Atualizar Estado
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>