<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha do Paciente - GeoSaúde Angola</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .navbar {
            background-color: #146c43 !important;
        }

        .card-ficha {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .header-ficha {
            background: #e9ecef;
            border-left: 5px solid #198754;
        }

        .section-title {
            font-size: 0.9rem;
            font-weight: bold;
            color: #198754;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .info-label {
            color: #6c757d;
            font-size: 0.85rem;
            margin-bottom: 2px;
        }

        .info-value {
            font-weight: 600;
            color: #2d3436;
        }

        .badge-doenca {
            background-color: #fff3f3;
            color: #d63031;
            border: 1px solid #fab1a0;
        }

        .qr-container {
            text-align: center;
            padding: 15px;
            border: 1px dashed #ddd;
            border-radius: 10px;
            background: #fff;
        }

        .empty-state {
            padding: 40px;
            text-align: center;
            color: #6c757d;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('medico.index') }}">
                <i class="fas fa-chevron-left me-2"></i> Voltar ao Painel
            </a>
            <span class="navbar-text text-white">Ficha Técnica Individual</span>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="card card-ficha shadow-sm mb-4">
                    <div class="card-body bg-white">
                        <form action="{{ route('ficha.index') }}" method="GET" class="row g-3 align-items-center">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="fas fa-search text-muted"></i></span>
                                    <input type="text" name="search" class="form-control bg-light border-start-0"
                                        placeholder="Pesquisar por Nº de BI ou Nome do Paciente..."
                                        value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success w-100 fw-bold">LOCALIZAR PACIENTE</button>
                            </div>
                        </form>
                    </div>
                </div>

                @if(isset($paciente) && $paciente)
                    <div class="card card-ficha shadow-lg mb-4">
                        <div class="card-header header-ficha p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="mb-1 fw-bold text-uppercase">{{ $paciente->paciente_nome }}</h2>
                                    <span class="badge bg-success">Paciente Registado</span>
                                    <span class="ms-2 text-muted"><i class="fas fa-id-card me-1"></i> BI:
                                        {{ $paciente->paciente_bi }}</span>
                                </div>
                                <div class="text-end d-none d-md-block">
                                    <i class="fas fa-user-circle fa-4x text-secondary"></i>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4 bg-white">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <h6 class="section-title"><i class="fas fa-user me-2"></i>Dados Pessoais</h6>
                                            <p class="info-label">Data de Nascimento</p>
                                            <p class="info-value">
                                                {{ \Carbon\Carbon::parse($paciente->paciente_nascimento)->format('d/m/Y') }}
                                            </p>
                                            <p class="info-label mt-2">Gênero</p>
                                            <p class="info-value">{{ $paciente->genero ?? 'Não informado' }}</p>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <h6 class="section-title"><i class="fas fa-map-marker-alt me-2"></i>Residência
                                            </h6>
                                            <p class="info-label">Localidade</p>
                                            <p class="info-value">{{ $paciente->municipio }} - {{ $paciente->bairro }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <h6 class="section-title text-center">Identificador QR</h6>
                                    <div class="qr-container shadow-sm p-3 bg-white border rounded text-center">
                                        @php
                                            // Geramos o link completo da ficha
                                            $linkFicha = route('ficha.index', ['search' => $paciente->paciente_bi]);

                                            // Montamos a URL da API de forma limpa
                                            $googleChartApi = "https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=" . urlencode($linkFicha) . "&choe=UTF-8";
                                        @endphp

                                        {{-- Testamos se a imagem carrega, se não, mostramos um ícone de fallback --}}
                                        <img src="{{ $googleChartApi }}" alt="QR Code do Paciente"
                                            style="max-width: 180px; height: auto;"
                                            onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($linkFicha) }}'">

                                        <p class="mt-2 mb-0 small text-muted text-uppercase fw-bold">
                                            <i class="fas fa-qrcode me-1"></i> Validar Identidade
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <h6 class="section-title mt-2"><i class="fas fa-history me-2"></i>Histórico Epidemiológico</h6>
                            <div class="table-responsive">
                                <table class="table table-hover border">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Data</th>
                                            <th>Doença</th>
                                            <th>Localidade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($historico as $h)
                                            <tr>
                                                <td>{{ $h->created_at->format('d/m/Y') }}</td>
                                                <td><span
                                                        class="badge badge-doenca text-dark">{{ $h->doenca->nome_doenca ?? 'N/A' }}</span>
                                                </td>
                                                <td>{{ $h->municipio }} ({{ $h->bairro }})</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer bg-white p-4 border-top d-flex justify-content-between">
                            <button class="btn btn-outline-secondary" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>Imprimir Ficha com QR
                            </button>
                            <a href="{{ route('casos.create', ['bi' => $paciente->paciente_bi]) }}"
                                class="btn btn-success px-4 fw-bold shadow-sm">
                                <i class="fas fa-plus me-2"></i>NOVO DIAGNÓSTICO
                            </a>
                        </div>
                    </div>

                @elseif(request('search'))
                    <div class="card card-ficha shadow-sm py-5 text-center bg-white">
                        <div class="card-body text-muted">
                            <i class="fas fa-user-slash fa-4x mb-3"></i>
                            <h4 class="fw-bold">Nenhum registo encontrado</h4>
                            <p>Tente outro número de BI ou Nome.</p>
                        </div>
                    </div>
                @else
                    <div class="empty-state bg-white rounded-3 shadow-sm border">
                        <i class="fas fa-hospital-user fa-5x text-light mb-3"></i>
                        <h4 class="text-muted">Pronto para Consulta</h4>
                        <p>Pesquise o paciente para ver o seu histórico e código de validação.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>