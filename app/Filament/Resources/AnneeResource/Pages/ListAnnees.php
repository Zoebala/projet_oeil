<?php

namespace App\Filament\Resources\AnneeResource\Pages;

use App\Models\Annee;
use Filament\Actions;
use Filament\Forms\Set;
use Livewire\Attributes\On;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Filament\Resources\AnneeResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\AnneeResource\Widgets\CreateAnneeWidget;

class ListAnnees extends ListRecords
{
    protected static string $resource = AnneeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label("Ajouter Année")
            ->icon("heroicon-o-calendar"),
            Action::make("annee")
                ->icon("heroicon-o-calendar-days")
                ->slideOver()
                ->label(function(){
                    if(Auth()->user()->hasRole("CANDIDAT"))
                        return "Choisir une année Académique";
                    else
                        return "Définition de l'année de travail";
                })
                ->form([
                    Select::make("annee")
                    ->label("Choix de l'année")
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function($state,Set $set){
                        $Annee=Annee::whereId($state)->get(["lib","debut"]);
                        $set("lib_annee",$Annee[0]->lib);
                        $set("annee_debut",$Annee[0]->debut);
                    })
                    ->options(Annee::query()->latest()->pluck("lib","id")),
                    Hidden::make("lib_annee")
                    ->label("Année Choisie")
                    ->dehydrated(true),
                    Hidden::make("annee_debut")
                    ->label("Année Début")
                    ->dehydrated(true),

                ])
                ->modalWidth(MaxWidth::Medium)
                ->modalIcon("heroicon-o-calendar")
                ->action(function(array $data){
                    if(session('Annee_id')==NULL && session('Annee')==NULL && session('AnneeDebut')==NULL){

                        session()->push("Annee_id", $data["annee"]);
                        session()->push("Annee", $data["lib_annee"]);
                        session()->push("AnneeDebut", $data["annee_debut"]);
                    }else{
                        session()->pull("Annee_id");
                        session()->pull("Annee");
                        session()->pull("AnneeDebut");
                        session()->push("Annee_id", $data["annee"]);
                        session()->push("Annee", $data["lib_annee"]);
                        session()->push("AnneeDebut", $data["annee_debut"]);

                    }

                    // dd(session('Annee'));
                    Notification::make()
                    ->title("Fixation de l'annee de travail en ".$data['lib_annee'])
                    ->success()
                     ->duration(5000)
                    ->send();


                    return redirect("/admin");


                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // CreateAnneeWidget::class,
        ];
    }

    #[On('annee-created')]
    public function refresh() {}
}
