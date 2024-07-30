<?php

namespace App\Filament\Resources\PaiementResource\Pages;

use App\Models\Annee;
use Filament\Actions;
use Filament\Forms\Set;
use App\Models\Etudiant;
use App\Models\Paiement;
use Livewire\Attributes\On;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PaiementResource;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\PaiementResource\Widgets\CreatePaiementWidget;

class ListPaiements extends ListRecords
{
    protected static string $resource = PaiementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label("Effectuer Paiement")
            ->icon("heroicon-o-banknotes"),
        ];
    }

    public $defaultAction="Annee";
    protected function Annee():Action
    {

       return Action::make("annee")
        ->modalHeading(function(){
            if(Auth()->user()->hasRole("CANDIDAT"))
                return "Choisir une année Académique";
            else
                return "Définition de l'année de travail";
        })
        ->icon("heroicon-o-calendar-days")
        ->visible(fn():bool => session("Annee_id")==null)
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
            ->options(Annee::query()->pluck("lib","id")),
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

            return redirect()->route("filament.admin.resources.frais.index");


        });
    }

    public function getTabs():array
    {

        $Annee=Annee::where("id",session("Annee_id") ?? 1)->first();
        $Etudiant=Etudiant::whereUser_id(Auth()->user()->id)->first();

        if(Auth()->user()->hasRole("CANDIDAT")){

            return [
                "$Annee->lib"=>Tab::make()
                ->modifyQueryUsing(function(Builder $query)
                {
                $query->where("annee_id",session("Annee_id") ?? 1);

                })->badge(Paiement::query()
                ->where("Etudiant_id",$Etudiant->id)->where("annee_id",session("Annee_id") ?? 1)->count())
                ->icon("heroicon-o-calendar-days"),
                'Tous'=>Tab::make()
                ->badge(Paiement::where("Etudiant_id",$Etudiant->id)->count()),

            ];
        }else{

            return [
                "$Annee->lib"=>Tab::make()
                ->modifyQueryUsing(function(Builder $query)
                {
                $query->where("annee_id",session("Annee_id") ?? 1);

                })->badge(Paiement::query()
                ->where("annee_id",session("Annee_id") ?? 1)->count())
                ->icon("heroicon-o-calendar-days"),
                'Tous'=>Tab::make()
                ->badge(Paiement::query()->count()),

            ];
        }
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // CreatePaiementWidget::class,
        ];
    }

    #[On('paiement-created')]
    public function refresh() {}
}
