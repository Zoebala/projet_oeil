<?php

namespace App\Filament\Resources\InscriptionResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\InscriptionResource;

class CreateInscription extends CreateRecord
{
    protected static string $resource = InscriptionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

   

}
