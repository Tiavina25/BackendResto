<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CommandeLigne extends Model {
    protected $fillable = ['commande_id','plat_id','quantite','prix'];

    public function plat() {
        return $this->belongsTo(Plat::class);
    }
}
