<?php

namespace App\Filament\Resources\FraisResource\Pages;

use App\Filament\Resources\FraisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrais extends EditRecord
{
    protected static string $resource = FraisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
