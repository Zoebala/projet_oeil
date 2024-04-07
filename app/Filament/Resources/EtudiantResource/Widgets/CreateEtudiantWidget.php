<?php

namespace App\Filament\Resources\EtudiantResource\Widgets;

use App\Models\Classe;
use App\Models\Etudiant;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateEtudiantWidget extends Widget implements HasForms
{
    use InteractsWithForms;
    protected static string $view = 'filament.resources.etudiant-resource.widgets.create-etudiant-widget';
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
                Wizard::make([
                    Step::make("Information")
                    ->schema([
                        Section::make()
                        ->schema([
                            Select::make('classe_id')
                            ->label("Classe")
                            // ->required()
                            ->options(function(){
                                return Classe::query()->pluck("lib","id");
                            }),
                            TextInput::make('matricule')
                                ->placeholder('Ex: 2023/1')
                                ->maxLength(15),
                            TextInput::make('nom')
                                ->placeholder("Ex: Dupon")
                                // ->required()
                                ->maxLength(25),
                            TextInput::make('postnom')
                                ->placeholder("Ex: Smith")
                                // ->required()
                                ->maxLength(25),
                            TextInput::make('prenom')
                                // ->required()
                                ->placeholder('Ex: joseph')
                                ->maxLength(25),
                                Select::make('genre')
                                ->options([
                                    "F"=>"F",
                                    "M"=>"M",
                                ]),
                                    // ->required(),
                                DatePicker::make('datenais')
                                    ->label("Date de Naissance")
                                    ->columnSpanFull()
                                    // ->required(),

                        ])->columnSpan(2)->columns(2),
                        Section::make()
                        ->schema([
                            FileUpload::make('photo')
                            ->disk("public")->directory("photos")
                            ->visibleOn("edit"),
                            // ->maxLength(255),
                        ])->columnSpan(1)->columns(1),

                    ])->columns(3),
                    Step::make("Contact & Provenance")
                    ->schema([
                        TextInput::make('teletudiant')
                            ->label("Téléphone Etudiant")
                            ->tel()
                            // ->required()
                            ->placeholder("Ex: 089XXXXXXX")
                            ->maxLength(10),
                        TextInput::make('teltutaire')
                            ->label("Téléphone Tutaire")
                            ->tel()
                            // ->required()
                            ->placeholder("Ex: 089XXXXXXX")
                            ->maxLength(25),
                        TextInput::make('adresse')
                            // ->required()
                            ->placeholder("Ex: 12, Av. Reservoi Q/Noki")
                            ->maxLength(50),
                         Select::make('province')
                            ->options([
                                    "Kongo-Central"=>"Kongo-Central",
                                    "Kinshasa"=>"Kinshasa",
                                    "Sud-kivu"=>"Sud-kivu",
                                    "Nord-kivu"=>"Nord-kivu",
                                    "Lualaba"=>"Lualaba",
                                    "Sub Ubangi"=>"Sub Ubangi",
                                    "Nord Ubangi"=>"Nord Ubangi",
                                    "Kwilu"=>"Kwilu",
                                    "Haut-Katanga"=>"Haut-Katanga",
                                    "Haut-Katanga"=>"Haut-Lomami",
                                    "Haut-Uélé"=>"Haut-Uélé",
                                    "Bas-Uélé"=>"Bas-Uélé",
                                    "Province Orientale"=>"Province Orientale",
                                    "Tshuapa"=>"Tshuapa",
                                    "Mai-ndombe"=>"Mai-ndombe",
                                    "Tshopo"=>"Tshopo",
                                    "Kwango"=>"Kwango",
                                    "Sankuru"=>"Kwango",

                            ])
                            ->searchable()
                            ->label("Province D'origine"),
                            // ->maxLength(50),
                        TextInput::make('territoire')
                            ->label("Territoire D'origine")
                            ->placeholder("Ex : Mbanza-Ngungu")
                            ->maxLength(50),
                        TextInput::make('territoireEcole')
                            ->label("Territoire Ecole")
                            ->placeholder("Ex : Mbanza-Ngungu")
                            ->maxLength(50),
                        TextInput::make('adresseEcole')
                            ->label("Adresse Ecole")
                            ->placeholder("Ex : 13, Av. Reservoir Q/Noki")
                            ->maxLength(50),
                        TextInput::make('ecole')
                            ->label("Ecole de Provenance")
                            ->placeHolder("Ex: Edap/ISP Mbanza-ngungu")
                            ->maxLength(50),
                        TextInput::make('optionSecondaire')
                            ->label("Option Faite au secondaire")
                            ->placeHolder("Ex: Mécanique Générale")
                            ->maxLength(50),
                    ])->columns(3),
                    Step::make("Autres Informations")
                    ->schema([

                        TextInput::make('pourcentage')
                            ->label("Pourcentage Obtenu")
                            ->placeHolder("Ex: 62")
                            // ->required()
                            ->numeric(),
                        TextInput::make('nompere')
                            ->label("Nom du père")
                            ->placeholder("Ex: Smith")
                            // ->required()
                            ->maxLength(25),
                        TextInput::make('nommere')
                            ->label("Nom de la mère")
                            ->placeholder("Ex: Saldivar")
                            // ->required()
                            ->maxLength(25),
                        TextInput::make('nationalite')
                            ->placeholder("Ex: congolaise")
                            ->maxLength(20),
                    ])->columns(2),
                ])->columnSpanFull(),





            ])->statePath("data");
    }

    public function create(): void
    {
        Etudiant::create($this->form->getState());
        $this->form->fill();
        $this->dispatch('etudiant-created');

        Notification::make()
        ->title('Enregistrement effectué avec succès')
        ->success()
         ->duration(5000)
        ->send();
    }
}
