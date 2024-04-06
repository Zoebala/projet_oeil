<?php

namespace App\Filament\Resources\DepartementResource\Pages;

use Filament\Actions;
use Livewire\Attributes\On;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\DepartementResource;
use App\Filament\Resources\DepartementResource\Widgets\CreateDepartementWidget;

class ListDepartements extends ListRecords
{
    protected static string $resource = DepartementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CreateDepartementWidget::class,
        ];
    }

    #[On('departement-created')]
    public function refresh() {}
}
