<?php

namespace App\Filament\Pages;

use App\Models\Annee;
use App\Models\Classe;
use Filament\Forms\Get;
use App\Models\Etudiant;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Concerns\HasActions;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Concerns\InteractsWithForms;


class Etats extends Page
{
//    use HasActions, InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.etats';
    protected static ?string $pollingInterval = '5s';

    // public static function canAccess():bool
    // {
    //     return Auth()->user()->hasRole('Admin');


    // }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make("budget Annuel")
                ->hidden(fn():bool => !Auth()->user()->hasRole(["COMGER","Admin","SGACAD","SACAD"]))
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



                    return redirect()->route("budget",compact("annee_id"));
                })
                ->openUrlInNewTab()
                ->tooltip("budget")
                ->button()
                ->icon("heroicon-o-clipboard-document-list")
                ->color("warning"),
                Action::make("Liste_étudiants_par_promotion")
                    ->form([
                        Select::make("annee_id")
                            ->label("Année Académique")
                            ->options(Annee::query()->pluck("lib","id"))
                            ->required(fn(Get $get):bool => !($get("etat")=="true"))
                            ->disabled(fn(Get $get):bool => $get("etat")=="true")
                            ->searchable(),
                        Select::make("classe_id")
                            ->label("Promotion")
                            ->required()
                            ->options(Classe::query()->pluck("lib","id"))
                            ->searchable(),
                         Toggle::make("etat")
                            ->live()
                            ->label("Etudiants non inscrits"),

                    ])
                    ->button()
                    ->hidden(fn():bool => !Auth()->user()->hasRole(["COMGER","Admin","SGACAD","SGACA","SECTION"]))
                    ->icon("heroicon-o-clipboard-document-list")
                    ->modalWidth(MaxWidth::Small)
                    ->modalIcon("heroicon-o-users")
                    ->action(function(array $data){

                        $etat=$data["etat"];

                        if(!$etat){
                            $annee_id=$data["annee_id"];
                            $classe_id=$data["classe_id"];

                            return redirect()->route("etudiant_promotion",compact("annee_id","classe_id"));
                        }else{
                            $classe_id=$data["classe_id"];
                            return redirect()->route("etudiant_promotion_non_inscrits",compact("classe_id"));

                        }



                    })
                    ->openUrlInNewTab()
                    ->tooltip("Liste_étudiants_par_promotion")
                    ->color("warning"),
                Action::make("Element_dossier")
                    ->label("Liste des étudiant n'ayant pas leurs éléments de dossiers")
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
                    ->hidden(fn():bool => !Auth()->user()->hasRole(["Admin","SGACAD","SACAD"]))
                    ->modalWidth(MaxWidth::Medium)
                    ->modalIcon("heroicon-o-users")
                    ->color("warning")
                    ->action(function(array $data){

                        $annee_id=$data["annee_id"];
                        $classe_id=$data["classe_id"];


                        return redirect()->route("element_dossier",compact("annee_id","classe_id"));
                    })
                    ->openUrlInNewTab()
                    ->tooltip("Liste des étudiant n'ayant pas leurs éléments de dossiers"),

                // Action::make("Etudiants Inscrits")
                //     ->label("Listes des étudiants Inscrits")
                //     ->form([
                //         Select::make("annee_id")
                //             ->label("Année Académique")
                //             ->options(Annee::query()->pluck("lib","id"))
                //             ->required()
                //             ->searchable(),
                //         Select::make("classe_id")
                //             ->label("Promotion")
                //             ->required()
                //             ->options(Classe::query()->pluck("lib","id"))
                //             ->searchable(),

                //     ])
                //     ->button()
                //     ->icon("heroicon-o-clipboard-document-list")
                //     ->modalWidth(MaxWidth::Medium)
                //     ->modalIcon("heroicon-o-users")
                //     ->action(function(array $data){

                //         $annee_id=$data["annee_id"];
                //         $classe_id=$data["classe_id"];


                //         return redirect()->route("etudiant.generate_promotion",compact("annee_id","classe_id"));
                //     })
                //     ->openUrlInNewTab()
                //     ->button()
                //     ->icon("heroicon-o-clipboard-document-list")
                //     ->color("success"),
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
                        TextInput::make("Montant")
                        ->label("Montant Payé au moins")
                        ->placeholder("Ex: 200000")
                        ->suffix("FC")


                    ])
                    ->button()
                    ->icon("heroicon-o-clipboard-document-list")
                    ->modalWidth(MaxWidth::Medium)
                    ->modalIcon("heroicon-o-users")
                    ->action(function(array $data){

                        $annee_id=$data["annee_id"];
                        $classe_id=$data["classe_id"];
                        $montant=$data["Montant"];

                        if(isset($montant)){

                            return redirect()->route("etudiants.paye1",compact("annee_id","classe_id","montant"));
                        }else{
                            return redirect()->route("etudiants.paye",compact("annee_id","classe_id"));
                        }
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
                     ->hidden(fn():bool => !Auth()->user()->hasRole(["COMGER","Admin","SGACAD","SACAD","ADMIN_BUDGET","SGADMN"]))
                    ->action(function(array $data){

                        $annee_id=$data["annee_id"];



                        return redirect()->route("frais.paye",compact("annee_id"));
                    })
                    ->openUrlInNewTab()
                    ->tooltip("Liste de tous les frais payés")
                    ->button()
                    ->icon("heroicon-o-clipboard-document-list")
                    ->color("warning"),
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

                    ->hidden(fn():bool => !Auth()->user()->hasRole(["COMGER","Admin","ADMIN_BUDGET","SGADMN"]))
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
                    ->color("warning"),


                // Action::make("Listes Départements"),
            ])->label("_________________________________________Génération des Etats de Sorties_______________________________________________")
            ->Icon("heroicon-o-clipboard-document-list")
            ->button(),
        ];

    }
}
