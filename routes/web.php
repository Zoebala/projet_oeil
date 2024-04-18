<?php

use App\Livewire\Etat;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PaiementController;

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
Route::get("etudiants_inscrits",[PdfController::class,"generate_pdf"])->name("etudiant.generate_promotion");
Route::get("paiement",[PaiementController::class,"generate_pdf"])->name("etudiants.paye");



