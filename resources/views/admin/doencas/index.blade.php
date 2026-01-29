<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoSaúde - Configurar Doenças</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .navbar { background-color: #146c43 !important; }
        .table-container { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .disease-badge { background-color: #e8f5e9; color: #2e7d32; padding: 5px 12px; border-radius: 20px; font-weight: bold; }
        .modal-content { border: none; border-radius: 15px; }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-arrow-left me-2"></i> Painel Admin
            </a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark">Gestão de Patologias</h2>
                <p class="text-muted">Defina as doenças que os médicos poderão reportar no sistema.</p>
            </div>
            <button class="btn btn-primary shadow-sm px-4 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modalNovaDoenca">
                <i class="fas fa-plus-circle me-2"></i> Configurar Nova Doença
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="table-container">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 20%;">Doença</th>
                        <th style="width: 30%;">Sintomas Comuns</th>
                        <th style="width: 30%;">Medidas de Prevenção</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doencas as $doenca)
                    <tr>
                        <td><span class="disease-badge">{{ $doenca->nome_doenca }}</span></td>
                        <td><small class="text-muted">{{ Str::limit($doenca->sintomas, 60) }}</small></td>
                        <td><small class="text-muted">{{ Str::limit($doenca->prevencao, 60) }}</small></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-outline-warning rounded-pill px-3" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEditar{{ $doenca->id }}">
                                    <i class="fas fa-edit me-1"></i> Editar
                                </button>

                                <form action="{{ route('admin.doencas.destroy', $doenca->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover esta patologia?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="fas fa-trash me-1"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalEditar{{ $doenca->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content shadow">
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title fw-bold text-dark">Editar Patologia</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.doencas.update', $doenca->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body p-4 text-start">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-muted">Nome da Doença</label>
                                            <input type="text" name="nome_doenca" class="form-control" value="{{ $doenca->nome_doenca }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-muted">Sintomas</label>
                                            <textarea name="sintomas" class="form-control" rows="3" required>{{ $doenca->sintomas }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-muted">Medidas de Prevenção</label>
                                            <textarea name="prevencao" class="form-control" rows="3" required>{{ $doenca->prevencao }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-warning px-4 fw-bold text-dark">Guardar Alterações</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Nenhuma doença configurada até ao momento.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalNovaDoenca" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">Configurar Nova Patologia</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.doencas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Nome da Doença</label>
                            <input type="text" name="nome_doenca" class="form-control" placeholder="Ex: Malária, Cólera, Dengue" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Sintomas (Separe por vírgulas)</label>
                            <textarea name="sintomas" class="form-control" rows="3" placeholder="Ex: Febre alta, calafrios, dores de cabeça..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Medidas de Prevenção</label>
                            <textarea name="prevencao" class="form-control" rows="3" placeholder="Ex: Uso de mosquiteiros, eliminar águas paradas..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">Gravar Configuração</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>