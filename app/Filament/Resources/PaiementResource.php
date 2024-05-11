<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Annee;
use App\Models\Frais;
use App\Models\Classe;
use Filament\Forms\Get;
use App\Models\Etudiant;
use App\Models\Paiement;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\PaiementResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PaiementResource\RelationManagers;
use App\Filament\Resources\PaiementResource\Widgets\CreatePaiementWidget;

class PaiementResource extends Resource
{
    protected static ?string $model = Paiement::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup ="COGE Management";
    protected static ?int $navigationSort = 8;
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
                        ->live()
                        ->searchable()
                        ->required()
                        ->options(function(){
                            return Classe::query()->pluck("lib","id");
                        }),
                        Select::make('etudiant_id')
                        ->label("Etudiant")
                        ->live()
                        ->options(function(Get $get){
                            return Etudiant::join('inscriptions',"inscriptions.etudiant_id","etudiants.id")->where("etudiants.classe_id",$get("classe_id"))->where("actif",true)->pluck("nom","etudiants.id");
                        })
                        ->required()
                        ->searchable()
                        ->required(),
                    Select::make('frais_id')
                        ->label("Frais")
                        ->required()
                        ->options(function(Get $get){
                          return Frais::query()->whereClasse_id($get("classe_id"))->pluck("montant","id");

                        })
                        ->suffix(" $"),
                    TextInput::make('motif')
                        ->required()
                        ->placeholder("Ex: Frais Académique")
                        ->default("Frais Académique")
                        ->maxLength(255)->columnSpan(2),
                        Select::make('devise')
                            ->label("Devise")
                            ->required()
                            ->options(
                                [
                                    "CDF" =>"CDF",
                                    "USD" =>"USD",
                                ]
                            )->preload()
                            ->searchable(),
                    TextInput::make('montant')
                        ->required()
                        ->placeholder("Ex: 300000")
                        ->numeric(),

                ])->columns(2)->columnSpan(2),
                Section::make()
                ->icon("heroicon-o-banknotes")
                ->description('Uploader le bordereau comme preuve de paiement')
                ->schema([
                    FileUpload::make('bordereau')
                        ->required()->disk("public")->directory('bordereaux'),
                        DateTimePicker::make('datepaie')
                        ->label("Date de Paiment")
                        ->default(now())
                        ->required(),
                        TextInput::make("Etudiant")
                        ->label('Etudiant Séléctionné')
                        ->placeholder(function(Get $get): string
                        {
                            // dd($get("etudiant_id"));
                            if($get("etudiant_id") <> Null){
                                $Etudiant=Etudiant::query()->whereId($get("etudiant_id"))->get(["nom","postnom","prenom"]);

                                return $Etudiant[0]->nom." ".$Etudiant[0]->postnom." ".$Etudiant[0]->prenom;
                            }else{
                                return "";
                            }
                        })
                        ->visible(fn(Get $get):bool => filled($get("etudiant_id")))
                        ->disabled(fn(Get $get):bool => filled($get("etudiant_id")))
                        ->columnSpanFull()

                ])->ColumnSpan(1),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('annee.lib')
                    ->label("Année Académique")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('classe.lib')
                    ->label("Classe")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('etudiant.nom')
                    ->label("Nom")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('etudiant.postnom')
                    ->label("Post Nom")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('etudiant.prenom')
                    ->label("Prénom")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('frais.montant')
                    ->label("Frais")
                    ->suffix(" $")
                    ->sortable(),
                Tables\Columns\TextColumn::make('montant')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('devise')
                    ->label("Devise")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('motif')
                    ->searchable(),
                Tables\Columns\TextColumn::make('datepaie')
                    ->label("Date de Paiement")
                    ->dateTime()
                    ->date("d/m/Y H:i:s")
                    ->sortable(),
                ImageColumn::make('bordereau')
                    ->searchable(),
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
            'index' => Pages\ListPaiements::route('/'),
            'create' => Pages\CreatePaiement::route('/create'),
            'edit' => Pages\EditPaiement::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
           CreatePaiementWidget::class,
        ];
    }
}
