<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->string("matricule",15)->nullable();
            $table->string("nom",25);
            $table->string("postnom",25);
            $table->string("prenom",25);
            $table->string("genre",1);
            $table->string("photo")->nullable();
            $table->string("province",50)->nullable();
            $table->string("territoire",50)->nullable();
            $table->string("territoireEcole",50)->nullable();
            $table->string("adresseEcole",50)->nullable();
            $table->string("ecole",50)->nullable();
            $table->string("optionSecondaire",50)->nullable();
            $table->date("datenais")->nullable();
            $table->integer("pourcentage")->nullable();
            $table->string("nompere",25)->nullable();
            $table->string("nommere",25)->nullable();
            $table->string("teletudiant",10)->nullable();
            $table->string("teltutaire",25)->nullable();
            $table->string("adresse",50)->nullable();
            $table->string("nationalite",20)->nullable();
            // $table->boolean("statut")->default(1);
            $table->unsignedBigInteger("classe_id");
            $table->timestamps();



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
