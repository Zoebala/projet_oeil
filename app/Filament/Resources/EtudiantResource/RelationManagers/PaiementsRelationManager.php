<?php

namespace App\Filament\Resources\EtudiantResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Annee;
use App\Models\Paiement;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class PaiementsRelationManager extends RelationManager
{
    protected static string $relationship = 'paiements';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('motif')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('motif')
            ->columns([
                TextColumn::make('motif')
                ->searchable()
                ->toggleable()
                ->sortable(),
                TextColumn::make('montant')
                ->searchable()
                ->toggleable()
                ->sortable(),
                TextColumn::make('datepaie')
                ->label("Date de Paiement")
                ->searchable()
                ->toggleable()
                ->sortable(),
                ImageColumn::make("bordereau")
            ])
            ->filters([
                //
                SelectFilter::make("Annee")
                ->label("Année Acadamique")
                ->relationship("annee","lib"),
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

    public function getTabs():array
    {

        $Annee=Annee::where("id",session("Annee_id") ?? 1)->first();

        return [
            "$Annee->lib"=>Tab::make()
            ->modifyQueryUsing(function(Builder $query)
            {
               $query->where("annee_id",session("Annee_id") ?? 1);

            })
            ->icon("heroicon-o-calendar-days"),


        ];
    }
}
