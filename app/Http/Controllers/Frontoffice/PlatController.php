<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plat;
use App\Models\Categorie;

class PlatController extends Controller
{
    // Récupérer tous les plats côté client (y compris inactifs)
    public function index()
    {
        $plats = Plat::with('categorie')->get();
        return response()->json($plats);
    }

    // Récupérer tous les plats actifs uniquement pour les clients
    public function indexClient()
    {
        $plats = Plat::with('categorie')->where('actif', 1)->get();
        return response()->json([
            'success' => true,
            'plats' => $plats
        ]);
    }

    // Récupérer un plat par ID
    public function show($id)
    {
        $plat = Plat::with('categorie')->find($id);
        if (!$plat) {
            return response()->json(['message' => 'Plat non trouvé'], 404);
        }
        return response()->json($plat);
    }

    // Récupérer toutes les catégories
    public function categories()
    {
        $categories = Categorie::all();
        return response()->json($categories);
    }

    // Récupérer tous les plats par catégorie
    public function platsByCategorie($categorieId)
    {
        $plats = Plat::with('categorie')
            ->where('categorie_id', $categorieId)
            ->where('actif', 1) // uniquement actifs
            ->get();
        return response()->json($plats);
    }
}
