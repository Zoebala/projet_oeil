<?php

namespace App\Filament\Resources\ClasseResource\Pages;

use Filament\Actions;
use Livewire\Attributes\On;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ClasseResource;
use App\Filament\Resources\ClasseResource\Widgets\CreateClasseWidget;

class ListClasses extends ListRecords
{
    protected static string $resource = ClasseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CreateClasseWidget::class,
        ];


    }

    #[On('classe-created')]
    public function refresh() {}

}
