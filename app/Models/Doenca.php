<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doenca extends Model
{
    protected $table = 'doencas';

    // Ajustado para os nomes das colunas da tua migração
    protected $fillable = ['nome_doenca', 'sintomas', 'prevencao'];

    /**
     * Relacionamento: Uma doença pode ter vários casos registados.
     */
    public function casos(): HasMany
    {
        return $this->hasMany(Caso::class, 'doenca_id');
    }
}