<?php

namespace App\Filament\Resources\InscriptionResource\Widgets;

use App\Models\Annee;
use App\Models\Classe;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Etudiant;
use Filament\Forms\Form;
use App\Models\Inscription;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateInscriptionWidget extends Widget implements HasForms
{
    use InteractsWithForms;
    protected static string $view = 'filament.resources.inscription-resource.widgets.create-inscription-widget';
    protected int | string | array $columnSpan = 'full';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make("Inscrire l'étudiant")
                ->icon("heroicon-o-clipboard-document-list")
                ->schema([
                    Select::make('annee_id')
                        ->label("Année Académique")
                        ->live()
                        ->options(function(){
                            return Annee::whereId(session("Annee_id") ?? 1)->pluck("lib","id");
                        })
                        ->disabled(function(Get $get, Set $set){

                            if(filled($get("etudiant_id")) && filled($get("annee_id")) && $get("classe_id") && Etudiant::join("inscriptions","inscriptions.etudiant_id","etudiants.id")
                                    ->join("annees","annees.id","inscriptions.annee_id")
                                    ->join("classes","classes.id","inscriptions.classe_id")
                                    ->Where("inscriptions.etudiant_id",$get("etudiant_id"))
                                    ->Where("inscriptions.annee_id",$get("annee_id"))
                                    ->Where("inscriptions.classe_id",$get("classe_id"))
                                    ->exists()){
                                        $set("annee_id",null);
                                        $set("classe_id",null);
                                        $set("etudiant_id",null);
                                        Notification::make()
                                        ->title("cette inscription existe déjà!")
                                        // ->successRedirectUrl("presences.list")
                                        ->danger()
                                        ->send();

                                        return true;

                                }
                            if(!Auth()->user()->hasRole(["Admin","SACAD"])){
                                return true;
                            }

                        })
                        ->required()
                        ->searchable(),
                    Select::make('classe_id')
                        ->label("classe")
                        ->options(function(){
                            return Classe::query()->pluck("lib","id");
                        })
                        ->required()
                        ->afterStateUpdated(function($state, Set $set,){
                            if($state==null){
                                $set("etudiant_id",null);
                            }
                        })
                        ->live()
                        ->searchable()
                        ->disabled(function(){
                            if(!Auth()->user()->hasRole(["Admin","SACAD"])){
                                return true;
                            }
                        })
                        ->required(),
                    Select::make('etudiant_id')
                        ->label("Etudiant")
                        ->live()
                        ->options(function(Get $get){
                            return Etudiant::query()->whereClasse_id($get("classe_id"))->pluck("nom","id");
                        })
                        ->required()
                        ->searchable()
                        ->disabled(function(){
                            if(!Auth()->user()->hasRole(["Admin","SACAD"])){
                                return true;
                            }
                        })
                        ->required(),
                    Toggle::make('actif')
                        ->required(),
                    TextInput::make("Etudiant")
                    ->label('Etudiant Séléctionné')
                    ->placeholder(function(Get $get): string
                    {
                        // dd($get("etudiant_id"));
                        if($get("etudiant_id") <> Null){
                            $Etudiant=Etudiant::query()->whereId($get("etudiant_id"))->get(["nom","postnom","prenom","genre","matricule"]);

                            return $Etudiant[0]->nom." ".$Etudiant[0]->postnom." ".$Etudiant[0]->prenom." | Genre : ".$Etudiant[0]->genre." | Matricule : ".$Etudiant[0]->matricule;
                        }else{
                            return "";
                        }
                    })
                    ->visible(fn(Get $get):bool => filled($get("etudiant_id")))
                    ->disabled(fn(Get $get):bool => filled($get("etudiant_id")))
                    ->columnSpan(2)

                ])->columns(3),
            ])->statePath("data");
    }

    public function create(): void
    {
        Inscription::create($this->form->getState());
        $this->form->fill();
        $this->dispatch('inscription-created');

        Notification::make()
        ->title('Enregistrement effectué avec succès')
        ->success()
         ->duration(5000)
        ->send();

    }
}
