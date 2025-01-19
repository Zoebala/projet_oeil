<?php

namespace App\Filament\Resources\PaiementResource\Pages;

use App\Models\Annee;
use Filament\Actions;
use Filament\Forms\Set;
use App\Models\Etudiant;
use App\Models\Paiement;
use Livewire\Attributes\On;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PaiementResource;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\PaiementResource\Widgets\CreatePaiementWidget;
use App\Models\Classe;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\TextInput;

class ListPaiements extends ListRecords
{
    protected static string $resource = PaiementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label("Effectuer Paiement")
            ->icon("heroicon-o-banknotes"),
            ActionGroup::make([

                Action::make("étudiants ayant payé")
                ->label("Liste des étudiants ayant payé")
                ->form([

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
                ->slideOver()
                ->icon("heroicon-o-clipboard-document-list")
                ->modalWidth(MaxWidth::Medium)
                ->modalIcon("heroicon-o-users")
                ->action(function(array $data){


                    $classe_id=$data["classe_id"];
                    $montant=$data["Montant"];

                    if(isset($montant)){

                        return redirect()->route("etudiants.paye1",compact("classe_id","montant"));
                    }else{
                        return redirect()->route("etudiants.paye",compact("classe_id"));
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
                            ->options(Annee::whereId(session("Annee_id") ?? 1)->pluck("lib","id"))
                            ->required()
                            ->searchable(),
                    ])
                    ->button()
                    ->icon("heroicon-o-clipboard-document-list")
                    ->modalWidth(MaxWidth::Medium)
                    ->modalIcon("heroicon-o-users")
                     ->hidden(fn():bool => !Auth()->user()->hasRole(["COMGER","Admin","SGACAD","SACAD","ADMIN_BUDGET","SGADMN","DG"]))
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

                        Select::make("classe_id")
                            ->label("Promotion")
                            ->required()
                            ->options(Classe::query()->pluck("lib","id"))
                            ->searchable(),

                    ])
                    ->slideOver()
                    ->button()
                    ->icon("heroicon-o-clipboard-document-list")
                    ->modalWidth(MaxWidth::Medium)
                    ->modalIcon("heroicon-o-users")
                    ->action(function(array $data){


                        $classe_id=$data["classe_id"];


                        return redirect()->route("frais_promotion",compact("classe_id"));
                    })
                    ->openUrlInNewTab()
                    ->tooltip("Listes des frais payés par promotion"),
                Action::make("Liste_étudiants_en_ordre_première_tranche")
                    ->form([
                     
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
                    ->hidden(fn():bool => !Auth()->user()->hasRole(["COMGER","Admin","ADMIN_BUDGET","SGADMN","DG"]))
                    ->icon("heroicon-o-clipboard-document-list")
                    ->modalWidth(MaxWidth::Small)
                    ->modalIcon("heroicon-o-users")
                    ->slideOver()
                    ->action(function(array $data){


                        $classe_id=$data["classe_id"];
                        $etat=$data["etat"];


                        return redirect()->route("etudiant_tranche",compact("annee_id","classe_id","etat"));
                    })
                    ->openUrlInNewTab()
                    ->tooltip("Liste des étudiants en ordre avec la première tranche")
                    ->color("warning"),

            ]),
        ];
    }



    public function getTabs():array
    {

        $Annee=Annee::where("id",session("Annee_id") ?? 1)->first();
        $Etudiant=Etudiant::whereUser_id(Auth()->user()->id)->first();

        if(Auth()->user()->hasRole("CANDIDAT")){

            return [
                "$Annee->lib"=>Tab::make()
                ->modifyQueryUsing(function(Builder $query)
                {
                $query->where("annee_id",session("Annee_id") ?? 1);

                })->badge(Paiement::query()
                ->where("Etudiant_id",$Etudiant->id)->where("annee_id",session("Annee_id") ?? 1)->count())
                ->icon("heroicon-o-calendar-days"),
                'Tous'=>Tab::make()
                ->badge(Paiement::where("Etudiant_id",$Etudiant->id)->count()),

            ];
        }else{

            return [
                "$Annee->lib"=>Tab::make()
                ->modifyQueryUsing(function(Builder $query)
                {
                $query->where("annee_id",session("Annee_id") ?? 1);

                })->badge(Paiement::query()
                ->where("annee_id",session("Annee_id") ?? 1)->count())
                ->icon("heroicon-o-calendar-days"),
                'Tous'=>Tab::make()
                ->badge(Paiement::query()->count()),

            ];
        }
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // CreatePaiementWidget::class,
        ];
    }

    #[On('paiement-created')]
    public function refresh() {}
}
