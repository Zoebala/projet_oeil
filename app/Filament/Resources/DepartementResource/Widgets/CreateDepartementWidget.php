<?php

namespace App\Filament\Resources\DepartementResource\Widgets;

use App\Models\Section;
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

class CreateDepartementWidget extends Widget implements HasForms
{
    use InteractsWithForms;
    protected static string $view = 'filament.resources.departement-resource.widgets.create-departement-widget';
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
                    Step::make("Informations")
                    ->schema([
                        Select::make('section_id')
                            ->label("Section")
                            ->required()
                            ->options(function(){
                                return Section::query()->pluck("lib","id");
                            }),
                        TextInput::make('lib')
                            ->label("Departement")
                            ->required()
                            ->unique(ignoreRecord:true,table: Departement::class)
                            ->placeholder("Ex: Informatique")
                            ->maxLength(255),

                    ]),
                    Step::make("Description")
                    ->schema([
                        MarkdownEditor::make('description')->columnSpanFull(),
                    ])
                ])->columns(2)->columnSpanFull(),

            ])->statePath("data");
    }

    public function create(): void
    {
        Departement::create($this->form->getState());
        $this->form->fill();
        $this->dispatch('departement-created');

        Notification::make()
        ->title('Enregistrement effectué avec succès')
        ->success()
         ->duration(5000)
        ->send();
    }
}
