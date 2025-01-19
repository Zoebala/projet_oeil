<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Section;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Departement;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DepartementResource\Pages;
use App\Filament\Resources\DepartementResource\RelationManagers;
use App\Filament\Resources\DepartementResource\Widgets\CreateDepartementWidget;
use App\Filament\Resources\DepartementResource\RelationManagers\ClassesRelationManager;
use App\Models\Annee;

class DepartementResource extends Resource
{
    protected static ?string $model = Departement::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup ="COGE Management";
    protected static ?int $navigationSort = 3;
    public static function getNavigationBadge():string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor():string
    {
        return "success";
    }

    public static function canAccess(): bool
    {


        if(self::canViewAny()){
            return Annee::isActive();
        }
        return false;
    }

    public static function canViewAny(): bool
    {
        return static::can('viewAny');
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
                            ->unique(ignoreRecord:true,table: Departement::class)
                            ->required()
                            ->placeholder("Ex: Informatique")
                            ->maxLength(255),

                    ]),
                    Step::make("Description")
                    ->schema([
                        MarkdownEditor::make('description')->columnSpanFull(),
                    ])
                ])->columns(2)->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('section.lib')
                    ->label("Section/Faculté")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('id')
                    ->label("Id Departement")
                    ->searchable(),
                TextColumn::make('lib')

                    ->label("Departement")

                    ->searchable(),
                TextColumn::make('description')
                    ->label("description")
                    ->searchable(),
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
            ClassesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartements::route('/'),
            'create' => Pages\CreateDepartement::route('/create'),
            'edit' => Pages\EditDepartement::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            CreateDepartementWidget::class,
        ];
    }
}
