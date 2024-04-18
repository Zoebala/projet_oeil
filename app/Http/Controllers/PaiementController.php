<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PaiementController extends Controller
{
    //

    public function generate_pdf(){

        //récupération de l'année en cours
        $annee=(int)date("Y")-1;
        $queries=DB::Select("SELECT nom,postnom,prenom,genre,cl.lib as classe, dep.lib as departement,an.debut as debut,SUM(paie.montant) as montant
                            FROM departements dep
                            JOIN classes cl ON dep.id = cl.departement_id
                            JOIN etudiants etud ON etud.classe_id=cl.id
                            JOIN inscriptions insc ON insc.etudiant_id=etud.id
                            JOIN paiements paie ON paie.etudiant_id=etud.id
                            JOIN annees an ON an.id=insc.annee_id
                            WHERE debut=$annee
                            GROUP BY nom,postnom,prenom,genre,classe,departement,debut
                            ORDER BY dep.lib asc,etud.nom asc");


         $data=[
             "title" => 'Liste des étudiants ayant payé en l\'année '.(date("Y")-1).'-'.date("Y"),
             "date" => date("d/m/Y"),
             "queries"=> $queries
         ];
         // dd($data["Etudiants"]);
         $pdf = Pdf::loadView('Etats/paiement',$data);
         return $pdf->download('Liste_paiement.pdf');
     }
}
