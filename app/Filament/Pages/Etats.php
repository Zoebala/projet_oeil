<?php

namespace App\Filament\Pages;

use App\Models\Etudiant;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\ActionGroup;
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
                Action::make("Listes des étudiants non inscrits"),
                Action::make("Listes Départements"),
                Action::make("Listes Départements"),
            ])->label("_________________________________________Génération des Etats de Sorties_______________________________________________")
            ->Icon("heroicon-o-clipboard-document-list")
            ->button(),
        ];

    }
}
