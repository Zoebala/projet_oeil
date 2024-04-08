<?php

namespace App\Imports;

use App\Models\Etudiant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EtudiantsImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        // dd($row);
        return new Etudiant([
            //
            "nom"=>$row["nom"],
            "postnom"=>$row["postnom"],
            "prenom"=>$row["prenom"],
            // "datenais"=>$row["datenais"],
            "genre"=>$row["genre"],
            // "matricule"=>$row["matricule"],
            "classe_id"=>$row["classe_id"],
            // "email"=>$row["email"],
            // "province"=>$row["province"],
            // "territoire"=>$row["territoire"],
            // "ecole"=>$row["ecole"],
            // "territoireEcole"=>$row["territoireEcole"],
            // "adresseEcole"=>$row["adresseEcole"],
            // "adresse"=>$row["adresse"],
            // "optionSecondaire"=>$row["optionSecondaire"],
            // "pourcentage"=>$row["pourcentage"],
            // "nompere"=>$row["nompere"],
            // "nommere"=>$row["nommere"],
            // "nationalite"=>$row["nationalite"],
            "teletudiant"=>$row["teletudiant"],
            // "teltutaire"=>$row["teltutaire"],
        ]);

    }
    // public function rules():array
    // {
    //     return [
    //         "nom"=>"required",
    //         "postnom"=>"required",
    //         "prenom"=>"required",
    //         "datenais"=>"required|date",
    //         "genre"=>"required|max:1|",
    //         "matricule"=>"",
    //         "classe_id"=>"required|int",
    //         // "email"=>"required",
    //         "province"=>"",
    //         "territoire"=>"",
    //         "ecole"=>"",
    //         "territoireEcole"=>"",
    //         "adresseEcole"=>"",
    //         "adresse"=>"",
    //         "optionSecondaire"=>"",
    //         "pourcentage"=>"",
    //         "nompere"=>"",
    //         "nommere"=>"",
    //         "nationalite"=>"",
    //         "teletudiant"=>"",
    //         "teltutaire"=>"",
    //     ];
    // }
}
