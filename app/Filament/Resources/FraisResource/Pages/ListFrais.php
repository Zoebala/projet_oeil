<?php

namespace App\Filament\Resources\FraisResource\Pages;

use Filament\Actions;
use Livewire\Attributes\On;
use App\Filament\Resources\FraisResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\FraisResource\Widgets\CreateFraisWidget;

class ListFrais extends ListRecords
{
    protected static string $resource = FraisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CreateFraisWidget::class,
        ];
    }

    #[On('frais-created')]
    public function refresh() {}
}
