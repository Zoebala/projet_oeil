<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Paiement;
use App\Models\Inscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Etudiant extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $casts=[
        "files"=>"json",
    ];

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


    protected static function booted():void{
        
        static::deleted(function(Etudiant $etudiant){
            foreach($etudiant->files as $file){
                Storage::delete("public/dossiers/$file");
            }
        });

        static::updating(function(Etudiant $etudiant){

            $imagesToDelete=array_diff($etudiant->getOriginal("files"));
            foreach($imagesToDelete as $file){
                Storage::delete("public/dossiers/$file");
            }
        });
    }


}
