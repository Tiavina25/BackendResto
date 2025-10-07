<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model {
    protected $fillable = ['table_id','type_commande','statut','total'];

    public function lignes() {
        return $this->hasMany(CommandeLigne::class);
    }
}
