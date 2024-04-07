<?php

namespace App\Filament\Resources\EtudiantResource\Pages;

use Filament\Actions;
use Livewire\Attributes\On;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\EtudiantResource;
use App\Filament\Resources\EtudiantResource\Widgets\CreateEtudiantWidget;

class ListEtudiants extends ListRecords
{
    protected static string $resource = EtudiantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CreateEtudiantWidget::class,
        ];
    }

    #[On('etudiant-created')]
    public function refresh() {}
}
