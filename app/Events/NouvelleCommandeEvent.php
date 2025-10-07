<?php

namespace App\Events;

use App\Models\Commande;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NouvelleCommandeEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $commande;

    /**
     * Crée une nouvelle instance de l’événement.
     */
    public function __construct(Commande $commande)
    {
        $this->commande = $commande;
    }

    /**
     * Canal public : tous les employés reçoivent la notif.
     */
    public function broadcastOn()
    {
        return new Channel('employes');
    }

    /**
     * Nom de l’événement côté frontend.
     */
    public function broadcastAs()
    {
        return 'NouvelleCommandeEvent';
    }

    /**
     * Données envoyées au frontend.
     */
    public function broadcastWith()
    {
        return [
            'id' => $this->commande->id,
            'message' => 'Nouvelle commande #' . $this->commande->id,
            'table_numero' => $this->commande->table_id ? $this->commande->table->numero : null,
            'commande_date' => $this->commande->created_at->format('Y-m-d H:i:s'),
            'commande' => [
                'id' => $this->commande->id,
                'statut' => $this->commande->statut,
                'type_commande' => $this->commande->type_commande,
                'total' => $this->commande->total,
                'lignes' => $this->commande->lignes,
            ],
        ];
    }
}
