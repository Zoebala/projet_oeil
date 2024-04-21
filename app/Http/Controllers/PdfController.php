<?php

namespace App\Http\Controllers;

use App\Models\Annee;
use App\Models\Etudiant;
use App\Models\Departement;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

class PdfController extends Controller
{
    //

    public function generate_pdf(int $annee_id,int $classe_id){

       $queries=Departement::join("classes","classes.departement_id","=","departements.id")
                        ->join("etudiants","etudiants.classe_id","=","classes.id")
                        ->join("inscriptions","inscriptions.etudiant_id","=","etudiants.id")
                        ->join("annees","annees.id","=","inscriptions.annee_id")
                        ->Where("annees.id",$annee_id)
                        ->Where("classes.id",$classe_id)
                        ->orderBy("departements.lib","asc")
                        ->orderBy("etudiants.nom","asc")
                        ->get(["nom","postnom","prenom","genre","classes.lib as classe","departements.lib as departement","annees.lib as annee"]);

        if(count($queries)>0){
            // Annee::whereLib()
            $data=[
                "title" => 'Liste des étudiants inscrits en '.$queries[0]->classe.' de l\'année '.$queries[0]->annee,
                "date" => date("d/m/Y"),
                "queries"=> $queries
            ];
            $pdf = Pdf::loadView('Etats/promotion',$data);
            return $pdf->download('Liste_promotions.pdf');
        }else{
            Notification::make()
            ->title('Aucune donnée trouvée!')
            ->danger()
           ->duration(5000)
            ->send();
            return redirect()->back();
        }
    }
}
