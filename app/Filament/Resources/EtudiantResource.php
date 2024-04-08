<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Annee;
use App\Models\Classe;
use App\Models\Etudiant;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Inscription;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\EtudiantResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EtudiantResource\RelationManagers;
use App\Filament\Resources\EtudiantResource\Widgets\CreateEtudiantWidget;
use App\Filament\Resources\EtudiantResource\RelationManagers\PaiementsRelationManager;

class EtudiantResource extends Resource
{
    protected static ?string $model = Etudiant::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup ="COGE Management";
    protected static ?int $navigationSort = 5;
    public static function getNavigationBadge():string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor():string
    {
        return "success";
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
                            ->required()
                            ->options(function(){
                                return Classe::query()->pluck("lib","id");
                            }),
                            TextInput::make('matricule')
                                ->placeholder('Ex: 2023/1')
                                ->maxLength(15),
                            TextInput::make('nom')
                                ->placeholder("Ex: Dupon")
                                ->required()
                                ->maxLength(25),
                            TextInput::make('postnom')
                                ->placeholder("Ex: Smith")
                                ->required()
                                ->maxLength(25),
                            TextInput::make('prenom')
                                ->required()
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
                                    ->required(),

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





            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    ToggleColumn::make('inscriptions.actif')
                    ->label("Inscrit ?"),
                Tables\Columns\TextColumn::make('classe.lib')
                    ->label("Classe")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('matricule')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('postnom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('prenom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('genre')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('photo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('province')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('territoire')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('territoireEcole')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('adresseEcole')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ecole')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('optionSecondaire')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('datenais')
                    ->label("Date de naissance")
                    ->date("d/m/Y")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pourcentage')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nompere')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nommere')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('teletudiant')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('teltutaire')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('adresse')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nationalite')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make("Incripition")
                    ->icon("heroicon-o-clipboard-document-list")
                    ->form([
                        Select::make("annee_id")
                        ->label("Année Académique")
                        ->options(function(){
                            return Annee::query()->pluck("lib","id");
                        })->required(),
                        Select::make("classe_id")
                        ->label("Classe")
                        ->options(function(){
                            return Classe::query()->pluck("lib","id");
                        })->required(),

                    ])->modalWidth(MaxWidth::Medium)
                    ->modalIcon("heroicon-o-clipboard-document-list")
                    ->Action(function(array $data,Etudiant $Etudiant){

                        // dd($data);

                        Inscription::create([
                            "actif"=>true,
                            "annee_id"=>$data['annee_id'],
                            "classe_id"=>$data['classe_id'],
                            "etudiant_id"=>$Etudiant->id,
                        ]);

                        Notification::make()
                            ->title("l'étudiant $Etudiant->nom $Etudiant->postnom $Etudiant->prenom a été inscrit avec succès!")
                            // ->successRedirectUrl("presences.list")
                            ->success()
                            ->send();

                    })
                ])->button()
                // ->color('primary')
                ->label("Actions"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make("Passer de classe")
                    ->icon("heroicon-o-forward")
                    ->color("success")
                    ->form([
                        Select::make("annee_id")
                        ->label("Année Académique")
                        ->options(function(){
                            return Annee::query()->pluck("lib","id");
                        })->required(),
                        Select::make("classe_id")
                        ->label("Classe")
                        ->options(function(){
                            return Classe::query()->pluck("lib","id");
                        })->required(),

                    ])->modalWidth(MaxWidth::Medium)
                    ->modalIcon("heroicon-o-chat-bubble-left")
                    ->Action(function(Collection $records,array $data){

                        foreach($records as $record){
                            Inscription::whereEtudiant_id($record->id)->update([
                                "actif"=>true,
                                // "etudiant_id"=>$record->id,
                                "annee_id"=>$data["annee_id"],
                                "classe_id"=>$data["classe_id"],

                            ]);
                            Etudiant::whereId($record->id)->update([
                                "classe_id"=>$data["classe_id"],
                            ]);
                         }
                        Notification::make()
                        ->title("Le passage de classe a été effetué avec succès!")
                        // ->successRedirectUrl("presences.list")
                        ->success()
                        ->send();
                    }),
                ]),
            ]);
            // ->headerActions([
            //     Tables\Actions\CreateAction::make(),
            // ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            PaiementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEtudiants::route('/'),
            'create' => Pages\CreateEtudiant::route('/create'),
            'edit' => Pages\EditEtudiant::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            CreateEtudiantWidget::class,
        ];
    }
}
