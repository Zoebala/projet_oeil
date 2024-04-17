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

                Action::make("Listes Des Etudiants ayant payé")
                ->url(fn(Collection $Student) =>route("etudiant.generate_promotion",$Student))
                ->openUrlInNewTab(),
                Action::make("Listes des paiements journaliers"),
                Action::make("Listes des étudiants non inscrits"),
                Action::make("Listes Départements"),
                Action::make("Listes Départements"),
            ])->label("Génération des Etats de Sorties")
            ->Icon("heroicon-o-clipboard-document-list")
            ->button(),
        ];

    }
}
