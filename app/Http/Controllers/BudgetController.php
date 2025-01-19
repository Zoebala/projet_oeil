<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class BudgetController extends Controller
{
    //

    public function generate_pdf(){

        $annee_id=session("Annee_id")[0];

        $queries=DB::Select("SELECT f.montant, f.taux,an.lib as Annee,cl.lib as Promotion,count(etud.id) as Effectif,(f.montant*f.taux) as 'Montantapayer',((f.montant*f.taux)*count(etud.id)) as 'MontantPromotion',cl.id
                                FROM frais as f
                                JOIN annees as an ON an.id=f.annee_id
                                JOIN classes as cl ON cl.id=f.classe_id
                                JOIN etudiants as etud ON etud.classe_id=cl.id
                                JOIN inscriptions as ins ON ins.etudiant_id=etud.id
                                WHERE an.id=$annee_id AND ins.actif=1
                                GROUP BY cl.id,f.montant,f.taux,an.lib,cl.lib
                                ");

        // dd($queries[0]->departement);
        if(count($queries) >0){

        $data=[
        "title" => 'Budget de l\'année '.$queries[0]->Annee,
        "date" => date("d/m/Y"),
        "queries"=> $queries
        ];
        // dd($data["Etudiants"]);

        $pdf = Pdf::loadView('Etats/budget',$data);
        return $pdf->download('budget_'.date("d/m/Y H:i:s").'.pdf');
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
