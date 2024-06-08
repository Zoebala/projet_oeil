<?php

namespace App\Filament\Resources\LiaisonResource\Pages;

use Filament\Actions;
use App\Models\Etudiant;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\LiaisonResource;

class CreateLiaison extends CreateRecord
{
    protected static string $resource = LiaisonResource::class;

    protected function mutateFormDataBeforeCreate(array $data):array
    {

        if(Auth()->user()->hasRole("CANDIDAT")){
            //Attribution du user id à l'étudiant(e) concerné(e)
            Etudiant::whereId($data["etudiant_id"])->update([
                "user_id"=>Auth()->user()->id,
            ]);

            $data["user_id"]=Auth()->user()->id;
        }

        Etudiant::whereId($data["etudiant_id"])->update([
            "user_id"=>$data["user_id"],
        ]);
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
