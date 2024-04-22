<?php

namespace App\Filament\Pages;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Etudiant;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Tables\Concerns\HasActions;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Concerns\InteractsWithForms;


class Etats extends Page
{
//    use HasActions, InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.etats';

    public static function canAccess():bool
    {
        return Auth()->user()->hasRole('Admin');


    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([

                Action::make("Etudiants Inscrits")
                    ->label("Listes des étudiants Inscrits")
                    ->form([
                        Select::make("annee_id")
                            ->label("Année Académique")
                            ->options(Annee::query()->pluck("lib","id"))
                            ->required()
                            ->searchable(),
                        Select::make("classe_id")
                            ->label("Promotion")
                            ->required()
                            ->options(Classe::query()->pluck("lib","id"))
                            ->searchable(),

                    ])
                    ->button()
                    ->icon("heroicon-o-clipboard-document-list")
                    ->modalWidth(MaxWidth::Medium)
                    ->modalIcon("heroicon-o-users")
                    ->action(function(array $data){

                        $annee_id=$data["annee_id"];
                        $classe_id=$data["classe_id"];


                        return redirect()->route("etudiant.generate_promotion",compact("annee_id","classe_id"));
                    })
                    ->openUrlInNewTab()
                    ->button()
                    ->icon("heroicon-o-clipboard-document-list")
                    ->color("success"),
                Action::make("étudiants ayant payé")
                    ->label("Liste des étudiants ayant payé")
                    ->form([
                        Select::make("annee_id")
                            ->label("Année Académique")
                            ->options(Annee::query()->pluck("lib","id"))
                            ->required()
                            ->searchable(),
                        Select::make("classe_id")
                            ->label("Promotion")
                            ->required()
                            ->options(Classe::query()->pluck("lib","id"))
                            ->searchable(),

                    ])
                    ->button()
                    ->icon("heroicon-o-clipboard-document-list")
                    ->modalWidth(MaxWidth::Medium)
                    ->modalIcon("heroicon-o-users")
                    ->action(function(array $data){

                        $annee_id=$data["annee_id"];
                        $classe_id=$data["classe_id"];


                        return redirect()->route("etudiants.paye",compact("annee_id","classe_id"));
                    })
                    ->openUrlInNewTab()
                    ->tooltip("Liste des étudiants ayant payé")
                    ->button()
                    ->icon("heroicon-o-clipboard-document-list"),

                Action::make("Liste de tous les frais payés")
                    ->form([
                        Select::make("annee_id")
                            ->label("Année Académique")
                            ->options(Annee::query()->pluck("lib","id"))
                            ->required()
                            ->searchable(),
                    ])
                    ->button()
                    ->icon("heroicon-o-clipboard-document-list")
                    ->modalWidth(MaxWidth::Medium)
                    ->modalIcon("heroicon-o-users")
                    ->action(function(array $data){

                        $annee_id=$data["annee_id"];



                        return redirect()->route("frais.paye",compact("annee_id"));
                    })
                    ->openUrlInNewTab()
                    ->tooltip("Liste de tous les frais payés")
                    ->button()
                    ->icon("heroicon-o-clipboard-document-list")
                    ->color("success"),
                Action::make("Liste des frais payés par promotion")
                    ->form([
                        Select::make("annee_id")
                            ->label("Année Académique")
                            ->options(Annee::query()->pluck("lib","id"))
                            ->required()
                            ->searchable(),
                        Select::make("classe_id")
                            ->label("Promotion")
                            ->required()
                            ->options(Classe::query()->pluck("lib","id"))
                            ->searchable(),

                    ])
                    ->button()
                    ->icon("heroicon-o-clipboard-document-list")
                    ->modalWidth(MaxWidth::Medium)
                    ->modalIcon("heroicon-o-users")
                    ->action(function(array $data){

                        $annee_id=$data["annee_id"];
                        $classe_id=$data["classe_id"];


                        return redirect()->route("frais_promotion",compact("annee_id","classe_id"));
                    })
                    ->openUrlInNewTab()
                    ->tooltip("Listes des frais payés par promotion"),
                Action::make("Liste_étudiants_en_ordre_première_tranche")
                    ->form([
                        Select::make("annee_id")
                            ->label("Année Académique")
                            ->options(Annee::query()->pluck("lib","id"))
                            ->required()
                            ->searchable(),
                        Select::make("classe_id")
                            ->label("Promotion")
                            ->required()
                            ->options(Classe::query()->pluck("lib","id"))
                            ->searchable(),
                        Select::make("etat")
                            ->label("Situation")
                            ->required()
                            ->options([
                                "En ordre"=>"En ordre",
                                "Non en ordre"=>"Non en ordre",
                            ])
                            ->searchable(),

                    ])
                    ->button()
                    ->icon("heroicon-o-clipboard-document-list")
                    ->modalWidth(MaxWidth::Small)
                    ->modalIcon("heroicon-o-users")
                    ->action(function(array $data){

                        $annee_id=$data["annee_id"];
                        $classe_id=$data["classe_id"];
                        $etat=$data["etat"];


                        return redirect()->route("etudiant_tranche",compact("annee_id","classe_id","etat"));
                    })
                    ->openUrlInNewTab()
                    ->tooltip("Liste des étudiants en ordre avec la première tranche")
                    ->color("success"),
                // Action::make("Listes Départements"),
            ])->label("_________________________________________Génération des Etats de Sorties_______________________________________________")
            ->Icon("heroicon-o-clipboard-document-list")
            ->button(),
        ];

    }
}
