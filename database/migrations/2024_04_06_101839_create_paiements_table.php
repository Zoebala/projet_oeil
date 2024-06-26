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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->integer("montant");
            $table->string("motif");
            $table->string("devise");
            $table->datetime("datepaie");
            $table->string("bordereau");
            $table->unsignedBigInteger("classe_id");
            $table->unsignedBigInteger("annee_id");
            $table->unsignedBigInteger("etudiant_id");
            $table->unsignedBigInteger("frais_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
