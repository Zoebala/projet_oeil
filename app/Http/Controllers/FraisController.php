<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;


class FraisController extends Controller
{
    //

    public function generate_pdf(){
    //récupération de l'année en cours

        $annee_id=session("Annee_id")[0];

        
       $queries=DB::select("SELECT nom,postnom,genre,prenom,cl.lib as classe,D.lib as departement,An.debut as debut,An.lib as annee,
                                SUM(P.montant) as montantpaye,F.montant, F.taux,(F.montant * F.taux) as 'totalapayer',((F.montant * F.taux)-SUM(P.montant)) as reste
                                -- SUM(COALESCE(P.montant)) as Total
                                FROM Frais F
                                JOIN paiements P ON P.frais_id=F.id
                                JOIN etudiants E ON E.id=P.etudiant_id
                                JOIN inscriptions Ins ON Ins.etudiant_id=E.id
                                JOIN classes Cl ON Cl.id=E.classe_id
                                JOIN departements D ON D.id=Cl.departement_id
                                JOIN annees An ON An.id=P.annee_id
                                WHERE An.id=$annee_id AND Ins.actif=1
                                GROUP BY nom,postnom,genre,F.montant,F.taux,prenom,cl.lib,D.lib,An.debut
                                ORDER BY D.lib,Cl.lib,nom,postnom");


        if(count($queries)>0){

            $data=[
                "title" => 'Liste de tous les frais payés en '.$queries[0]->annee,
                "date" => date("d/m/Y"),
                "queries"=> $queries
            ];


            $pdf = Pdf::loadView('Etats/frais_paye',$data);
            return $pdf->stream('Liste_frais_payés_'.date("d/m/Y H:i:s").'.pdf');
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
