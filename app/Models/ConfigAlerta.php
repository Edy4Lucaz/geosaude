<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigAlerta extends Model
{
    protected $table = 'config_alertas';
    protected $fillable = ['doenca_id', 'alerta_ativo', 'modo', 'threshold_surto', 'threshold_epidemia', 'threshold_pandemia'];

    public function doenca()
    {
        return $this->belongsTo(Doenca::class, 'doenca_id');
    }
}