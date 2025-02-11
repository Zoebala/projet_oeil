<?php

use App\Models\User;
use App\Livewire\Etat;
use App\Models\Section;
use App\Models\Etudiant;
use App\Models\Actualite;
use App\Models\Departement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FraisController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ElementDossierController;
use App\Http\Controllers\EtudiantprofilController;
use App\Http\Controllers\FraispromotionController;
use App\Http\Controllers\EtudianttrancheController;
use App\Http\Controllers\EtudiantparpromotionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    $Departements=Departement::all();
    $Sections=Section::all();
    $Actualites=Actualite::query()->Orderby('id',"desc")->take(5)->get();

    //On vérifie s'il y a un utilisateur authentifié
    if(Auth::user()){
        $Etudiant=Etudiant::where("user_id",Auth()->user()->id)->first();

        $User=User::whereId(Auth()->user()->id)->first();
        return view('welcome',compact('Departements','Sections',"Actualites","Etudiant","User"));
    }

    return view('welcome',compact('Departements','Sections',"Actualites"));
    // return redirect("/admin");
});
//chargement de la page Etat livewire sur filament via sa blade page
Route::get("/Etat",Etat::class);
//Routes pour les états de sorties
Route::get("budget",[BudgetController::class,"generate_pdf"])->name("budget");
// Route::get("etudiants_inscrits/{annee_id}/{classe_id}",[PdfController::class,"generate_pdf"])->name("etudiant.generate_promotion");
Route::get("paiement/{classe_id}/{montant}",[PaiementController::class,"generate_pdf1"])->name("etudiants.paye1");
Route::get("paiement/{classe_id}",[PaiementController::class,"generate_pdf"])->name("etudiants.paye");
Route::get("frais_paye",[FraisController::class,"generate_pdf"])->name("frais.paye");
Route::get("frais_promotion/{classe_id}",[FraispromotionController::class,"generate_pdf"])->name("frais_promotion");
Route::get("etudiant_tranche/{classe_id}/{etat}",[EtudianttrancheController::class,"generate_pdf"])->name("etudiant_tranche");
Route::get("element_dossier/{classe_id}",[ElementDossierController::class,"generate_pdf"])->name("element_dossier");
Route::get("etudiant_promotion/{classe_id}",[EtudiantparpromotionController::class,"generate_pdf"])->name("etudiant_promotion");
Route::get("etudiant_promotion_non_inscrits/{classe_id}",[EtudiantparpromotionController::class,"generate_pdf1"])->name("etudiant_promotion_non_inscrits");
Route::get("etudiant.profil/{etudiant}",[EtudiantprofilController::class,"generate_pdf"])->name("etudiant.profil");






