<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;

class EtudiantprofilController extends Controller
{
    //

    public function generate_pdf($etudiant){

        $queries=Etudiant::join("classes","classes.id","etudiants.classe_id")
                         ->join("departements","departements.id","classes.departement_id")
                         ->where("etudiants.id",$etudiant)
                         ->get(["etudiants.*","classes.lib as classe","departements.lib as departement"]);


        // dd($queries);
        if(count($queries) > 0){
            $data=[
                "title" => "Etudiant de ".$queries[0]->classe,
                "date" => date("d/m/Y"),
                "queries"=> $queries
            ];

            $pdf = Pdf::loadView('Etats/Etudiantprofil',$data);
            return $pdf->download('profil_etudiant_'.$queries[0]->nom.'_'.$queries[0]->prenom.date("d/m/Y H:i:s",strtotime(now())).'.pdf');
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
