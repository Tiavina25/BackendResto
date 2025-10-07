<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PlatController extends Controller
{
    public function index()
    {
        return response()->json(Plat::with('categorie')->get());
    }

    public function show($id)
    {
        try {
            $plat = Plat::with('categorie')->findOrFail($id);
            return response()->json($plat);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Plat non trouvé'], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'categorie_id' => 'required|integer|exists:categories,id',
            'nom' => 'required|string|max:150',
            'description' => 'nullable|string',
            'prix' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('plats', 'public') : null;

        $plat = Plat::create([
            'categorie_id' => $request->categorie_id,
            'nom' => $request->nom,
            'description' => $request->description,
            'prix' => $request->prix,
            'image_url' => $imagePath,
            'actif' => 1
        ]);

        return response()->json(Plat::with('categorie')->find($plat->id));
    }

    public function update(Request $request, $id)
    {
        $plat = Plat::findOrFail($id);

        $request->validate([
            'categorie_id' => 'nullable|integer|exists:categories,id',
            'nom' => 'nullable|string|max:150',
            'description' => 'nullable|string',
            'prix' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        if ($request->hasFile('image')) {
            if ($plat->image_url && Storage::disk('public')->exists($plat->image_url)) {
                Storage::disk('public')->delete($plat->image_url);
            }
            $plat->image_url = $request->file('image')->store('plats', 'public');
        }

        $plat->update($request->only(['categorie_id','nom','description','prix']));

        return response()->json(Plat::with('categorie')->find($id));
    }

    public function destroy($id)
    {
        $plat = Plat::findOrFail($id);

        if ($plat->image_url && Storage::disk('public')->exists($plat->image_url)) {
            Storage::disk('public')->delete($plat->image_url);
        }

        $plat->delete();
        return response()->json(['message' => 'Plat supprimé avec succès']);
    }

    public function toggleActif($id)
    {
        $plat = Plat::findOrFail($id);
        $plat->actif = !$plat->actif;
        $plat->save();
        return response()->json(['actif' => $plat->actif]);
    }
}
