<?php

namespace App\Filament\Resources\EtudiantResource\Pages;

use Filament\Actions;
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
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\EtudiantResource;
use Konnco\FilamentImport\Actions\ImportField;
use Konnco\FilamentImport\Actions\ImportAction;
use App\Filament\Resources\EtudiantResource\Widgets\CreateEtudiantWidget;

class ListEtudiants extends ListRecords
{
    protected static string $resource = EtudiantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make("Importer")
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
                    ->required()
                    ->label('Téléphone Etudiant'),
                ImportField::make('genre')
                    ->required()
                    ->label('Genre'),
                ImportField::make('classe_id')
                    ->required()
                    ->label('Classe'),

            ]),
            // Actions\CreateAction::make(),
            // Actions\Action::make("importer")
            // ->icon("heroicon-o-user-plus")
            // ->form([
            //     FileUpload::make("file"),
            // ])->modalWidth(MaxWidth::Medium)
            // ->modalIcon("heroicon-o-users")
            // ->Action(function(Request $request){
            //     // $file=request()->file('file');


            //     Excel::import(new EtudiantsImport,$request->file('file'));;
            //     Notification::make()
            //     ->title('Importation effectuée avec succès')
            //     ->success()
            //      ->duration(5000)
            //     ->send();

            // }),
        ];
    }
    // public function getHeader():?View
    // {
    //     $data=Actions\CreateAction::make();
    //     return view("filament.custom.upload-file",compact("data"));
    // }

    protected function getHeaderWidgets(): array
    {
        return [
            CreateEtudiantWidget::class,
        ];
    }
    #[Rule('required|file|mimes:csv:max:4048')]
    public $file="";

    public function save(){
        if($this->file != ""){
            Excel::import(new EtudiantsImport,$this->file);

            Notification::make()
                ->title('Importation effectuée avec succès')
                ->success()
                 ->duration(5000)
                ->send();

            // $this->file->reset();
        }


    }


    #[On('etudiant-created')]
    public function refresh() {}
}
