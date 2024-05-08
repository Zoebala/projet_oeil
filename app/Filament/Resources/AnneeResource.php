<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Annee;

use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AnneeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AnneeResource\RelationManagers;
use App\Filament\Resources\AnneeResource\Widgets\CreateAnneeWidget;

class AnneeResource extends Resource
{
    protected static ?string $model = Annee::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup ="COGE Management";
    protected static ?int $navigationSort = 1;
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

                Section::make("Définition de l'année Académique")
                ->icon("heroicon-o-calendar-days")
                ->schema([

                    TextInput::make('lib')
                        ->label("Annee Académique")
                        ->required()
                        ->placeholder('Ex :2023-2024')
                        ->unique(ignoreRecord:true,table: Annee::class)
                        ->live(debounce:1500)
                        ->afterStateUpdated(function(Get $get,Set $set){
                            $set("debut",substr($get("lib"),0,4));
                            $set("fin",substr($get("lib"),5,9));
                        })
                        ->maxLength(9)
                        ->columnSpan(1),
                    Hidden::make('debut')
                        ->columnSpan(1),
                    Hidden::make('fin')
                        ->columnSpan(1),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lib')
                    ->label('Année Académiquue')
                    ->searchable(),
                Tables\Columns\TextColumn::make('debut')
                    ->label('Année Début')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fin')
                    ->label('Année Fin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])

            ])
            ->HeaderActions([
                Action::make("annee")
                ->label("Définition de l'année de travail")
                ->form([
                    Select::make("annee")
                    ->label("Choix de l'année")
                    ->required()
                    ->live()
                    ->afterStateUpdated(function($state,Set $set){
                        $Annee=Annee::whereId($state)->get(["lib","debut"]);
                        $set("lib_annee",$Annee[0]->lib);
                        $set("annee_debut",$Annee[0]->debut);


                    })
                    ->options(Annee::query()->pluck("lib","id")),
                    TextInput::make("lib_annee")
                    ->label("Année Choisie")
                    ->disabled()
                    // ->hidden()
                    ->dehydrated(true)
                    ->placeholder($annee->lib ?? date("Y")),
                    TextInput::make("annee_debut")
                    ->label("Année Début")
                    ->disabled()
                    // ->hidden()
                    ->dehydrated(true)
                    ->placeholder($annee->debut ?? date("Y")),

                ])
                ->modalWidth(MaxWidth::Medium)
                ->modalIcon("heroicon-o-calendar")
                ->action(function(array $data){
                    if(session('Annee_id')==NULL && session('Annee')==NULL && session('AnneeDebut')==NULL){

                        session()->push("Annee_id", $data["annee"]);
                        session()->push("Annee", $data["lib_annee"]);
                        session()->push("AnneeDebut", $data["annee_debut"]);
                    }else{
                        session()->pull("Annee_id");
                        session()->pull("Annee");
                        session()->pull("AnneeDebut");

                    }

                    // dd(session('Annee'));
                    Notification::make()
                    ->title("Fixation de l'annee de travail en ".$data['lib_annee'])
                    ->success()
                     ->duration(5000)
                    ->send();


                    return redirect("/admin");


                }),
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
            'index' => Pages\ListAnnees::route('/'),
            'create' => Pages\CreateAnnee::route('/create'),
            'edit' => Pages\EditAnnee::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            CreateAnneeWidget::class,
        ];
    }
}
