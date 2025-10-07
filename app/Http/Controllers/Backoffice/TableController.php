<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\TableRestaurant;
use Illuminate\Http\Request;

class TableController extends Controller
{
    // Liste des tables
    public function index()
    {
        $tables = TableRestaurant::all();
        return response()->json($tables);
    }

    // Ajouter une table
    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|unique:tables_restaurant,numero'
        ]);

        $table = TableRestaurant::create([
            'numero' => $request->numero
        ]);

        return response()->json($table);
    }

    // Modifier une table
    public function update(Request $request, $id)
    {
        $table = TableRestaurant::findOrFail($id);

        $request->validate([
            'numero' => 'required|unique:tables_restaurant,numero,' . $id
        ]);

        $table->update([
            'numero' => $request->numero
        ]);

        return response()->json($table);
    }

    // Supprimer une table
    public function destroy($id)
    {
        $table = TableRestaurant::findOrFail($id);
        $table->delete();

        return response()->json(['message' => 'Table supprimée']);
    }
}
