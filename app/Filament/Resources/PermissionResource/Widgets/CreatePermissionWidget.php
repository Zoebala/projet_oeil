<?php

namespace App\Filament\Resources\PermissionResource\Widgets;

use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Spatie\Permission\Models\Permission;
use Filament\Forms\Concerns\InteractsWithForms;

class CreatePermissionWidget extends Widget  implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.resources.permission-resource.widgets.create-permission-widget';

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

               Section::make("Définition des Permissions")
               ->icon("heroicon-o-key")
               ->schema([
                   TextInput::make("name")
                   ->label("Désignation de la Permission")
                   ->placeholder("Ex: Create Etudiant")
                   ->unique(ignoreRecord:true,table: Permission::class)
                   ->required()
                   ->columnspan(1),

               ])->columns(2),
            ])->statePath("data");
    }

    public function create(): void
    {
        Permission::create($this->form->getState());
        $this->form->fill();
        $this->dispatch('permission-created');

        Notification::make()
        ->title('Enregistrement effectué avec succès')
        ->success()
         ->duration(5000)
        ->send();
    }
}
