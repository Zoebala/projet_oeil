<?php

namespace App\Filament\Resources\EtudiantResource\Pages;

use Filament\Actions;
use App\Models\Classe;
use App\Models\Etudiant;
use Livewire\Attributes\On;
use Illuminate\Http\Request;
use Livewire\Attributes\Rule;
use App\Imports\EtudiantsImport;
use Illuminate\Contracts\View\View;
use Filament\Support\Enums\MaxWidth;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use pxlrbt\FilamentExcel\Columns\Column;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\EtudiantResource;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Konnco\FilamentImport\Actions\ImportField;
use Konnco\FilamentImport\Actions\ImportAction;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use App\Filament\Resources\EtudiantResource\Widgets\CreateEtudiantWidget;

class ListEtudiants extends ListRecords
{
    protected static string $resource = EtudiantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportAction::make("Importer")
            ->label("Importer")
            ->icon("heroicon-o-users")
            // ->uniqueField('nom')
            ->fields([
                ImportField::make('nom')
                    ->required(),
                ImportField::make('postnom')
                    ->required()
                    ->label('Postnom'),
                ImportField::make('prenom')
                    ->required()
                    ->label('Prenom'),
                ImportField::make('teletudiant')
                    // ->required()
                    ->label('Téléphone Etudiant'),
                ImportField::make('genre')
                    ->required()
                    ->label('Genre'),
                ImportField::make('matricule')
                    ->required()
                    ->label('Matricule'),
                ImportField::make('classe_id')
                    ->required()
                    ->label('Classe'),

            ]),





        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // CreateEtudiantWidget::class,
        ];
    }


    #[On('etudiant-created')]
    public function refresh() {}
}
