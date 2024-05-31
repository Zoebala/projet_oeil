<?php

namespace App\Filament\Resources\EtudiantResource\Pages;

use App\Filament\Resources\EtudiantResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

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
