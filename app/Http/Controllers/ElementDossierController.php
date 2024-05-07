<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class ElementDossierController extends Controller
{
    //

    public function generate_pdf(int $annee_id,int $classe_id){
        // dd($annee_id,$classe_id);
        $queries=DB::select("SELECT nom, postnom,prenom,genre,cl.lib as classe, an.lib as Annee, dep.lib as departement,etud.datenais as Naissance
                             FROM etudiants as etud
                             JOIN classes as cl ON etud.classe_id=cl.id
                             JOIN  departements as dep ON dep.id=cl.departement_id
                             JOIN inscriptions as ins ON ins.etudiant_id=etud.id
                             JOIN annees as an ON an.id=ins.annee_id
                             WHERE etud.files IS NULL AND ins.actif=1 AND cl.id=$classe_id AND an.id=$annee_id");



        if(count($queries) > 0){
            $data=[
                "title" => 'Etudiants n\'ayant pas leurs éléments dossiers en ordre en '.$queries[0]->classe." - ".$queries[0]->Annee,
                "date" => date("d/m/Y"),
                "queries"=> $queries
            ];

            $pdf = Pdf::loadView('Etats/dossierEtudiant',$data);
            return $pdf->download('Liste_Etudiants_non_en_ordre_élément_dossier_'.date("d/m/Y H:i:s").'.pdf');
        }else{
            Notification::make()
            ->title('Aucune donnée trouvée!')
            ->danger()
           ->duration(5000)
            ->send();
            return redirect()->back();

        }
        // dd($data["Etudiants"]);


    }
}
