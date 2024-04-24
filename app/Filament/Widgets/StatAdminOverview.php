<?php

namespace App\Filament\Widgets;

use App\Models\Section;
use App\Models\Etudiant;
use App\Models\Departement;
use App\Models\Inscription;
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
            Stat::make("Etudiants", Etudiant::join("inscriptions","inscriptions.etudiant_id","=","etudiants.id")
                                        ->join("annees","annees.id","=","inscriptions.annee_id")
                                        ->Where("annees.debut",session('AnneeDebut') ?? date("Y")-1)
                                        ->Where("inscriptions.actif",1)
                                        ->count())
            ->description("Etudiants Inscrits")
            ->color("warning")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-users"),
            Stat::make("Etudiants",Etudiant::leftJoin("inscriptions","inscriptions.etudiant_id","etudiants.id")
                                        ->where("actif")->count())

            ->description("Etudiants non Inscrits")
            ->color("danger")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-users"),
            Stat::make("Etudiants", Etudiant::join("paiements","paiements.etudiant_id","=","etudiants.id")
                                        ->join("annees","annees.id","=","paiements.annee_id")
                                        ->Where("annees.debut",session('AnneeDebut') ?? date("Y")-1)
                                        ->count())
            ->description("Etudiant ayant payé un accompte")
            ->color("success")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-users"),
            Stat::make("Nombre Filles", Etudiant::join("inscriptions","inscriptions.etudiant_id","=","etudiants.id")
                                            ->join("annees","annees.id","=","inscriptions.annee_id")
                                            ->Where("annees.debut",session('AnneeDebut') ?? date("Y")-1)
                                            ->Where("etudiants.genre","F")
                                            ->count())
            ->description("Etudiantes Inscrites")
            ->color("warning")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-users"),
            Stat::make("Nombre Garçons", Etudiant::join("inscriptions","inscriptions.etudiant_id","=","etudiants.id")
                                            ->join("annees","annees.id","=","inscriptions.annee_id")
                                            ->Where("annees.debut",session('AnneeDebut') ?? date("Y")-1)
                                            ->Where("etudiants.genre","M")
                                            ->count())
            ->description("Etudiants Inscrits")
            ->color("success")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-users"),
            // SELECT substring(lib,1,4) AS Annee,SUM(montant) AS Montant FROM annees JOIN paiements ON annees.id=paiements.annee_id GROUP BY lib HAVING Annee=2023
            Stat::make("Total des paiements enregistrés", Etudiant::join("paiements","paiements.etudiant_id","=","etudiants.id")
                                                    ->join("annees","annees.id","=","paiements.annee_id")
                                                    ->Where("annees.debut",session('AnneeDebut') ?? date("Y")-1)
                                                    ->sum("montant")." FC")
            ->description("paiements enregistrés")
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
