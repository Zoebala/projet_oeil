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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
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
                        ->live()
                        ->searchable()
                        ->required(),
                    Select::make('etudiant_id')
                        ->label("Etudiant")
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

                    ->sortable(),
                TextColumn::make('classe.lib')
                    ->label("Classe")
                    ->sortable(),
               TextColumn::make('etudiant.nom')
                    ->label("Nom")
                    ->sortable(),
                TextColumn::make('etudiant.postnom')
                    ->label("Post Nom")
                    ->sortable(),
                TextColumn::make('etudiant.prenom')
                    ->label("Prénnom")
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
