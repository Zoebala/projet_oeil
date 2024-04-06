<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FraisResource\Pages;
use App\Filament\Resources\FraisResource\RelationManagers;
use App\Models\Frais;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FraisResource extends Resource
{
    protected static ?string $model = Frais::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('montant')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('nombre_tranche')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('taux')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('motif')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('annee_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('classe_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('montant')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre_tranche')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('taux')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('motif')
                    ->searchable(),
                Tables\Columns\TextColumn::make('annee_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('classe_id')
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
            'index' => Pages\ListFrais::route('/'),
            'create' => Pages\CreateFrais::route('/create'),
            'edit' => Pages\EditFrais::route('/{record}/edit'),
        ];
    }
}
