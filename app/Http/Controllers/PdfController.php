<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Departement;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

class PdfController extends Controller
{
    //

    public function generate_pdf(){

       $queries=Departement::join("classes","classes.departement_id","=","departements.id")
                        ->join("etudiants","etudiants.classe_id","=","classes.id")
                        ->join("inscriptions","inscriptions.etudiant_id","=","etudiants.id")
                        ->join("annees","annees.id","=","inscriptions.annee_id")
                        ->Where("annees.debut",date("Y")-1)
                        ->orderBy("departements.lib","asc")
                        ->orderBy("etudiants.nom","asc")
                        ->get(["nom","postnom","prenom","genre","classes.lib as classe","departements.lib as departement"]);
    //    dd($Etudiants);
        $data=[
            "title" => 'Liste des étudiants inscrits de l\'année '.(date("Y")-1).'-'.date("Y"),
            "date" => date("d/m/Y"),
            "queries"=> $queries
        ];
        // dd($data["Etudiants"]);
        Notification::make()
        ->title('Génération pdf effectuée avec succès!')
        ->success()
         ->duration(5000)
        ->send();
        $pdf = Pdf::loadView('Etats/promotion',$data);
        return $pdf->download('Liste_promotions.pdf');
    }
}
