<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\NouvelleCommandeEvent;

class CommandeController extends Controller
{
    // Afficher les détails d'une commande pour l'employé
    public function show($id)
    {
        $commande = Commande::with('lignes.plat')->find($id);

        if (!$commande) {
            return response()->json(['message' => 'Commande non trouvée'], 404);
        }

        // Récupérer le numéro de la table
        $table = DB::table('tables_restaurant')
            ->where('id', $commande->table_id)
            ->first();

        $commande->table_numero = $table ? $table->numero : null;

        return response()->json($commande);
    }

    // Marquer la commande comme payée (TERMINE) et mettre à jour la notification existante
    public function payer($id)
    {
        try {
            $commande = Commande::find($id);
            if (!$commande) {
                return response()->json(['message' => 'Commande non trouvée'], 404);
            }

            $commande->statut = 'TERMINE';
            $commande->save();

            DB::table('notifications')
                ->where('commande_id', $id)
                ->update(['message' => 'Paiement OK', 'updated_at' => now()]);

            return response()->json(['message' => 'Paiement validé']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur paiement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // --- Nouvelle méthode pour confirmer la commande côté frontoffice (client) ---
    public function confirmerCommande($id)
    {
        $commande = Commande::findOrFail($id);
        $commande->statut = 'CONFIRME';
        $commande->save();

        // Créer la notification pour l'employé
        $notif = Notification::create([
            'commande_id' => $commande->id,
            'lu' => false,
            'message' => "Nouvelle commande pour la table {$commande->table_id}"
        ]);

        // Émettre l'événement en temps réel pour Vue.js côté Backoffice
        event(new NouvelleCommandeEvent($commande));

        return response()->json([
            'success' => true,
            'commande_id' => $commande->id
        ]);
    }
}
