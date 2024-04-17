<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;

class PdfController extends Controller
{
    //

    public function generate_pdf(){

       $Etudiant=Etudiant::all();
    //    dd($Etudiants);
        $data=[
            "title" => "Liste Des promotions",
            "date" => date("d/m/Y"),
            "Etudiants"=> $Etudiant
        ];
        // dd($data["Etudiants"]);
        $pdf = Pdf::loadView('Etats/promotion',$data);
        return $pdf->download('Liste_promotions.pdf');
    }
}
