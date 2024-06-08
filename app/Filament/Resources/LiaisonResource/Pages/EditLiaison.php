<?php

namespace App\Filament\Resources\LiaisonResource\Pages;

use App\Filament\Resources\LiaisonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLiaison extends EditRecord
{
    protected static string $resource = LiaisonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
