<?php

namespace App\Filament\Resources\AnneeResource\Widgets;

use App\Models\Annee;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateAnneeWidget extends Widget implements HasForms
{
    use InteractsWithForms;
    protected static string $view = 'filament.resources.annee-resource.widgets.create-annee-widget';

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
                Section::make("Définition de l'année Académique")
                ->icon("heroicon-o-calendar-days")
                ->schema([

                    TextInput::make('lib')
                        ->label("Annee Académique")
                        ->required()
                        ->placeholder('Ex :2023-2024')
                        ->unique(ignoreRecord:true,table: Annee::class)
                        ->live(debounce:1500)
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
                ])->columns(2),
            ])->statePath("data");
    }

    public function create(): void
    {
        Annee::create($this->form->getState());
        $this->form->fill();
        $this->dispatch('annee-created');

        Notification::make()
        ->title('Enregistrement effectué avec succès')
        ->success()
         ->duration(5000)
        ->send();
    }
}
