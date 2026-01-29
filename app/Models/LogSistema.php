<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogSistema extends Model
{
    // Dizemos ao Laravel o nome exato da tabela no MySQL
    protected $table = 'logs_sistema';

    protected $fillable = ['user_id', 'acao', 'detalhes', 'tipo'];

    /**
     * Relacionamento: Um log pertence a um utilizador.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}