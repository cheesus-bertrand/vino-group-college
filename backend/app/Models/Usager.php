<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Modèle représentant un usager.
 */
class Usager extends Authenticatable
{
    use HasFactory;

    protected $table = 'usagers';
    // attributs pouvant être assignés en masse
    protected $fillable = [
        'nom',
        'courriel',
        'mot_de_passe',
    ];

    // masquer les attributs lors de la sérialisation du modèle
    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    // Retourne le mot de passe utilisé pour l'authentification.
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    // retourne le token de reconnexion automatique
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    // ajouter la relation avec Cellier
    public function celliers()
    {
        return $this->hasMany(Cellier::class, 'usager_id');
    }

    // ajouter la relation avec ListeAchat
    public function listeAchats()
    {
        return $this->hasMany(listeAchat::class, 'usager_id');
    }
}
