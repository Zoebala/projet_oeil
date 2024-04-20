<?php

namespace App\Filament\Widgets;

use App\Models\Section;
use App\Models\Etudiant;
use Filament\Widgets\ChartWidget;

class SectionPaiementChart extends ChartWidget
{
    protected static ?string $heading = 'Effectifs des étudiants ayant payé par Section';
    protected static ?int $sort = 10;

    protected function getData(): array
    {
        $Sections=Section::get("lib");
        $tableau=[];$SectionsId=[];$EffectifParSection=[];
        //mise des valeurs de l'objet dans la variable tableau
        foreach ($Sections as $Section) {
            $tableau[]=$Section->lib;
        }

        $Sections=Section::get(["lib","id"]);
        //récupération des clefs de sections
        foreach ($Sections as $Section){
            $SectionsId[]=$Section->id;
        }
        //récupération des effectifs par section pour l'année en cours
        $annee=(date("Y")-1);
        foreach($SectionsId as $index){

            $EffectifParSection[]=Etudiant::join("classes","classes.id","etudiants.classe_id")
                                        ->join("departements","departements.id","classes.departement_id")
                                        ->join("sections","sections.id","departements.section_id")
                                        ->join("inscriptions","inscriptions.etudiant_id","etudiants.id")
                                        ->join("paiements","paiements.etudiant_id","etudiants.id")
                                        ->join("annees","annees.id","inscriptions.annee_id")
                                        ->where("annees.debut",$annee)
                                        ->where("sections.id",$index)
                                        ->where('inscriptions.actif',true)
                                        ->select(["etudiants.nom","etudiants.postnom","etudiants.prenom"])
                                        ->groupBy(["etudiants.nom","etudiants.postnom","etudiants.prenom"])
                                        ->get()
                                        ->count();
                                        // ->count());
        }







        return [
            'datasets' => [
                [
                    'label' => 'Effectifs des étudiants ayant payé par Section',
                    'data' => $EffectifParSection,
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
