<?php

namespace App\Livewire;

use Filament\Tables;
use App\Models\Annee;
use App\Models\Section;
use Filament\Forms\Get;
use Livewire\Component;
use App\Models\Etudiant;
// use Tables\Actions\EditAction;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class Etat extends Component implements HasForms,HasTable
{
    use InteractsWithForms,InteractsWithTable;

    public function render()
    {
        return view('livewire.etat');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('lib')
                    ->required()
                    ->unique(ignoreRecord:true,table: Annee::class)
                    ->placeholder('Ex :2023-2024')
                    ->live(debounce:1000)
                    ->afterStateUpdated(function(Get $get,Set $set){
                        $set("debut",substr($get("lib"),0,4));
                        $set("fin",substr($get("lib"),5,9));
                    })
                    ->maxLength(9)
                    ->columnSpan(1),
                Hidden::make('debut')
                    ->columnSpan(1),
                Hidden::make('fin')
                    ->columnSpan(1),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            // ActionGroup::make([

                Action::make("Listes Des Etudiants ayant payé")
                ->url(fn(Collection $Student) =>route("etudiant.generate_promotion",$Student))
                ->openUrlInNewTab(),
                Action::make("Listes des paiements journaliers"),
                Action::make("Listes des étudiants non inscrits"),
                Action::make("Listes Départements"),
                // Action::make("Listes Départements"),
            // ])->label("Génération des Etats de Sorties")
            // ->Icon("heroicon-o-clipboard-document-list")
            // ->button(),
        ];

    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Annee::query())
            ->columns([
                TextColumn::make('lib')
                    ->label("Année Académique"),
                    // ->searchable(),
                TextColumn::make('debut')
                    ->label("Début"),
                    // ->searchable(),
                TextColumn::make('fin')
                    ->label("Fin"),
                    // ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                //
            ])
            ->actions([

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
