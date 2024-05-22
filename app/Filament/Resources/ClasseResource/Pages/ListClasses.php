<?php

namespace App\Filament\Resources\ClasseResource\Pages;

use Filament\Actions;
use Livewire\Attributes\On;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ClasseResource;
use Konnco\FilamentImport\Actions\ImportField;
use Konnco\FilamentImport\Actions\ImportAction;
use App\Filament\Resources\ClasseResource\Widgets\CreateClasseWidget;

class ListClasses extends ListRecords
{
    protected static string $resource = ClasseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label("Ajouter une classe")
            ->icon("heroicon-o-building-office-2"),
            ImportAction::make("Importer")
            ->label("Importer")
            ->icon("heroicon-o-users")
            // ->uniqueField('nom')
            ->fields([
                ImportField::make('lib')
                    ->label("Classe")
                    ->required(),
                ImportField::make('departement_id')
                    ->required()
                    ->label('Departement'),



            ])->visible(fn():bool => Auth()->user()->hasRole(["Admin","SACAD"])),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // CreateClasseWidget::class,
        ];


    }

    #[On('classe-created')]
    public function refresh() {}

}
