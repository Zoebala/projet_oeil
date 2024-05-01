<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;


class PaiementController extends Controller
{
    //

    public function generate_pdf(int $annee_id,int $classe_id){

            $queries=DB::Select("SELECT nom,postnom,prenom,genre,cl.lib as classe, dep.lib as departement,an.debut as debut,SUM(paie.montant) as montant,an.lib as Annee
                                FROM departements dep
                                JOIN classes cl ON dep.id = cl.departement_id
                                JOIN etudiants etud ON etud.classe_id=cl.id
                                JOIN inscriptions insc ON insc.etudiant_id=etud.id
                                JOIN paiements paie ON paie.etudiant_id=etud.id
                                JOIN annees an ON an.id=insc.annee_id
                                WHERE an.id=$annee_id AND cl.id=$classe_id AND insc.actif=1
                                GROUP BY nom,postnom,prenom,genre,classe,departement,debut,an.lib
                                ORDER BY dep.lib asc,etud.nom asc");


        // dd($queries[0]->departement);
        if(count($queries) >0){

            $data=[
                "title" => 'Liste des étudiants de '.$queries[0]->classe.' ayant payé en l\'année '.$queries[0]->Annee,
                "date" => date("d/m/Y"),
                "queries"=> $queries
            ];
            // dd($data["Etudiants"]);

            $pdf = Pdf::loadView('Etats/paiement',$data);
            return $pdf->download('Liste_paiement_'.date("d/m/Y H:i:s").'.pdf');
        }else{
            Notification::make()
            ->title('Aucune donnée trouvée!')
            ->danger()
           ->duration(5000)
            ->send();
            return redirect()->back();
        }
     }

    public function generate_pdf1(int $annee_id,int $classe_id,int $montant){
        
        $queries=DB::Select("SELECT nom,postnom,prenom,genre,cl.lib as classe, dep.lib as departement,an.debut as debut,SUM(paie.montant) as montant,an.lib as Annee
        FROM departements dep
        JOIN classes cl ON dep.id = cl.departement_id
        JOIN etudiants etud ON etud.classe_id=cl.id
        JOIN inscriptions insc ON insc.etudiant_id=etud.id
        JOIN paiements paie ON paie.etudiant_id=etud.id
        JOIN annees an ON an.id=insc.annee_id
        WHERE an.id=$annee_id AND cl.id=$classe_id AND insc.actif=1
        GROUP BY nom,postnom,prenom,genre,classe,departement,debut,an.lib
        HAVING montant >= $montant
        ORDER BY dep.lib asc,etud.nom asc");


            if(count($queries) >0){

                $data=[
                    "title" => 'Liste des étudiants de '.$queries[0]->classe.' ayant payé en l\'année '.$queries[0]->Annee,
                    "date" => date("d/m/Y"),
                    "queries"=> $queries
                ];
                // dd($data["Etudiants"]);

                $pdf = Pdf::loadView('Etats/paiement',$data);
                return $pdf->download('Liste_paiement_'.date("d/m/Y H:i:s").'.pdf');
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
