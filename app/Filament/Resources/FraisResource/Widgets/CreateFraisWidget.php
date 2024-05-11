<?php

namespace App\Filament\Resources\FraisResource\Widgets;

use App\Models\Annee;
use App\Models\Frais;
use App\Models\Classe;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateFraisWidget extends Widget  implements HasForms
{
    use InteractsWithForms;
    protected static string $view = 'filament.resources.frais-resource.widgets.create-frais-widget';

    protected int | string | array $columnSpan = 'full';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
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
                        return Annee::all()->pluck('lib',"id");
                    })
                    ->searchable()
                    ->required(),
                    Select::make('classe_id')
                    ->label("Classe")
                    ->searchable()
                    ->required()
                    ->options(function(){
                        return Classe::all()->pluck('lib',"id");
                    }),
                    TextInput::make('motif')
                        ->required()
                        ->placeholder("Ex: Frais Académique")
                        ->default("Frais Académique")
                        ->maxLength(255),
                    TextInput::make('montant')
                        ->required()
                        ->placeholder("Ex: 500")
                        ->suffix("$")
                        ->maxLength(4)
                        ->minLength(1)
                        ->numeric(),
                    TextInput::make('nombre_tranche')
                        ->required()
                        ->placeholder("Ex: 3")
                        ->numeric(),
                    TextInput::make('taux')
                        ->required()
                        ->placeholder("Ex: 2750")
                        ->numeric(),
                ])->columns(3),

            ])->statePath("data");
    }

    public function create(): void
    {
        Frais::create($this->form->getState());
        $this->form->fill();
        $this->dispatch('frais-created');

        Notification::make()
        ->title('Enregistrement effectué avec succès')
        ->success()
         ->duration(5000)
        ->send();
    }
}
