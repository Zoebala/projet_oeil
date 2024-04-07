<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\Paiement;
use App\Models\Inscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Etudiant extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function classe()
    {
        return $this->BelongsTo(Classe::class);
    }

    public function paiements()
    {
        return $this->HasMany(Paiement::class);
    }

    public function inscriptions()
    {
        return $this->HasMany(Inscription::class);
    }
}
