<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Annee;
use App\Models\Classe;
use Filament\Forms\Get;
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
use Filament\Tables\Columns\ToggleColumn;
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

                Section::make("")
                ->schema([
                    Select::make('annee_id')
                        ->label("Année Académique")
                        ->unique(ignoreRecord:true,table: Annee::class)
                        ->live()
                        ->options(function(){
                            return Annee::query()->pluck("lib","id");
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

                        })
                        ->required()
                        ->searchable(),
                    Select::make('classe_id')
                        ->label("classe")

                        ->unique(ignoreRecord:true,table: Classe::class)
                        ->options(function(){
                            return Classe::query()->pluck("lib","id");
                        })
                        ->required()
                        ->live()
                        ->searchable()
                        ->required(),
                    Select::make('etudiant_id')
                        ->label("Etudiant")
                        //->unique(ignoreRecord:true,table: Etudiant::class)
                        ->live()

                        ->options(function(Get $get){
                            return Etudiant::query()->whereClasse_id($get("classe_id"))->pluck("nom","id");
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
                ToggleColumn::make('actif'),
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
