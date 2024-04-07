<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Annee;
use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Paiement;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
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
                        ->required()
                        ->options(function(){
                            return Classe::query()->pluck("lib","id");
                        }),
                    Select::make('etudiant_id')
                        ->label("Etudiant")
                        ->required()
                        ->options(function(){
                            return Etudiant::query()->pluck("nom","id");
                        }),
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
                        ->required(),

                ])->ColumnSpan(1),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('montant')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('motif')
                    ->searchable(),
                Tables\Columns\TextColumn::make('datepaie')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bordereau')
                    ->searchable(),
                Tables\Columns\TextColumn::make('classe_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('annee_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('etudiant_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('frais_id')
                    ->numeric()
                    ->sortable(),
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
