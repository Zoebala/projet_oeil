<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\Section as Sections;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\SectionResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SectionResource\RelationManagers;
use App\Filament\Resources\SectionResource\RelationManagers\DepartementsRelationManager;

class SectionResource extends Resource
{
    protected static ?string $model = Sections::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = "Sections/Facultés";
    protected static ?string $modelLabel = "Sections/Facultés";

    protected static ?string $navigationGroup ="COGE Management";
    protected static ?int $navigationSort = 2;
    public static function getNavigationBadge():string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor():string
    {
        return "success";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make("Information")
                    ->schema([

                        TextInput::make('lib')
                            ->label("Section/Faculté")
                            ->required()
                            ->unique(ignoreRecord:true,table: Sections::class)
                            ->placeholder("Ex: Techniques")
                            ->maxLength(255)
                            ->columnSpan(1),
                    ]),
                    Step::make("Description")
                    ->schema([
                        MarkdownEditor::make('description')->columnSpanFull(),
                    ])
                ])->columns(2)->columnSpanFull(),
                Toggle::make("Depart")
                // ->disabled(fn(Get $get):bool => !filled($get("lib")))
                // ->tooltip("salut")
                ->label(function(Get $get){
                    if($get('Depart')==false){
                        return "Renseigner ses départements?";
                    }else{
                        return "Ne pas renseigner ses départements ?";
                    }
                })
                ->live(),
                Section::make("")
                ->schema([
                    Repeater::make("departements")
                    ->label("Departement")
                    ->relationship()
                    ->schema([
                        Wizard::make([
                            Step::make("Information")
                            ->schema([
                                TextInput::make("lib")
                                ->label("Departement")
                                ->live()
                                ->placeholder("Ex: Info et technologies"),

                            ]),
                            Step::make("Description")
                            ->schema([
                                MarkdownEditor::make("departements.description")
                                ->label("Description")
                                ->disabled(fn(Get $get):bool => !filled($get("lib")))

                            ]),

                        ]),

                    ])
                ])->hidden(fn(Get $get):bool => $get("Depart")==false)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lib')
                    ->label("Section")
                    ->searchable(),
                TextColumn::make('description')
                    ->label("Description")
                    ->searchable()
                    ->placeholder("Pas de description"),
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
                ActionGroup::make([

                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->button()->label("Actions"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            DepartementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSections::route('/'),
            'create' => Pages\CreateSection::route('/create'),
            'edit' => Pages\EditSection::route('/{record}/edit'),
        ];
    }
}
