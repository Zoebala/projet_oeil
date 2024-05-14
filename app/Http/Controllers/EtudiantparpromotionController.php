<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class EtudiantparpromotionController extends Controller
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
                             WHERE ins.actif=1 AND cl.id=$classe_id AND an.id=$annee_id");



        if(count($queries) > 0){
            $data=[
                "title" => 'Etudiants de '.$queries[0]->classe." - ".$queries[0]->Annee,
                "date" => date("d/m/Y"),
                "queries"=> $queries
            ];

            $pdf = Pdf::loadView('Etats/Etudiant_par_promotion',$data);
            return $pdf->download('Liste_Etudiant_par_promotion'.date("d/m/Y H:i:s").'.pdf');
        }else{
            Notification::make()
            ->title('Aucune donnée trouvée!')
            ->danger()
           ->duration(5000)
            ->send();
            return redirect()->back();

        }
    }

    //Etudiants par promotion mais non inscrits
    public function generate_pdf1(int $classe_id){

        $queries=Etudiant::leftJoin("inscriptions","inscriptions.etudiant_id","etudiants.id")
                            ->join("classes","classes.id","etudiants.classe_id")
                            ->join("departements","departements.id","classes.departement_id")
                            ->where("actif")
                            ->where("etudiants.classe_id",$classe_id)
                            ->get(["nom","postnom","prenom","classes.lib as classe","genre","departements.lib as departement"]);



        if(count($queries) > 0){
            $data=[
                "title" => 'Etudiants non inscrits de '.$queries[0]->classe,
                "date" => date("d/m/Y"),
                "queries"=> $queries
            ];

            $pdf = Pdf::loadView('Etats/Etudiant_par_promotion',$data);
            return $pdf->download('Liste_Etudiant_par_promotion_non_inscrits'.date("d/m/Y H:i:s").'.pdf');
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
