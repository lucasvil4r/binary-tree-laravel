<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'referrer_id', 'left_child_id', 'right_child_id', 'points'];

    // Relacionamento com o referenciador (usuário que indicou)
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    // Relacionamento com o filho à esquerda
    public function leftChild()
    {
        return $this->belongsTo(User::class, 'left_child_id');
    }

    // Relacionamento com o filho à direita
    public function rightChild()
    {
        return $this->belongsTo(User::class, 'right_child_id');
    }

    // Relacionamento com os pontos (pode ser histórico se usar tabela `user_points`)
    public function points()
    {
        return $this->hasMany(UserPoint::class);
    }
}
