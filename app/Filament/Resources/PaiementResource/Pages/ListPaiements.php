<?php

namespace App\Filament\Resources\PaiementResource\Pages;

use Filament\Actions;
use Livewire\Attributes\On;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PaiementResource;
use App\Filament\Resources\PaiementResource\Widgets\CreatePaiementWidget;

class ListPaiements extends ListRecords
{
    protected static string $resource = PaiementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CreatePaiementWidget::class,
        ];
    }

    #[On('paiement-created')]
    public function refresh() {}
}
