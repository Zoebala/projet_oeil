<?php

namespace App\Filament\Widgets;

use App\Models\Section;
use App\Models\Etudiant;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SectionChart extends ChartWidget
{
    protected static ?string $heading = 'Effectifs des étudiants par Section';
    protected static ?int $sort = 8;
    protected static bool $isLazy = false;

    public static function canView(): bool
    {
        // Votre logique de contrôle d'accès ici
        if(Auth()->user()->hasRole(["Admin","COMGER","SGACAD","SGADMN","ADMIN_BUDGET","DG"])){

            return true; // ou false selon vos besoins
        }else{

            return false;
        }
    }



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
                                        ->join("annees","annees.id","inscriptions.annee_id")
                                        ->where("annees.debut",session('AnneeDebut') ?? $annee)
                                        ->where("sections.id",$index)
                                        ->count();
        }







        return [
            'datasets' => [
                [
                    'label' => 'Effectifs des étudiants par Section',
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
        return 'doughnut';
    }
}
