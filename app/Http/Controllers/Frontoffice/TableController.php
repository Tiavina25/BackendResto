<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\TableRestaurant;

class TableController extends Controller
{
    // Récupérer toutes les tables pour le client
    public function index()
    {
        return response()->json(TableRestaurant::all());
    }
}
