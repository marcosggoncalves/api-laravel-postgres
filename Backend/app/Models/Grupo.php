<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    protected $hidden = ['created_at', 'updated_at'];

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
}
