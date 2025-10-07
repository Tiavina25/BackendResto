<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HistoriqueController extends Controller
{
    public function filter(Request $request)
    {
        $dateDebut = $request->query('date_debut', null);
        $dateFin = $request->query('date_fin', null);
        $periode = $request->query('periode', null);

        // Récupérer les commandes avec filtre date, dateDebut/dateFin ou période
        $commandesQuery = DB::table('commandes')->orderBy('created_at', 'desc');

        if ($periode) {
            switch ($periode) {
                case 'aujourdhui':
                    $commandesQuery->whereDate('created_at', Carbon::today());
                    break;
                case 'cette_semaine':
                    $commandesQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'ce_mois':
                    $commandesQuery->whereMonth('created_at', Carbon::now()->month)
                                   ->whereYear('created_at', Carbon::now()->year);
                    break;
            }
        } elseif ($dateDebut && $dateFin) {
            // Filtrer entre dateDebut et dateFin
            $commandesQuery->whereDate('created_at', '>=', $dateDebut)
                           ->whereDate('created_at', '<=', $dateFin);
        } elseif ($dateDebut) {
            // Si seulement dateDebut est fournie
            $commandesQuery->whereDate('created_at', $dateDebut);
        }

        $commandes = $commandesQuery->get();

        // Détails des commandes
        $commandes_details = [];
        $total_plats_global = 0;

        foreach ($commandes as $commande) {
            $total_qtt = DB::table('commande_lignes')
                ->where('commande_id', $commande->id)
                ->sum('quantite');

            $total_plats_global += $total_qtt;

            $commandes_details[] = [
                'id' => $commande->id,
                'created_at' => $commande->created_at,
                'total' => $commande->total,
                'total_qtt' => $total_qtt
            ];
        }

        // Chiffre d'affaires global
        $globalQuery = DB::table('commandes');

        if ($periode) {
            switch ($periode) {
                case 'aujourdhui':
                    $globalQuery->whereDate('created_at', Carbon::today());
                    break;
                case 'cette_semaine':
                    $globalQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'ce_mois':
                    $globalQuery->whereMonth('created_at', Carbon::now()->month)
                                ->whereYear('created_at', Carbon::now()->year);
                    break;
            }
        } elseif ($dateDebut && $dateFin) {
            $globalQuery->whereDate('created_at', '>=', $dateDebut)
                        ->whereDate('created_at', '<=', $dateFin);
        } elseif ($dateDebut) {
            $globalQuery->whereDate('created_at', $dateDebut);
        }

        $global = $globalQuery->selectRaw('COUNT(*) as total_commandes, SUM(total) as chiffre_affaires')->first();
        $global->total_plats = $total_plats_global;

        if (!count($commandes_details)) {
            $global = (object)[
                'total_commandes' => 0,
                'chiffre_affaires' => 0,
                'total_plats' => 0
            ];
        }

        return response()->json([
            'global' => $global,
            'commandes_details' => $commandes_details
        ]);
    }
}
