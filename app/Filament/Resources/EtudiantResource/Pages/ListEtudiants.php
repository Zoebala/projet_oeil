<?php

namespace App\Filament\Resources\EtudiantResource\Pages;

use Filament\Actions;
use App\Models\Classe;
use Filament\Forms\Set;
use App\Models\Etudiant;
use Livewire\Attributes\On;
use Filament\Actions\Action;
use Illuminate\Http\Request;
use Livewire\Attributes\Rule;
use App\Imports\EtudiantsImport;
use Illuminate\Contracts\View\View;
use Filament\Support\Enums\MaxWidth;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use pxlrbt\FilamentExcel\Columns\Column;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EtudiantResource;
use Filament\Resources\Pages\ListRecords\Tab;
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
            Actions\Action::make("Accueil")
            ->icon("heroicon-o-home")
            ->action(function(){
                return redirect("/");
            }),


            Actions\Action::make("Je suis déjà inscrit(e)")
            ->icon("heroicon-o-link")
            ->action(function(){
                return redirect()->route("filament.admin.resources.liaisons.index");
            })
            ->hidden(fn():bool =>Etudiant::whereUser_id(Auth()->user()->id)->exists()),
            Actions\CreateAction::make()
            ->label(function(){
                if(Auth()->user()->hasRole(["Admin","SACAD"])){
                    return "Ajouter un étudiant";
                }else{
                    return "Posez votre Candidature";
                }
            })
            ->icon("heroicon-o-user-plus")
            ->visible(fn():bool => Etudiant::where("user_id",Auth()->user()->id)->exists()),



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
                    // ->required()
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

            ])->visible(fn():bool => Auth()->user()->hasRole(["Admin","SACAD"])),
            Action::make("classe_choix")
                ->icon("heroicon-o-building-office")
                ->label("Choix de la Classe")
                ->modalSubmitActionLabel("Définir")
                ->form([
                    Select::make("classe_id")
                    ->label("Classe")
                    ->options(Classe::all()->pluck("lib","id"))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function($state,Set $set){
                        $Classe=Classe::whereId($state)->get(["lib"]);
                        $set("classe",$Classe[0]->lib);

                    }),
                    Hidden::make("classe")
                    ->disabled()
                    ->dehydrated(true),


                ])
                ->modalWidth(MaxWidth::Medium)
                ->modalIcon("heroicon-o-building-office-2")
                ->action(function(array $data){
                    if(session('classe_id')==NULL && session('classe')==NULL){

                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);

                    }else{

                        session()->pull("classe_id", $data["classe_id"]);
                        session()->pull("classe", $data["classe"]);
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);


                    }
                    Notification::make()
                    ->title("Classe Choisie :  ".$data['classe'])
                    ->success()
                     ->duration(5000)
                    ->send();
                     return redirect()->route("filament.admin.resources.etudiants.index");

                }),





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


    public $defaultAction="classe";
    public function classe():Action
    {

        return Action::make("classe")
                ->modalHeading("Choix de la Classe")
                ->modalSubmitActionLabel("Définir")
                ->visible(fn():bool => session("classe_id") == null)
                ->form([
                    Select::make("classe_id")
                    ->label("Classe")
                    ->options(Classe::all()->pluck("lib","id"))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function($state,Set $set){
                        $Classe=Classe::whereId($state)->get(["lib"]);
                        $set("classe",$Classe[0]->lib);

                    }),
                    Hidden::make("classe")
                    ->disabled()
                    ->dehydrated(true),


                ])
                ->modalWidth(MaxWidth::Medium)
                ->modalIcon("heroicon-o-building-office-2")
                ->action(function(array $data){
                    if(session('classe_id')==NULL && session('classe')==NULL){

                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);

                    }else{

                        session()->pull("classe_id", $data["classe_id"]);
                        session()->pull("classe", $data["classe"]);
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);


                    }
                    Notification::make()
                    ->title("Classe Choisie :  ".$data['classe'])
                    ->success()
                     ->duration(5000)
                    ->send();
                     return redirect()->route("filament.admin.resources.etudiants.index");

                });

    }

    public function getTabs():array
    {

        $Classe=Classe::where("id",session("classe_id")[0] ?? 1)->first();

            return [
                "$Classe->lib | Code : $Classe->id "=>Tab::make()
                ->modifyQueryUsing(function(Builder $query)
                {
                $query->where("classe_id",session("classe_id")[0] ?? 1);

                })->badge(Etudiant::where("classe_id",session("classe_id")[0] ?? 1)
                                 ->count())
                ->icon("heroicon-o-calendar-days"),
                'Tous'=>Tab::make()
                ->badge(Etudiant::query()->count()),

            ];

    }
}
