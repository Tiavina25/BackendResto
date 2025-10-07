<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableRestaurant extends Model
{
    use HasFactory;

    protected $table = 'tables_restaurant';

    protected $fillable = [
        'numero',
        'created_at'
    ];

    public $timestamps = false; // car on utilise created_at manuellement
}
