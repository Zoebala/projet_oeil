<?php

namespace App\Filament\Resources\InscriptionResource\Widgets;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Etudiant;
use Filament\Forms\Form;
use App\Models\Inscription;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateInscriptionWidget extends Widget implements HasForms
{
    use InteractsWithForms;
    protected static string $view = 'filament.resources.inscription-resource.widgets.create-inscription-widget';
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

                Section::make("")
                ->schema([
                    Select::make('annee_id')
                        ->label("Année Académique")
                        ->options(function(){
                            return Annee::query()->pluck("lib","id");
                        })
                        ->required()
                        ->searchable(),
                    Select::make('classe_id')
                        ->label("classe")
                        ->options(function(){
                            return Classe::query()->pluck("lib","id");
                        })
                        ->required()
                        ->searchable()
                        ->required(),
                    Select::make('etudiant_id')
                        ->label("Etudiant")
                        ->options(function(){
                            return Etudiant::query()->pluck("nom","id");
                        })
                        ->required()
                        ->searchable()
                        ->required(),
                    Toggle::make('actif')
                        ->required(),

                ])->columns(3),
            ])->statePath("data");
    }

    public function create(): void
    {
        Inscription::create($this->form->getState());
        $this->form->fill();
        $this->dispatch('inscription-created');

        Notification::make()
        ->title('Enregistrement effectué avec succès')
        ->success()
         ->duration(5000)
        ->send();

    }
}
