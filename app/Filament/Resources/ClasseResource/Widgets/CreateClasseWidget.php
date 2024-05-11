<?php

namespace App\Filament\Resources\ClasseResource\Widgets;

use App\Models\Classe;
use Filament\Forms\Form;
use App\Models\Departement;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateClasseWidget extends Widget implements HasForms
{
    use InteractsWithForms;
    protected static string $view = 'filament.resources.classe-resource.widgets.create-classe-widget';
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
                Wizard::make([
                    Step::make("Information")
                    ->schema([

                        Select::make('departement_id')
                        ->label("Departement")
                        ->required()
                        ->preload()
                        ->searchable()
                        ->options(function(){
                            return Departement::query()->pluck("lib","id");
                        }),
                    TextInput::make('lib')
                        ->label("Classe")
                        ->placeholder("Ex: L1 IT")
                        ->required()
                        ->unique("classes")
                        ->maxLength(255),
                    ]),
                    Step::make("Description")
                    ->schema([
                         MarkdownEditor::make('description')
                        ->columnSpanFull()
                        ->maxLength(255),

                    ]),
                ])->columns(2)->columnSpanFull(),
            ])->statePath("data");
    }

    public function create(): void
    {
        Classe::create($this->form->getState());
        $this->form->fill();
        $this->dispatch('classe-created');

        Notification::make()
        ->title('Enregistrement effectué avec succès')
        ->success()
         ->duration(5000)
        ->send();
    }
}
