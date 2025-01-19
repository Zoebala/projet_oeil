<?php

namespace App\Filament\Resources\FraisResource\Pages;

use App\Models\Annee;
use App\Models\Frais;
use Filament\Actions;
use Filament\Forms\Set;
use Livewire\Attributes\On;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use App\Filament\Resources\FraisResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\FraisResource\Widgets\CreateFraisWidget;
use App\Models\Classe;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\TextInput;

class ListFrais extends ListRecords
{
    protected static string $resource = FraisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label("Définir les Frais")
            ->icon("heroicon-o-banknotes"),
            Action::make("budget Annuel")
            ->hidden(fn():bool => !Auth()->user()->hasRole(["COMGER","Admin","SGACAD","SACAD","DG"]))
            ->action(fn()=>redirect()->route("budget"))
            ->openUrlInNewTab()
            ->tooltip("budget")
            ->button()
            ->icon("heroicon-o-clipboard-document-list")
            ->color("warning"),

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
