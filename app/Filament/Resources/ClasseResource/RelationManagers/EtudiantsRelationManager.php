<?php

namespace App\Filament\Resources\ClasseResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class EtudiantsRelationManager extends RelationManager
{
    protected static string $relationship = 'etudiants';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nom')
            ->columns([
                IconColumn::make('inscriptions.actif')
                    ->boolean()
                    ->label("Inscription Active ?")
                    ->placeholder("Non Inscrit(e)")
                    // ->default(false)
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('nom'),

                Tables\Columns\TextColumn::make('matricule')
                    ->label("Matricule")
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nom')
                    ->label("Nom Complet")
                    ->getStateUsing(fn($record)=> $record->nom." ".$record->postnom." ".$record->prenom)
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('genre')
                    ->label("Genre")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('photo')
                    ->label("Photo")
                    ->placeholder("Pas de Profil")
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
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
}
