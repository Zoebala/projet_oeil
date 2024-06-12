<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;

use Filament\Forms\Form;
use App\Models\Permission;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;

use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;
use App\Filament\Resources\PermissionResource\Widgets\CreatePermissionWidget;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationGroup ="Paramètres";
    protected static ?int $navigationSort = 3;
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
               Section::make("Définition des Permissions")
               ->icon("heroicon-o-key")
               ->schema([
                   TextInput::make("name")
                   ->label("Désignation de la Permission")
                   ->unique(ignoreRecord:true,table: Permission::class)
                   ->placeholder("Ex: Create Etudiants"),

               ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make("name")
                ->label("Désignation de la Permission")
                ->searchable()
                ->sortable(),
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
                ])->button()->label("Actions")
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
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }


    public static function getWidgets(): array
    {
        return [
            CreatePermissionWidget::class,
        ];
    }
}
