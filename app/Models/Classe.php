<?php

namespace App\Models;

use App\Models\Etudiant;
use App\Models\Departement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classe extends Model
{
    use HasFactory;

    protected $fillable=[
        "lib","description","departement_id",
    ];


    public function departement()
    {
        return $this->BelongsTo(Departement::class);
    }

    public function etudiants():HasMany
    {
        return $this->HasMany(Etudiant::class);
    }
}
