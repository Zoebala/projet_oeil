<?php

namespace App\Filament\Resources\PaiementResource\Widgets;

use App\Models\Annee;
use App\Models\Classe;
use Filament\Forms\Get;
use App\Models\Etudiant;
use App\Models\Paiement;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;

class CreatePaiementWidget extends Widget   implements HasForms
{
    use InteractsWithForms;
    protected static string $view = 'filament.resources.paiement-resource.widgets.create-paiement-widget';

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
                Section::make("Paiement Frais")
                ->icon("heroicon-o-banknotes")
                ->schema([

                    Select::make('annee_id')
                    ->label("Annee Académique")
                    ->required()
                    ->options(function(){
                        return Annee::query()->pluck("lib","id");
                    }),
                    Select::make('classe_id')
                        ->label("Classe")
                        ->required()
                        ->live()
                        ->options(function(){
                            return Classe::query()->pluck("lib","id");
                        }),
                        Select::make('etudiant_id')
                        ->label("Etudiant")
                        ->live()
                        ->options(function(Get $get){
                            return Etudiant::query()->whereClasse_id($get("classe_id"))->pluck("nom","id");
                        })
                        ->required()
                        ->searchable()
                        ->required(),
                    TextInput::make('motif')
                        ->required()
                        ->placeholder("Ex: Frais Académique")
                        ->maxLength(255),
                    TextInput::make('montant')
                        ->required()
                        ->placeholder("Ex: 300000")
                        ->numeric(),
                    DateTimePicker::make('datepaie')
                        ->required(),
                ])->columns(2)->columnSpan(2),
                Section::make()
                ->icon("heroicon-o-banknotes")
                ->description('Uploader le bordereau comme preuve de paiement')
                ->schema([
                    FileUpload::make('bordereau')
                        ->required()->disk("public")->directory('bordereaux'),
                        TextInput::make("Etudiant")
                        ->label('Etudiant Séléctionné')
                        ->placeholder(function(Get $get): string
                        {
                            // dd($get("etudiant_id"));
                            if($get("etudiant_id") <> Null){
                                $Etudiant=Etudiant::query()->whereId($get("etudiant_id"))->get(["nom","postnom","prenom","genre","matricule"]);

                                return $Etudiant[0]->nom." ".$Etudiant[0]->postnom." ".$Etudiant[0]->prenom;
                            }else{
                                return "";
                            }
                        })
                        ->visible(fn(Get $get):bool => filled($get("etudiant_id")))
                        ->disabled(fn(Get $get):bool => filled($get("etudiant_id")))
                        ->columnSpanFull()

                ])->ColumnSpan(1),

            ])->columns(3)->statePath("data");
    }

    public function create(): void
    {
        Paiement::create($this->form->getState());
        $this->form->fill();
        $this->dispatch('paiement-created');

        Notification::make()
        ->title('Enregistrement effectué avec succès')
        ->success()
         ->duration(5000)
        ->send();
    }
}
