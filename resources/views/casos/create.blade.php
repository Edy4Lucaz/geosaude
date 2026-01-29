<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoSaúde - Registo de Caso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Overlay para o médico saber que o sistema está a processar o mapa */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            flex-direction: column;

        }

        .navbar {
            background-color: #146c43 !important;
        }
    </style>
</head>

<body class="bg-light">


    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('medico.index') }}">
                <i class="fas fa-chevron-left me-2"></i> Voltar ao Painel
            </a>
            <span class="navbar-text text-white">Registo de Casos</span>
        </div>
    </nav>

    <div id="loader" class="loading-overlay">
        <div class="spinner-border text-success" style="width: 3rem; height: 3rem;" role="status"></div>
        <span class="mt-3 fw-bold text-dark">A processar geolocalização em Angola...</span>
    </div>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white p-3">
                        <h4 class="mb-0">Médico: Registar Novo Caso (CU-04)</h4>
                    </div>
                    <div class="card-body p-4">

                        @if ($errors->any())
                            <div class="alert alert-danger shadow-sm">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success shadow-sm border-0">
                                <strong>Sucesso!</strong> {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('casos.store') }}" method="POST" id="formCaso">
                            @csrf

                            <h5 class="text-secondary border-bottom pb-2 mb-3">Dados do Paciente</h5>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nome Completo</label>
                                <input type="text" name="paciente_nome" class="form-control"
                                    placeholder="Ex: João Manuel" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Bilhete de Identidade (BI)</label>
                                    <input type="text" name="paciente_bi" class="form-control"
                                        placeholder="000000000LA000" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Data de Nascimento</label>
                                    <input type="date" name="paciente_nascimento" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Doença Diagnosticada (RF-02)</label>
                                <select name="doenca_id" class="form-control form-select" required>
                                    <option value="">Selecione a patologia...</option>
                                    @foreach($doencas as $doenca)
                                        <option value="{{ $doenca->id }}">{{ $doenca->nome_doenca }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <h5 class="text-primary border-bottom pb-2 mb-3">Localização do Surto</h5>
                            <div class="row bg-white p-3 rounded mb-4 border shadow-sm">
                                <div class="col-md-4 mb-2">
                                    <label class="small fw-bold">Província</label>
                                    <input type="text" name="provincia" class="form-control" placeholder="Ex: Huíla"
                                        required>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="small fw-bold">Município</label>
                                    <input type="text" name="municipio" class="form-control" placeholder="Ex: Lubango"
                                        required>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="small fw-bold">Bairro</label>
                                    <input type="text" name="bairro" class="form-control" placeholder="Ex: Lucrécia"
                                        required>
                                </div>
                                <div class="col-12 mt-2">
                                    <small class="text-muted italic">* O sistema converterá estes nomes em coordenadas
                                        GPS automaticamente.</small>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg fw-bold shadow">
                                    Finalizar e Gerar Identificador Único (QR)
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Quando o formulário for submetido, mostramos o loader
        // O PHP tratará de buscar a latitude e longitude no servidor
        document.getElementById('formCaso').onsubmit = function () {
            document.getElementById('loader').style.display = 'flex';
            return true;
        };
    </script>

</body>

</html>