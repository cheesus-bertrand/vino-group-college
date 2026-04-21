<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant un cellierVin.
 */
class CellierVin extends Model
{
    use HasFactory;

    protected $table = 'cellier_vins';
    // attributs pouvant être assignés en masse
    protected $fillable = [
        'cellier_id',
        'vin_id',
        'quantite'
    ];

    // ajouter la relation avec Cellier
    public function cellier()
    {
        return $this->belongsTo(Cellier::class);
    }

    // ajouter la relation avec Vin
    public function vin()
    {
        return $this->belongsTo(Vin::class);
    }
}
