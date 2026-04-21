<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant un vin.
 */
class Vin extends Model
{
    use HasFactory;
    // attributs pouvant être assignés en masse
    protected $fillable = [
        'sku',
        'nom',
        'prix',
        'pays',
        'region',
        'cepage',
        'degre_alcool',
        'taux_sucre',
        'format',
        'annee',
        'image_url',
        'couleur'
    ];

    // ajouter la relation avec CellierVin
    public function cellierVins()
    {
        return $this->hasMany(CellierVin::class);
    }

    // ajouter la relation avec ListeAchat
    public function listeAchats()
    {
        return $this->hasMany(ListeAchat::class);
    }
}
