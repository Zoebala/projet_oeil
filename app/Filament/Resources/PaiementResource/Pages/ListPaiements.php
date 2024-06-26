<?php

namespace App\Filament\Resources\PaiementResource\Pages;

use App\Models\Annee;
use Filament\Actions;
use App\Models\Etudiant;
use App\Models\Paiement;
use Livewire\Attributes\On;
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
