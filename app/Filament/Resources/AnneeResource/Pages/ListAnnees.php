<?php

namespace App\Filament\Resources\AnneeResource\Pages;

use Filament\Actions;
use Livewire\Attributes\On;
use App\Filament\Resources\AnneeResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\AnneeResource\Widgets\CreateAnneeWidget;

class ListAnnees extends ListRecords
{
    protected static string $resource = AnneeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CreateAnneeWidget::class,
        ];
    }

    #[On('annee-created')]
    public function refresh() {}
}
