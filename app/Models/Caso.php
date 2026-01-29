<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caso extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_nome',
        'paciente_bi',
        'paciente_nascimento',
        'provincia',    // Adicionado
        'municipio',    // Adicionado
        'bairro',       // Adicionado
        'latitude',
        'longitude',
        'qr_code',
        'doenca_id',
        'user_id'
    ];

    /**
     * Relacionamento: Um caso pertence a uma Doença (CU-01)
     */
    public function doenca()
    {
        return $this->belongsTo(Doenca::class, 'doenca_id');
    }

    /**
     * Relacionamento: Um caso foi registado por um Utilizador/Médico (CU-04)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}