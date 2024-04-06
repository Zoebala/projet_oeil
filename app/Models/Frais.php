<?php

namespace App\Models;

use App\Models\Annee;
use App\Models\Classe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Frais extends Model
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


}
