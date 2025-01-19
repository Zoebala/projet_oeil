<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Classe;
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
use App\Filament\Resources\ClasseResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClasseResource\RelationManagers;
use App\Filament\Resources\ClasseResource\Widgets\CreateClasseWidget;
use App\Filament\Resources\ClasseResource\RelationManagers\EtudiantsRelationManager;
use App\Models\Annee;

class ClasseResource extends Resource
{
    protected static ?string $model = Classe::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup ="COGE Management";
    protected static ?int $navigationSort = 4;
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
                        ->maxLength(255),
                    ]),
                    Step::make("Description")
                    ->schema([
                         MarkdownEditor::make('description')
                        ->columnSpanFull()
                        ->maxLength(255),

                    ]),
                ])->columns(2)->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('departement.lib')
                    ->label("Departement")
                    ->sortable(),
                TextColumn::make('id')
                    ->label('Id Classe')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('lib')
                    ->label('Classe')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Description')
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
                    Tables\Actions\ViewAction::make()->SlideOver(),
                ])
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
            EtudiantsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClasses::route('/'),
            'create' => Pages\CreateClasse::route('/create'),
            'edit' => Pages\EditClasse::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [

            CreateClasseWidget::class,
        ];
    }
}
