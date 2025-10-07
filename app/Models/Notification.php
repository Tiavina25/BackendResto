<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    // Colonnes que l'on peut remplir avec create()
    protected $fillable = [
        'commande_id',
        'lu',
        'message', // si tu veux mettre un message
    ];
}
