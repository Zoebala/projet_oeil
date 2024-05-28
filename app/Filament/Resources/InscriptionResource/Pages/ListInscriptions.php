<?php

namespace App\Filament\Resources\InscriptionResource\Pages;

use App\Models\Annee;
use Filament\Actions;
use App\Models\Inscription;
use Livewire\Attributes\On;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\InscriptionResource;
use App\Filament\Resources\InscriptionResource\Widgets\CreateInscriptionWidget;

class ListInscriptions extends ListRecords
{
    protected static string $resource = InscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make()
            // ->label("Nouvelle Inscription")
            // ->icon("heroicon-o-clipboard-document-list"),

        ];
    }

    public function getTabs():array
    {

        $Annee=Annee::where("id",session("Annee_id") ?? 1)->first();

        return [
            "$Annee->lib"=>Tab::make()
            ->modifyQueryUsing(function(Builder $query)
            {
               $query->where("annee_id",session("Annee_id") ?? 1);

            })->badge(Inscription::query()
            ->where("annee_id",session("Annee_id") ?? 1)->count())
            ->icon("heroicon-o-calendar-days"),
            'Toutes'=>Tab::make()
            ->badge(Inscription::query()->count()),

        ];
    }


    protected function getHeaderWidgets(): array
    {
        return [
            CreateInscriptionWidget::class,
        ];
    }

    #[On('inscription-created')]
    public function refresh() {}


}
