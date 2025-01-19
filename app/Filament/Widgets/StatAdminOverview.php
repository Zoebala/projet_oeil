<?php

namespace App\Filament\Widgets;

use App\Models\Section;
use App\Models\Etudiant;
use App\Models\Actualite;
use App\Models\Annee;
use App\Models\Departement;
use App\Models\Inscription;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatAdminOverview extends BaseWidget
{

    protected static bool $isLazy = false;


    public static function canView(): bool
    {

        return Annee::isActive();

    }


    protected function getStats(): array
    {
        return [
            //
            Stat::make("Sections/Facultés", Section::count())
            ->description("Nos Sections")
            ->color("warning")
            ->chart([34,2,5,23])

            ->Icon("heroicon-o-building-office-2"),
            Stat::make("Departements", Departement::count())
            ->description("Nos Départements")
            ->color("success")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-home-modern"),
            Stat::make("Actualités", Actualite::take(5)->Orderby("id","desc")->count())
            ->description("Nombre d'actualités publié")
            ->color("warning")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-clipboard-document-list"),
            // Stat::make("Etudiants", Etudiant::join("inscriptions","inscriptions.etudiant_id","=","etudiants.id")
            //                             ->join("annees","annees.id","=","inscriptions.annee_id")
            //                             ->Where("annees.debut",session('AnneeDebut') ?? date("Y")-1)
            //                             ->Where("inscriptions.actif",1)
            //                             ->count())
            // ->description("Etudiants Inscrits")
            // ->color("warning")
            // ->chart([34,2,5,23])
            // ->Icon("heroicon-o-users"),
            // Stat::make("Etudiant(s)",Etudiant::leftJoin("inscriptions","inscriptions.etudiant_id","etudiants.id")
            //                             ->where("actif")
            //                             ->select(["inscriptions.etudiant_id","inscriptions.annee_id","inscriptions.classe_id"])
            //                             ->groupBy(["inscriptions.etudiant_id","inscriptions.annee_id","inscriptions.classe_id"])
            //                             // ->get()
            //                             ->count())

            // ->description("Etudiants non Inscrits")
            // ->color("danger")
            // ->chart([34,2,5,23])
            // ->Icon("heroicon-o-users"),
            // Stat::make("Etudiant(s)", Etudiant::join("paiements","paiements.etudiant_id","=","etudiants.id")
            //                             ->join("annees","annees.id","=","paiements.annee_id")
            //                             ->join("inscriptions","inscriptions.etudiant_id","etudiants.id")
            //                             ->Where("annees.debut",session('AnneeDebut') ?? date("Y")-1)
            //                             ->whereActif(1)
            //                             ->select(["etudiants.nom","etudiants.postnom","etudiants.prenom"])
            //                             ->groupBy(["etudiants.nom","etudiants.postnom","etudiants.prenom"])
            //                             ->get()
            //                             ->count())
            // ->description("Etudiants ayant payé un accompte")
            // ->color("success")
            // ->chart([34,2,5,23])
            // ->Icon("heroicon-o-users"),
            // Stat::make("Nombre Filles", Etudiant::join("inscriptions","inscriptions.etudiant_id","=","etudiants.id")
            //                                 ->join("annees","annees.id","=","inscriptions.annee_id")
            //                                 ->Where("annees.debut",session('AnneeDebut') ?? date("Y")-1)
            //                                 ->Where("etudiants.genre","F")
            //                                 ->Where("actif",1)
            //                                 ->count())
            // ->description("Etudiantes Inscrites")
            // ->color("warning")
            // ->chart([34,2,5,23])
            // ->Icon("heroicon-o-users"),
            // Stat::make("Nombre Garçons", Etudiant::join("inscriptions","inscriptions.etudiant_id","=","etudiants.id")
            //                                 ->join("annees","annees.id","=","inscriptions.annee_id")
            //                                 ->Where("annees.debut",session('AnneeDebut') ?? date("Y")-1)
            //                                 ->Where("etudiants.genre","M")
            //                                 ->Where("actif",1)
            //                                 ->count())
            // ->description("Etudiants Inscrits")
            // ->color("success")
            // ->chart([34,2,5,23])
            // ->Icon("heroicon-o-users"),
            // Stat::make("Total des paiements enregistrés", Etudiant::join("paiements","paiements.etudiant_id","=","etudiants.id")
            //                                         ->join("annees","annees.id","=","paiements.annee_id")
            //                                         ->join("inscriptions","inscriptions.etudiant_id","etudiants.id")
            //                                         ->Where("annees.debut",session('AnneeDebut') ?? date("Y")-1)
            //                                         ->whereActif(1)
            //                                         ->sum("montant")." FC")

            // ->description("paiements enregistrés")
            // ->color("warning")
            // ->chart([34,2,5,23])
            // ->Icon("heroicon-o-banknotes"),

        ];
    }

    public function getColumns(): int
    {
        return 3;
    }
}
