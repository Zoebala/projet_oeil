<?php

namespace App\Filament\Resources;

use Filament\Forms;

use App\Models\Role;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;

use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;

use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RoleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Filament\Resources\RoleResource\Widgets\CreateRoleWidget;
use App\Models\Annee;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';
    protected static ?string $navigationGroup ="Paramètres";
    protected static ?int $navigationSort = 2;
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
                //
                Section::make("Définition des Rôles")
                ->icon("heroicon-o-finger-print")
                ->schema([
                    TextInput::make("name")
                    ->label("Désignation du rôle")
                    ->placeholder("Ex: DG")
                    ->unique(ignoreRecord:true,table: Role::class)
                    ->required()
                    ->columnSpan(1),
                    Select::make("permissions")
                    ->label("Permission")
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->relationship("permissions","name")
                    // ->required()
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make("name")
                ->label("Rôle")
                ->searchable()
                ->sortable(),
                TextColumn::make("permissions.name")
                ->label("Permissions")
                ->formatStateUsing(function($state){
                    return substr($state,0,85)."...";
                })
                ->searchable(),
                // ->sortable(),
                // TextColumn::make("created_at")
                // ->label("Créé le")
                // ->datetime("d/m/Y à H:i:s")
                // // ->searchable()
                // ->sortable(),


            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([

                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ViewAction::make()->slideOver(),
                ]),

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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            CreateRoleWidget::class,
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where("name","!=","Admin");
    }
}
