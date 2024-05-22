<?php

namespace App\Filament\Resources\FraisResource\Pages;

use App\Models\Annee;
use App\Models\Frais;
use Filament\Actions;
use Livewire\Attributes\On;
use App\Filament\Resources\FraisResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\FraisResource\Widgets\CreateFraisWidget;

class ListFrais extends ListRecords
{
    protected static string $resource = FraisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label("Définir les Frais")
            ->icon("heroicon-o-banknotes"),
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

            })->badge(Frais::query()
            ->where("annee_id",session("Annee_id") ?? 1)->count())
            ->icon("heroicon-o-calendar-days"),
            'Toutes'=>Tab::make()
            ->badge(Frais::query()->count()),

        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // CreateFraisWidget::class,
        ];
    }

    #[On('frais-created')]
    public function refresh() {}
}
