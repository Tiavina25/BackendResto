<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        return response()->json(Categorie::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|unique:categories,nom'
        ]);

        $categorie = Categorie::create($validated);
        return response()->json($categorie, 201);
    }

    public function update(Request $request, $id)
    {
        $categorie = Categorie::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|unique:categories,nom,' . $categorie->id
        ]);

        $categorie->update($validated);
        return response()->json($categorie);
    }

    public function destroy($id)
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->delete(); // cascade supprime automatiquement les plats liés
        return response()->json(['message' => 'Catégorie supprimée avec succès']);
    }
}
