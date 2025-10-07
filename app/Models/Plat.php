<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plat extends Model
{
    use HasFactory;

    protected $table = 'plats';

    protected $fillable = [
        'categorie_id',
        'nom',
        'description',
        'prix',
        'image_url',
        'actif'
    ];

    // Relation avec catégorie
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }
}
