<?php

namespace App\Filament\Resources\EtudiantResource\Pages;

use Filament\Actions;
use App\Models\Liaison;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\EtudiantResource;

class CreateEtudiant extends CreateRecord
{
    protected static string $resource = EtudiantResource::class;

    protected function mutateFormDataBeforeCreate(array $data):array
    {

        $data["user_id"]=Auth()->user()->id;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
