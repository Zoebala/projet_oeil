<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EtudiantResource\Pages;
use App\Filament\Resources\EtudiantResource\RelationManagers;
use App\Models\Etudiant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EtudiantResource extends Resource
{
    protected static ?string $model = Etudiant::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('matricule')
                    ->maxLength(15),
                Forms\Components\TextInput::make('nom')
                    ->required()
                    ->maxLength(25),
                Forms\Components\TextInput::make('postnom')
                    ->required()
                    ->maxLength(25),
                Forms\Components\TextInput::make('prenom')
                    ->required()
                    ->maxLength(25),
                Forms\Components\TextInput::make('genre')
                    ->required()
                    ->maxLength(1),
                Forms\Components\TextInput::make('photo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('province')
                    ->maxLength(50),
                Forms\Components\TextInput::make('territoire')
                    ->maxLength(50),
                Forms\Components\TextInput::make('territoireEcole')
                    ->maxLength(50),
                Forms\Components\TextInput::make('adresseEcole')
                    ->maxLength(50),
                Forms\Components\TextInput::make('ecole')
                    ->maxLength(50),
                Forms\Components\TextInput::make('optionSecondaire')
                    ->maxLength(50),
                Forms\Components\DatePicker::make('datenais')
                    ->required(),
                Forms\Components\TextInput::make('pourcentage')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('nompere')
                    ->required()
                    ->maxLength(25),
                Forms\Components\TextInput::make('nommere')
                    ->required()
                    ->maxLength(25),
                Forms\Components\TextInput::make('teletudiant')
                    ->tel()
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('teltutaire')
                    ->tel()
                    ->required()
                    ->maxLength(25),
                Forms\Components\TextInput::make('adresse')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('nationalite')
                    ->maxLength(20),
                Forms\Components\TextInput::make('classe_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                Tables\Columns\TextColumn::make('photo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('province')
                    ->searchable(),
                Tables\Columns\TextColumn::make('territoire')
                    ->searchable(),
                Tables\Columns\TextColumn::make('territoireEcole')
                    ->searchable(),
                Tables\Columns\TextColumn::make('adresseEcole')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ecole')
                    ->searchable(),
                Tables\Columns\TextColumn::make('optionSecondaire')
                    ->searchable(),
                Tables\Columns\TextColumn::make('datenais')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pourcentage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nompere')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nommere')
                    ->searchable(),
                Tables\Columns\TextColumn::make('teletudiant')
                    ->searchable(),
                Tables\Columns\TextColumn::make('teltutaire')
                    ->searchable(),
                Tables\Columns\TextColumn::make('adresse')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nationalite')
                    ->searchable(),
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
            'index' => Pages\ListEtudiants::route('/'),
            'create' => Pages\CreateEtudiant::route('/create'),
            'edit' => Pages\EditEtudiant::route('/{record}/edit'),
        ];
    }
}
