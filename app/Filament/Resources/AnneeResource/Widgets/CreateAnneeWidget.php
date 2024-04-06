<?php

namespace App\Filament\Resources\AnneeResource\Widgets;

use App\Models\Annee;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
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
                TextInput::make('lib')
                    ->label("Annee Académique")
                    ->placeholder('Ex :2023-2024')
                    ->maxLength(9)
                    ->columnSpan(1),
            ])->statePath("data")->columns(2);
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
