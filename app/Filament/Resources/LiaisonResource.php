<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Classe;
use App\Models\Liaison;
use Filament\Forms\Get;
use App\Models\Etudiant;
use Filament\Forms\Form;
use Filament\Tables\Table;

use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LiaisonResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LiaisonResource\RelationManagers;

class LiaisonResource extends Resource
{
    protected static ?string $model = Liaison::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $navigationLabel = 'Liaison-User-Etudiant';
    protected static ?string $navigationGroup ="Paramètres";
    protected static ?int $navigationSort = 4;
    public static function getNavigationBadge():string
    {
        if(Auth()->user()->hasRole(["Admin"])){
            return static::getModel()::count();

        }else{

            return static::getModel()::where("user_id",Auth()->user()->id)->count();
        }
    }
    public static function getNavigationBadgeColor():string
    {
        return "success";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("Liaison Utilisateur-Etudiant")
                ->icon("heroicon-o-link")
                ->schema([
                    TextInput::make("Etudiant")
                        ->label('Etudiant(e) Séléctionné(e)')
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
                        ->columnSpanFull(),
                    Select::make('classe_id')
                        ->label("Classe")
                        ->options(Classe::all()->pluck("lib","id"))
                        ->required()
                        ->preload()
                        ->live()
                        ->searchable(),
                    Select::make('etudiant_id')
                    ->label("Etudiant")
                    ->live()
                    ->options(function(Get $get){
                        return Etudiant::join("inscriptions","inscriptions.etudiant_id","etudiants.id")
                                       ->where("etudiants.classe_id",$get("classe_id"))->pluck("nom","etudiants.id");
                    })
                    ->required()
                    ->preload()
                    ->searchable(),

                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make("etudiant.nom")
                ->label("Noms")
                ->getStateUsing(function($record){

                    $Etudiant=Etudiant::whereId($record->etudiant_id)->first();

                    return $Etudiant->nom." ".$Etudiant->postnom." ".$Etudiant->prenom;
                }),
                TextColumn::make("classe")
                ->getStateUsing(function($record){
                    $Etudiant=Etudiant::whereId($record->etudiant_id)->first();

                    $Classe=Classe::whereId($Etudiant->classe_id)->first();

                    return $Classe->lib;
                }),

            ])
            ->filters([
                //
            ])
            ->actions([
                 ActionGroup::make([
                     Tables\Actions\EditAction::make(),
                     Tables\Actions\DeleteAction::make()
                     ->visible(Auth()->user()->hasRole('admin')),
                 ])->button()->label("Actions"),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListLiaisons::route('/'),
            'create' => Pages\CreateLiaison::route('/create'),
            'edit' => Pages\EditLiaison::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if(!Auth()->user()->hasRole(["Admin"])){

            return parent::getEloquentQuery()->where("id",Auth()->user()->id);
        }else{
            return parent::getEloquentQuery();

        }
    }
}
