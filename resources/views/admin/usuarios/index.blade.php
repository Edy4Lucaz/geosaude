<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoSaúde - Gestão de Utilizadores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .navbar {
            background-color: #146c43 !important;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .badge-admin {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .badge-medico {
            background-color: #198754;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .modal-content {
            border: none;
            border-radius: 15px;
        }
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
                <h2 class="fw-bold">Gestão de Profissionais</h2>
                <p class="text-muted">Adicione ou remova médicos e administradores do sistema.</p>
            </div>
            <button class="btn btn-success shadow-sm px-4 py-2 fw-bold" data-bs-toggle="modal"
                data-bs-target="#modalNovoUsuario">
                <i class="fas fa-user-plus me-2"></i> Novo Utilizador
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ $errors->first() }}
            </div>
        @endif

        <div class="table-container">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Província</th>
                        <th>Cargo</th>
                        <th>Estado</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $user)
                        <tr>
                            <td class="fw-bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->provincia ?? 'N/A' }}</td>
                            <td>
                                @if($user->role == 1)
                                    <span class="badge-admin">Administrador</span>
                                @else
                                    <span class="badge-medico">Médico</span>
                                @endif
                            </td>
                            <td>
                                @if($user->trashed())
                                    <span class="text-danger small"><i class="fas fa-times-circle me-1"></i> Desativado</span>
                                @else
                                    <span class="text-success small"><i class="fas fa-check-circle me-1"></i> Ativo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$user->trashed())
                                    <form action="{{ route('admin.usuarios.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Tem a certeza que deseja remover este utilizador?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                            <i class="fas fa-trash-alt me-1"></i> Eliminar
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">Inativo</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalNovoUsuario" tabindex="-1" aria-labelledby="modalNovoUsuarioLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="modalNovoUsuarioLabel italic">Registar Profissional</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.usuarios.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Nome Completo</label>
                            <input type="text" name="name" class="form-control" placeholder="Ex: Dr. Silvano João"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">E-mail Profissional</label>
                            <input type="email" name="email" class="form-control" placeholder="exemplo@geosaude.it"
                                required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Cargo</label>
                                <select name="role" class="form-select" required>
                                    <option value="2">Médico</option>
                                    <option value="1">Administrador</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Província</label>
                                <input type="text" name="provincia" class="form-control" placeholder="Luanda" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Palavra-passe Inicial</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Mínimo 6 caracteres" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success px-4 fw-bold">Criar Conta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>