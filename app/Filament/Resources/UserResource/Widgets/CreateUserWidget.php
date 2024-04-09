<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateUserWidget extends Widget implements HasForms
{
    use InteractsWithForms;
    protected static string $view = 'filament.resources.user-resource.widgets.create-user-widget';

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
                Section::make("Définition des Utilisateurs")
                ->Icon("heroicon-o-users")
                ->schema([

                    TextInput::make('name')
                        ->required()
                        ->placeholder("Ex: User")
                        ->maxLength(255)
                        ->columnSpan(1),
                    TextInput::make('email')
                        ->email()
                        ->placeholder("Ex: user@example.com")
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(1),
                    // DateTimePicker::make('email_verified_at'),
                    TextInput::make('password')
                        ->password()
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(1),
                ])->columns(3),
            ])->statePath("data");
    }


    public function create(): void
    {
        User::create($this->form->getState());
        $this->form->fill();
        $this->dispatch('user-created');

        Notification::make()
        ->title('Enregistrement effectué avec succès')
        ->success()
         ->duration(5000)
        ->send();
    }
}
