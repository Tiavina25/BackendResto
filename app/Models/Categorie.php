<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['nom'];

    // Relation avec les plats
    public function plats()
    {
        return $this->hasMany(Plat::class, 'categorie_id');
    }
}
