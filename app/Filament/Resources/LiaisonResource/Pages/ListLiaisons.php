<?php

namespace App\Filament\Resources\LiaisonResource\Pages;

use Filament\Actions;
use App\Models\Liaison;
use App\Models\Etudiant;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\LiaisonResource;

class ListLiaisons extends ListRecords
{
    protected static string $resource = LiaisonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label("S'associer à un étudiant")
            ->hidden(fn()=> Liaison::whereUser_id(Auth()->user()->id)->exists() || Etudiant::whereUser_id(Auth()->user()->id)->exists()),
        ];
    }
}
