<?php

namespace App\Filament\Resources\InscriptionResource\Pages;

use Filament\Actions;
use Livewire\Attributes\On;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\InscriptionResource;
use App\Filament\Resources\InscriptionResource\Widgets\CreateInscriptionWidget;

class ListInscriptions extends ListRecords
{
    protected static string $resource = InscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),

        ];
    }


    protected function getHeaderWidgets(): array
    {
        return [
            CreateInscriptionWidget::class,
        ];
    }

    #[On('inscription-created')]
    public function refresh() {}

    
}
