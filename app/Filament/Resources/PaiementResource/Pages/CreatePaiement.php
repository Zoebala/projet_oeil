<?php

namespace App\Filament\Resources\PaiementResource\Pages;

use App\Models\Frais;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PaiementResource;

class CreatePaiement extends CreateRecord
{
    protected static string $resource = PaiementResource::class;

    protected function mutateFormDataBeforeCreate(array $data):array
    {
        if($data['devise']=="USD"){
            $Frais=Frais::query()->whereClasse_id($data["classe_id"])->first();

            $data["montant"]=$data["montant"]*$Frais->taux;
            $data["devise"]="CDF";

        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Payement effectué avec succès!';
    }
}
