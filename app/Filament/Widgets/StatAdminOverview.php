<?php

namespace App\Filament\Widgets;

use App\Models\Section;
use App\Models\Etudiant;
use App\Models\Departement;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatAdminOverview extends BaseWidget
{


    protected function getStats(): array
    {
        return [
            //
            Stat::make("Sections", Section::count())
            ->description("Nos Sections")
            ->color("warning")
            ->chart([34,2,5,23])

            ->Icon("heroicon-o-building-office-2"),
            Stat::make("Departements", Departement::count())
            ->description("Nos Départements")
            ->color("success")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-home-modern"),
            Stat::make("Etudiants", Etudiant::join("inscriptions","inscriptions.etudiant_id","=","etudiants.id")->Where("inscriptions.actif",1)->count())
            ->description("Etudiants Inscrits")
            ->color("warning")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-users"),
            Stat::make("Etudiants", Etudiant::where("actif")->leftjoin("inscriptions","inscriptions.etudiant_id","=","etudiants.id")->count())
            ->description("Etudiants non Inscrits")
            ->color("danger")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-users"),
            Stat::make("Etudiants", Etudiant::join("paiements","paiements.etudiant_id","=","etudiants.id")->count())
            ->description("Etudiant ayant payé un accompte")
            ->color("success")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-users"),
            Stat::make("Nombre Filles", Etudiant::join("inscriptions","inscriptions.etudiant_id","=","etudiants.id")->Where("etudiants.genre","F")->count())
            ->description("Etudiantes")
            ->color("warning")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-users"),
            Stat::make("Nombre Garçons", Etudiant::join("inscriptions","inscriptions.etudiant_id","=","etudiants.id")->Where("etudiants.genre","M")->count())
            ->description("Etudiants")
            ->color("success")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-users"),
            Stat::make("Total Solde Paiement", Etudiant::join("paiements","paiements.etudiant_id","=","etudiants.id")->sum("montant"). " FC")
            ->description("Nos paiements")
            ->color("warning")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-banknotes"),

        ];
    }

    public function getColumns(): int
    {
        return 4;
    }
}
