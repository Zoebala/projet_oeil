<?php

namespace App\Models;

use App\Models\Annee;
use App\Models\Frais;
use App\Models\Classe;
use App\Models\Etudiant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paiement extends Model
{
    use HasFactory;


     protected $guarded=[];


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
    public function frais()
    {
        return $this->BelongsTo(Frais::class);
    }
}
