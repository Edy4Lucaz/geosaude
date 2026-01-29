<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatório Epidemiológico - GeoSaúde Angola</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; color: #333; line-height: 1.5; margin: 20px; }
        .header { text-align: center; border-bottom: 3px solid #146c43; padding-bottom: 10px; margin-bottom: 20px; }
        .row { display: table; width: 100%; margin-bottom: 30px; }
        .chart-container { display: table-cell; width: 48%; border: 1px solid #eee; padding: 10px; border-radius: 8px; vertical-align: top; }
        .chart-title { font-weight: bold; margin-bottom: 15px; color: #146c43; text-transform: uppercase; font-size: 12px; }
        .bar-label { font-size: 11px; margin-bottom: 5px; }
        .bar-bg { background: #e9ecef; height: 12px; border-radius: 6px; overflow: hidden; margin-bottom: 10px; }
        .bar-fill { background: #198754; height: 100%; border-radius: 6px; }
        .map-table { width: 100%; border-collapse: collapse; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 10px; }
        th, td { border: 1px solid #dee2e6; padding: 8px; text-align: left; }
        th { background-color: #146c43; color: white; }
        .anonimo { font-family: monospace; color: #555; }
        /* Forçar quebra de página se necessário */
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">GEOSAÚDE ANGOLA</h2>
        <p style="margin:0; font-size: 12px;">Relatório Administrativo de Vigilância Sanitária</p>
        <small>Gerado em: {{ date('d/m/Y H:i') }} | Total de Casos: {{ $stats['total_geral'] }}</small>
    </div>

    <div class="row">
        <div class="chart-container" style="padding-right: 10px;">
            <div class="chart-title">Distribuição por Patologia</div>
            @foreach($stats['por_doenca'] as $doenca => $total)
                @php $percent = ($stats['total_geral'] > 0) ? ($total / $stats['total_geral']) * 100 : 0; @endphp
                <div class="bar-label">{{ $doenca }} ({{ $total }})</div>
                <div class="bar-bg"><div class="bar-fill" style="width: {{ $percent }}%;"></div></div>
            @endforeach
        </div>

        <div class="chart-container">
            <div class="chart-title">Mapa de Calor (Surtos)</div>
            <table class="map-table">
                <thead>
                    <tr><th>Província</th><th>Casos</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach($stats['por_provincia'] as $provincia => $total)
                        @php
                            $style = ''; $status = 'ESTÁVEL';
                            if ($total >= 10) { $style = 'background: #ff4d4d; color: white;'; $status = 'SURTO CRÍTICO'; }
                            elseif ($total >= 5) { $style = 'background: #ffa500;'; $status = 'ALERTA'; }
                        @endphp
                        <tr style="{{ $style }}">
                            <td>{{ $provincia }}</td>
                            <td>{{ $total }}</td>
                            <td><strong>{{ $status }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <h4 class="chart-title">Listagem Anonimizada de Registos</h4>
    <table>
        <thead>
            <tr>
                <th>Paciente</th>
                <th>BI</th>
                <th>Doença</th>
                <th>Localidade</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dados as $item)
                <tr>
                    <td class="anonimo"><strong>{{ $item['paciente'] }}</strong></td>
                    <td>{{ $item['bi'] }}</td>
                    <td>{{ $item['doenca'] }}</td>
                    <td>{{ $item['provincia'] }} ({{ $item['municipio'] }})</td>
                    <td>{{ $item['data'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px; font-size: 9px; color: #999;">
        <p>Aviso: Este documento contém dados anonimizados para fins estatísticos e proteção de privacidade.</p>
    </div>
</body>
</html>