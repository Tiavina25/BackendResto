<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\CommandeLigne;
use Illuminate\Support\Facades\DB;
use App\Events\NouvelleCommandeEvent;

class CommandeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'nullable|exists:tables_restaurant,id',
            'type_commande' => 'required|in:SUR_PLACE,A_EMPORTER',
            'lignes' => 'required|array|min:1',
            'lignes.*.plat_id' => 'required|exists:plats,id',
            'lignes.*.quantite' => 'required|integer|min:1',
            'lignes.*.prix' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $commande = Commande::create([
                'table_id' => $request->table_id,
                'type_commande' => $request->type_commande,
                'statut' => 'EN_ATTENTE',
                'total' => 0
            ]);

            $total = 0;
            foreach ($request->lignes as $ligne) {
                $ligneTotal = $ligne['quantite'] * $ligne['prix'];
                CommandeLigne::create([
                    'commande_id' => $commande->id,
                    'plat_id' => $ligne['plat_id'],
                    'quantite' => $ligne['quantite'],
                    'prix' => $ligne['prix']
                ]);
                $total += $ligneTotal;
            }

            $commande->total = $total;
            $commande->save();

            // Notification base
            DB::table('notifications')->insert([
                'commande_id' => $commande->id,
                'message' => 'Nouvelle commande #' . $commande->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Diffusion temps réel
            event(new NouvelleCommandeEvent($commande));

            DB::commit();

            return response()->json([
                'message' => 'Commande créée avec succès',
                'commande_id' => $commande->id
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erreur lors de la création de la commande',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTypesCommande()
    {
        return response()->json([
            ['type_commande' => 'SUR_PLACE'],
            ['type_commande' => 'A_EMPORTER']
        ]);
    }
}
