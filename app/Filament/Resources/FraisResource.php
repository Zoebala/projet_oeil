<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Annee;
use App\Models\Frais;
use App\Models\Classe;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FraisResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FraisResource\RelationManagers;
use App\Filament\Resources\FraisResource\Widgets\CreateFraisWidget;

class FraisResource extends Resource
{
    protected static ?string $model = Frais::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup ="COGE Management";

    protected static ?int $navigationSort = 6;
    protected static ?string $pollingInterval = '5s';
    public static function getNavigationBadge():string
    {
        return static::getModel()::Where("annee_id",session("Annee_id") ?? 1)->count();
    }
    public static function getNavigationBadgeColor():string
    {
        return "success";
    }

    public static function canAccess(): bool
    {
        if(self::canViewAny()){
            return Annee::isActive();
        }
        return false;
    }

    public static function canViewAny(): bool
    {
        return static::can('viewAny');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Définition Frais')
                ->icon("heroicon-o-banknotes")
                ->schema([

                    Select::make('annee_id')
                    ->label("Annee Académique")
                    ->options(function(){
                        return Annee::whereId(session("Annee_id") ?? 1)->pluck('lib',"id");
                    })
                    ->required(),
                    Select::make('classe_id')
                    ->label("classe")
                    ->required()
                    ->live()
                    ->afterStateUpdated(function(Get $get, Set $set)
                    {
                        if( (filled($get("annee_id")) && filled($get("classe_id")))){
                            $Frais=Frais::whereAnnee_id($get("annee_id"))->whereClasse_id($get("classe_id"))->exists();

                            if($Frais){
                                $set("annee_id",null);
                                $set("classe_id",null);
                                Notification::make()
                                ->title("Les Frais Académiques ont déjà été définis pour cette classe")
                                ->danger()
                                ->duration(5000)
                                ->send();
                            }
                        }
                    })
                    ->options(function(){
                        return Classe::all()->pluck('lib',"id");
                    })
                    ->preload()
                    ->searchable(),
                    TextInput::make('motif')
                        ->required()
                        ->placeholder("Ex: Frais Académique")
                        ->default("Frais Académique")
                        ->maxLength(255),
                    TextInput::make('montant')
                        ->required()
                        ->placeholder("Ex: 500")
                        ->suffix("$")
                        ->numeric(),
                    TextInput::make('nombre_tranche')
                        ->required()
                        ->placeholder("Ex: 3")
                        ->numeric(),
                    TextInput::make('taux')
                        ->required()
                        ->placeholder("Ex:2750")
                        ->numeric(),
                ])->columns(3),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('annee.lib')
                ->label("Annee")
                ->numeric()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
               Tables\Columns\TextColumn::make('classe.lib')
                ->label("Classe")
                ->numeric()
                ->sortable(),
                Tables\Columns\TextColumn::make('montant')
                    ->numeric()
                    ->sortable()
                    ->suffix(" $"),
                Tables\Columns\TextColumn::make('nombre_tranche')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('taux')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('motif')
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
                SelectFilter::make("Annee")
                ->label("Année Acadamique")
                ->relationship("annee","lib"),
            ])
            ->actions([
                ActionGroup::make([

                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ViewAction::make()->SlideOver(),
                ])
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
            'index' => Pages\ListFrais::route('/'),
            'create' => Pages\CreateFrais::route('/create'),
            'edit' => Pages\EditFrais::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            CreateFraisWidget::class,
        ];
    }
}
