<?php

namespace App\Filament\Resources\RoleResource\Widgets;

use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateRoleWidget extends Widget implements HasForms
{
    use InteractsWithForms;
    protected static string $view = 'filament.resources.role-resource.widgets.create-role-widget';


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
                //
                Section::make("Définition des Rôles")
                ->icon("heroicon-o-finger-print")
                ->schema([
                    TextInput::make("name")
                    ->label("Désignation du rôle")
                    ->placeholder("Ex: DG")
                    ->unique("roles")
                    ->required()
                    ->columnSpan(1)
                ])->columns(2),
            ])->statePath("data");
    }

    public function create(): void
    {
        Role::create($this->form->getState());
        $this->form->fill();
        $this->dispatch('role-created');

        Notification::make()
        ->title('Enregistrement effectué avec succès')
        ->success()
         ->duration(5000)
        ->send();
    }

}
