<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Annee;
use App\Models\Classe;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Etudiant;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Inscription;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InscriptionResource\Pages;
use App\Filament\Resources\InscriptionResource\RelationManagers;
use App\Filament\Resources\InscriptionResource\Widgets\CreateInscriptionWidget;

class InscriptionResource extends Resource
{
    protected static ?string $model = Inscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup ="COGE Management";
    protected static ?int $navigationSort = 7;
    public static function getNavigationBadge():string
    {
        return static::getModel()::Where("annee_id",session("Annee_id") ?? 1 )
                                ->where("actif",true)
                                ->count();
    }
    public static function getNavigationBadgeColor():string
    {
        return "success";
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
                            return Annee::query()->pluck("lib","id");
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
                        ->required(),
                    Select::make('etudiant_id')
                        ->label("Etudiant")
                        ->live()
                        ->options(function(Get $get){
                            return Etudiant::join('inscriptions',"inscriptions.etudiant_id","etudiants.id")
                                            ->where("etudiants.classe_id",$get("classe_id"))
                                            ->where("actif",true)
                                            ->pluck("nom","etudiants.id");
                        })
                        ->required()
                        ->searchable()
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ToggleColumn::make('actif')
                ->afterStateUpdated(function ($record, $state) {
                    // Runs after the state is saved to the database.
                    // dd($state,$record);
                    $Etudiant=Etudiant::whereId($record->etudiant_id)->first();
                    // dd($Etudiant);
                    if($state==true){

                        Notification::make()
                                ->title("L'étudiant(e) $Etudiant->nom $Etudiant->postnom $Etudiant->prenom a  été activé(e) avec succès!")
                                ->success()
                                ->send();
                    }else{
                        Notification::make()
                                ->title("L'étudiant(e) $Etudiant->nom $Etudiant->postnom $Etudiant->prenom a  été desactivé(e) avec succès!")
                                ->danger()
                                ->send();

                    }
                })->disabled(fn():bool => !Auth()->user()->hasRole(["Admin","SACAD"])),
                TextColumn::make('annee.lib')
                    ->label("Année Académique")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('classe.lib')
                    ->label("Classe")
                    ->sortable(),
               TextColumn::make('etudiant.nom')
                    ->label("Nom")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('etudiant.postnom')
                    ->label("Post Nom")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('etudiant.prenom')
                    ->label("Prénnom")
                    ->searchable()
                    ->placeholder("Pas de prénom")
                    ->sortable(),
               TextColumn::make('created_at')
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
                SelectFilter::make("Annee")
                ->label("Année Acadamique")
                ->relationship("annee","lib")
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),


                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInscriptions::route('/'),
            'create' => Pages\CreateInscription::route('/create'),
            'edit' => Pages\EditInscription::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            CreateInscriptionWidget::class,
        ];
    }
}
