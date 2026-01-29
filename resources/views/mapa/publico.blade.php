<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Radar GeoSaúde - Angola</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #map { height: 550px; width: 100%; border-radius: 15px; border: 2px solid #198754; }
        .alerta-dinamico { display: none; color: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; text-align: center; font-weight: bold; }
        .status-endemia { background-color: #198754; border: 4px solid #0f5132; }
        .status-surto { background-color: #ffc107; color: #000 !important; border: 4px solid #997404; }
        .status-epidemia { background-color: #fd7e14; border: 4px solid #8a450b; }
        .status-pandemia { background-color: #dc3545; border: 4px solid #ffc107; animation: blink 1.5s infinite; }

        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        .label-contagem { background: none !important; border: none !important; color: #8b0000; font-weight: 900; font-size: 13px; text-shadow: 1px 1px 0px white; }
        .chart-box { background: white; padding: 15px; border-radius: 15px; border: 1px solid #ddd; height: 300px; margin-top: 20px; }
    </style>
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow mb-4">
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
                        <a class="nav-link text-white opacity-75" href="{{ route('noticias.index') }}">Notícias</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-4">Login</a>
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

    <div class="container">
        <div id="bannerAlerta" class="alerta-dinamico shadow">
            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i><br>
            <span id="labelGravidade"></span>: <span id="doencaAlerta"></span>
        </div>

        <div class="row mb-4 bg-white p-3 rounded shadow-sm border mx-0">
            <div class="col-md-3">
                <label class="fw-bold small">PROVÍNCIA</label>
                <select id="selectProvincia" class="form-select border-success">
                    <option value="angola">Angola (Geral)</option>
                    @foreach(['Bengo', 'Benguela', 'Bié', 'Cabinda', 'Cuando Cubango', 'Cuanza Norte', 'Cuanza Sul', 'Cunene', 'Huambo', 'Huíla', 'Luanda', 'Lunda Norte', 'Lunda Sul', 'Malanje', 'Moxico', 'Namibe', 'Uíge', 'Zaire'] as $p)
                        <option value="{{ strtolower(str_replace(' ', '_', $p)) }}">{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="fw-bold small">DOENÇA</label>
                <select id="selectDoenca" class="form-select border-success">
                    <option value="">-- Selecione --</option>
                    @foreach(\App\Models\Doenca::all() as $d)
                        <option value="{{ $d->nome_doenca }}" data-id="{{ $d->id }}">{{ $d->nome_doenca }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><label class="fw-bold small">DESDE</label><input type="date" id="dataInicio" class="form-control border-success"></div>
            <div class="col-md-2"><label class="fw-bold small">ATÉ</label><input type="date" id="dataFim" class="form-control border-success"></div>
            <div class="col-md-2 d-flex align-items-end"><button onclick="carregarAnaliseReal()" class="btn btn-success w-100 fw-bold">ANALISAR</button></div>
        </div>

        <div id="map" class="shadow"></div>
        <div class="chart-box shadow-sm mb-5"><canvas id="graficoEvolucao"></canvas></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://leaflet.github.io/Leaflet.heat/dist/leaflet-heat.js"></script>

    <script>
        const configAlertas = @json(\App\Models\ConfigAlerta::all()->keyBy('doenca_id'));
        const coords = {
            'angola': [-11.20, 17.87, 6], 'bengo': [-8.58, 13.83, 10], 'benguela': [-12.57, 13.40, 10],
            'bie': [-12.38, 17.30, 10], 'cabinda': [-5.55, 12.20, 11], 'cuando_cubango': [-14.65, 19.33, 9],
            'cuanza_norte': [-9.11, 14.91, 10], 'cuanza_sul': [-10.73, 14.33, 10], 'cunene': [-16.50, 15.50, 9],
            'huambo': [-12.77, 15.73, 10], 'huila': [-14.91, 13.49, 10], 'luanda': [-8.83, 13.23, 11],
            'lunda_norte': [-8.30, 19.00, 9], 'lunda_sul': [-10.33, 20.33, 9], 'malanje': [-9.54, 16.34, 10],
            'moxico': [-13.45, 20.33, 9], 'namibe': [-15.18, 12.15, 10], 'uige': [-7.61, 15.05, 10], 'zaire': [-6.26, 13.50, 10]
        };

        var map = L.map('map').setView(coords.angola, coords.angola[2]);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        let heatLayer, markerGroup = L.layerGroup().addTo(map), chartInstance = null;

        async function carregarAnaliseReal() {
            const selectD = document.getElementById('selectDoenca');
            const doencaNome = selectD.value;
            const doencaId = selectD.options[selectD.selectedIndex].getAttribute('data-id');
            const prov = document.getElementById('selectProvincia').value;
            const dDe = document.getElementById('dataInicio').value;
            const dAte = document.getElementById('dataFim').value;

            if (!doencaNome) return alert("Por favor, selecione uma doença.");

            if (heatLayer) map.removeLayer(heatLayer);
            markerGroup.clearLayers();
            document.getElementById('bannerAlerta').style.display = 'none';

            try {
                const response = await fetch("{{ route('api.casos.dados') }}");
                const dados = await response.json();

                let filtrados = dados.filter(c => c.doenca.nome_doenca === doencaNome);
                if (dDe) filtrados = filtrados.filter(c => c.created_at >= dDe);
                if (dAte) filtrados = filtrados.filter(c => c.created_at <= dAte);

                if (filtrados.length === 0) return alert("Nenhum caso encontrado.");

                // 1. Heatmap
                const heatPoints = filtrados.map(caso => [parseFloat(caso.latitude), parseFloat(caso.longitude), 0.8]);
                heatLayer = L.heatLayer(heatPoints, {
                    radius: 35, blur: 15, maxZoom: 10,
                    gradient: { 0.4: 'blue', 0.6: 'lime', 1: 'red' }
                }).addTo(map);

                // 2. Lógica Alertas Admin
                const conf = configAlertas[doencaId];
                if (conf && conf.alerta_ativo == 1) {
                    let layout = { txt: "CONTROLO ENDÉMICO", cls: "status-endemia" };
                    if (conf.nivel_atual === 'surto') layout = { txt: "ALERTA DE SURTO", cls: "status-surto" };
                    if (conf.nivel_atual === 'epidemia') layout = { txt: "ALERTA DE EPIDEMIA", cls: "status-epidemia" };
                    if (conf.nivel_atual === 'pandemia') layout = { txt: "ALERTA DE PANDEMIA", cls: "status-pandemia" };

                    const banner = document.getElementById('bannerAlerta');
                    banner.className = `alerta-dinamico shadow ${layout.cls}`;
                    document.getElementById('labelGravidade').innerText = layout.txt;
                    document.getElementById('doencaAlerta').innerText = doencaNome.toUpperCase();
                    banner.style.display = 'block';
                }

                if (coords[prov]) map.flyTo([coords[prov][0], coords[prov][1]], coords[prov][2]);

                // 3. Marcadores
                const datasGrafico = {};
                filtrados.forEach(caso => {
                    const lat = parseFloat(caso.latitude);
                    const lng = parseFloat(caso.longitude);
                    const data = caso.created_at.split('T')[0];
                    datasGrafico[data] = (datasGrafico[data] || 0) + 1;

                    L.circleMarker([lat, lng], {
                        radius: 12, color: '#8b0000', fillColor: '#ffcccc', fillOpacity: 0.5, weight: 1
                    }).addTo(markerGroup).bindTooltip(`<b>1</b>`, { permanent: true, direction: 'center', className: 'label-contagem' });
                });

                const labels = Object.keys(datasGrafico).sort();
                if (chartInstance) chartInstance.destroy();
                chartInstance = new Chart(document.getElementById('graficoEvolucao').getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{ label: 'Casos', data: labels.map(l => datasGrafico[l]), borderColor: '#198754', fill: true }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });

            } catch (error) { console.error(error); }
        }
    </script>
</body>
</html>