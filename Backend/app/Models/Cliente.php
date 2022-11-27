<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'cnpj', 'data_fundacao', 'grupo_id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
}
