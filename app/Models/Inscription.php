<?php

namespace App\Models;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Etudiant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable=[
        "actif","annee_id","classe_id","etudiant_id",
    ];

    public function annee()
    {
        return $this->BelongsTo(Annee::class);
    }
    public function classe()
    {
        return $this->BelongsTo(Classe::class);
    }
    public function etudiant()
    {
        return $this->BelongsTo(Etudiant::class);
    }
}
