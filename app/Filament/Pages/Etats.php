<?php

namespace App\Filament\Pages;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Etudiant;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Tables\Concerns\HasActions;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Concerns\InteractsWithForms;


class Etats extends Page
{
//    use HasActions, InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.etats';

    public static function canAccess():bool
    {
        return Auth()->user()->hasRole('Admin');


    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([

                Action::make("Etudiants Inscrits")
                    ->label("Listes des étudiants Inscrits")
                    ->tooltip("Listes des étudiants Inscrits")
                    ->url(fn():string =>route("etudiant.generate_promotion"))
                    ->openUrlInNewTab(),
                Action::make("étudiants ayant payé")
                    ->label("Liste des étudiants ayant payé")
                    ->url(fn():string =>route("etudiants.paye"))
                    ->openUrlInNewTab()
                    ->tooltip("Liste des étudiants ayant payé"),

                Action::make("Liste des frais payés")
                    ->url(fn():string =>route("frais.paye"))
                    ->openUrlInNewTab()
                    ->tooltip("Liste des frais payés"),
                Action::make("Liste des frais payés par promotion")
                    ->form([
                        Select::make("annee_id")
                            ->label("Année Académique")
                            ->options(Annee::query()->pluck("lib","id"))
                            ->searchable(),
                        Select::make("classe_id")
                            ->label("Promotion")
                            ->options(Classe::query()->pluck("lib","id"))
                            ->searchable(),

                    ])->modalWidth(MaxWidth::Medium)
                    ->modalIcon("heroicon-o-users")
                    ->action(function(array $data){

                        $annee_id=$data["annee_id"];
                        $classe_id=$data["classe_id"];


                        return redirect()->route("frais_promotion",compact("annee_id","classe_id"));
                    })
                    ->openUrlInNewTab()
                    ->tooltip("Listes des frais payés par promotion"),
                // Action::make("Listes Départements"),
            ])->label("_________________________________________Génération des Etats de Sorties_______________________________________________")
            ->Icon("heroicon-o-clipboard-document-list")
            ->button(),
        ];

    }
}
