<?php

namespace App\Filament\Widgets;

use App\Models\Etudiant;
use App\Models\Departement;
use Filament\Widgets\ChartWidget;

class DepartementChart extends ChartWidget
{
    protected static ?string $heading = 'Effectifs des étudiants par Département';
    protected static bool $isLazy = false;

    protected static ?int $sort = 9;
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
                                        ->join("annees","annees.id","inscriptions.annee_id")
                                        ->where("annees.debut",session('AnneeDebut') ?? $annee)
                                        ->where("departements.id",$index)
                                        ->count();
        }


        // dd(session('AnneeDebut'));




        return [
            'datasets' => [
                [
                    'label' => 'Effectifs des étudiants par Département',
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

                    ],
                ],
            ],
            'labels' => $tableau,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
