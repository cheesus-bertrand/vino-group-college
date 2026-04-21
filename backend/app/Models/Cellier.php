<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant un cellier.
 */
class Cellier extends Model
{
    use HasFactory;
    // attributs pouvant être assignés en masse
    protected $fillable = [
        'usager_id',
        'nom',
    ];

    // ajouter la relation avec Usager
    public function usager()
    {
        return $this->belongsTo(Usager::class);
    }

    // ajouter la relation avec CellierVin
    public function cellierVins()
    {
        return $this->hasMany(CellierVin::class);
    }
}
