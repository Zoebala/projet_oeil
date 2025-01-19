<?php

namespace App\Filament\Widgets;

use App\Models\Annee;
use App\Models\Etudiant;
use App\Models\Departement;
use Filament\Widgets\ChartWidget;

class DepartementPaiement extends ChartWidget
{
    protected static ?string $heading = 'Effectif des étudiants ayant payé par Département';
    protected static ?int $sort = 12;
    protected static bool $isLazy = false;
    public static function canView(): bool
    {
        // Votre logique de contrôle d'accès ici
        if(Auth()->user()->hasRole(["Admin","COMGER","SGACAD","SGADMN","ADMIN_BUDGET","DG"])){

            return Annee::isActive(); // ou false selon vos besoins
        }else{

            return false;
        }
    }

    protected function getData(): array
    {
        $Departements=Departement::get("lib");
        $tableau=[];$DepartId=[];$EffectifparDepartement=[];
        //mise des valeurs de l'objet dans la variable tableau
        foreach ($Departements as $Depart) {
            $tableau[]=$Depart->lib;
        }

        $Departements=Departement::get(["lib","id"]);
        //récupération des clefs de Departements
        foreach ($Departements as $Depart){
            $DepartId[]=$Depart->id;
        }
        //récupération des effectifs par section pour l'année en cours
        $annee=(date("Y")-1);
        foreach($DepartId as $index){

            $EffectifparDepartement[]=Etudiant::join("classes","classes.id","etudiants.classe_id")
                                        ->join("departements","departements.id","classes.departement_id")
                                        ->join("inscriptions","inscriptions.etudiant_id","etudiants.id")
                                        ->join("paiements","paiements.etudiant_id","etudiants.id")
                                        ->join("annees","annees.id","paiements.annee_id")
                                        ->where("annees.id",session('Annee_id')[0] ?? 1)
                                        ->where("departements.id",$index)
                                        ->select(["etudiants.nom","etudiants.postnom","etudiants.prenom"])
                                        ->groupBy(["etudiants.nom","etudiants.postnom","etudiants.prenom"])
                                        ->get()
                                        ->count();
        }







        return [
            'datasets' => [
                [
                    'label' => 'Effectif des étudiants ayant payé par Département',
                    'data' => $EffectifparDepartement,
                    // définition des couleurs pour les effectifs des sections
                    'backgroundColor' => [
                        'rgb(255,99,132)',
                        'rgb(54,162,235)',
                        'rgb(255,205,86)',
                        'red',
                        'gray',
                        'green',
                        'yellow',
                        'lightblue',
                        'pink',
                        'blue',
                        'white',

                    ],
                ],
            ],
            'labels' => $tableau,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
