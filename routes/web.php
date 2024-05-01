<?php

use App\Livewire\Etat;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\FraisController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ElementDossierController;
use App\Http\Controllers\FraispromotionController;
use App\Http\Controllers\EtudianttrancheController;

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
    // return view('welcome');
    return redirect("/admin");
});
//chargement de la page Etat livewire sur filament via sa blade page
Route::get("/Etat",Etat::class);
//Routes pour les états de sorties
Route::get("budget/{annee_id}",[BudgetController::class,"generate_pdf"])->name("budget");
Route::get("etudiants_inscrits/{annee_id}/{classe_id}",[PdfController::class,"generate_pdf"])->name("etudiant.generate_promotion");
Route::get("paiement/{annee_id}/{classe_id}",[PaiementController::class,"generate_pdf"])->name("etudiants.paye");
Route::get("frais_paye/{annee_id}",[FraisController::class,"generate_pdf"])->name("frais.paye");
Route::get("frais_promotion/{annee_id}/{classe_id}",[FraispromotionController::class,"generate_pdf"])->name("frais_promotion");
Route::get("etudiant_tranche/{annee_id}/{classe_id}/{etat}",[EtudianttrancheController::class,"generate_pdf"])->name("etudiant_tranche");
Route::get("element_dossier/{annee_id}/{classe_id}",[ElementDossierController::class,"generate_pdf"])->name("element_dossier");



