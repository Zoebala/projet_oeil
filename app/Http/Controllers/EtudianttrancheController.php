<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class EtudianttrancheController extends Controller
{
    //

    public function generate_pdf(int $annee_id, int $classe_id,string $etat){

        if($etat =="En ordre"){

            $queries=DB::select("SELECT nom,postnom,genre,prenom,cl.lib as classe,D.lib as departement,An.debut as debut,An.lib as Annee,
                                    SUM(P.montant) as montantpaye,F.montant, F.taux,(F.montant * F.taux) as 'totalapayer',((F.montant * F.taux)-SUM(P.montant)) as reste,
                                    ((F.montant*F.taux) / F.nombre_tranche) as 'Ptranche'
                                    FROM Frais F
                                    JOIN paiements P ON P.frais_id=F.id
                                    JOIN etudiants E ON E.id=P.etudiant_id
                                    JOIN inscriptions Ins ON Ins.etudiant_id=E.id
                                    JOIN classes Cl ON Cl.id=E.classe_id
                                    JOIN departements D ON D.id=Cl.departement_id
                                    JOIN annees An ON An.id=P.annee_id
                                    WHERE An.id=$annee_id AND Cl.id=$classe_id
                                    GROUP BY nom,postnom,genre,F.montant,F.taux,prenom,cl.lib,D.lib,An.debut,F.nombre_tranche,An.lib
                                    HAVING montantpaye >= Ptranche
                                    ORDER BY D.lib,Cl.lib,nom,postnom");
        }else{
            $queries=DB::select("SELECT nom,postnom,genre,prenom,cl.lib as classe,D.lib as departement,An.debut as debut,An.lib as Annee,
                                    SUM(P.montant) as montantpaye,F.montant, F.taux,(F.montant * F.taux) as 'totalapayer',((F.montant * F.taux)-SUM(P.montant)) as reste,
                                    ((F.montant*F.taux) / F.nombre_tranche) as 'Ptranche'
                                    FROM Frais F
                                    JOIN paiements P ON P.frais_id=F.id
                                    JOIN etudiants E ON E.id=P.etudiant_id
                                    JOIN inscriptions Ins ON Ins.etudiant_id=E.id
                                    JOIN classes Cl ON Cl.id=E.classe_id
                                    JOIN departements D ON D.id=Cl.departement_id
                                    JOIN annees An ON An.id=P.annee_id
                                    WHERE An.id=$annee_id AND Cl.id=$classe_id
                                    GROUP BY nom,postnom,genre,F.montant,F.taux,prenom,cl.lib,D.lib,An.debut,F.nombre_tranche,An.lib
                                    HAVING montantpaye < Ptranche
                                    ORDER BY D.lib,Cl.lib,nom,postnom");

        }



        if(count($queries) > 0){
            $data=[
                "title" => "Liste des étudiant de ". $queries[0]->classe." ayant payés la première tranche en l'année \n ".$queries[0]->Annee,
                "date" => date("d/m/Y"),
                "queries"=> $queries
            ];

            // Pdf::setPaper('A4','landscape');
            $pdf = Pdf::loadView('Etats/etudiant_tranche',$data);
            return $pdf->download('Liste_étudiants_première_tranche.pdf');
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
