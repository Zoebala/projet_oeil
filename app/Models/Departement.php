<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departement extends Model
{
    use HasFactory;

    protected $fillable=[
        "lib","description","section_id"
    ];


    public function section()
    {
        return $this->BelongsTo(Section::class);
    }

    public function classes():HasMany
    {
        return $this->HasMany(Classe::class);
    }
}
