<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Annee;
use App\Models\Frais;
use App\Models\Classe;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Etudiant;
use App\Models\Paiement;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\PaiementResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PaiementResource\RelationManagers;
use App\Filament\Resources\PaiementResource\Widgets\CreatePaiementWidget;

class PaiementResource extends Resource
{
    protected static ?string $model = Paiement::class;
    protected static ?string $pollingInterval = '5s';

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup ="COGE Management";
    protected static ?int $navigationSort = 8;
    public static function getNavigationBadge():string
    {
        return static::getModel()::Where("annee_id",session("Annee_id") ?? 1 )->count();
    }
    public static function getNavigationBadgeColor():string
    {
        return "success";
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
                Section::make("Paiement Frais")
                ->icon("heroicon-o-banknotes")
                ->schema([

                    TextInput::make("etat")
                    ->label("Etat Paiement de l'étudiant")
                    ->visible(fn(Get $get) :bool =>
                        (filled($get("etudiant_id")) && filled($get("annee_id")) && $get("classe_id") &&
                        Paiement::Where("paiements.etudiant_id",$get("etudiant_id"))
                        ->Where("paiements.annee_id",$get("annee_id"))
                        ->Where("paiements.classe_id",$get("classe_id"))
                        ->exists() || filled($get("etudiant_id")) && filled($get("annee_id")) && $get("classe_id") &&
                        !Paiement::Where("paiements.etudiant_id",$get("etudiant_id"))
                        ->Where("paiements.annee_id",$get("annee_id"))
                        ->Where("paiements.classe_id",$get("classe_id"))
                        ->exists() )
                    )
                    ->disabled()
                    ->live()
                    ->placeholder(function(Get $get){

                        if(filled($get("etudiant_id")) && filled($get("annee_id")) && $get("classe_id") &&
                            Paiement::Where("paiements.etudiant_id",$get("etudiant_id"))
                            ->Where("paiements.annee_id",$get("annee_id"))
                            ->Where("paiements.classe_id",$get("classe_id"))
                            ->exists()){

                                //détermination du montant à payer pour une promotion donnée à une année académique donnée
                                $Frais=Frais::where("classe_id",$get("classe_id"))
                                            ->where("annee_id",$get("annee_id"))
                                            ->first();

                                $MontantTotal=$Frais->montant*$Frais->taux;

                                //détermination du montant déjà payé par l'étudiant
                                $Paye=Paiement::query()
                                               ->where("etudiant_id",$get("etudiant_id"))
                                               ->where("annee_id",$get("annee_id"))
                                               ->SUM("montant");
                                //Détermination du reste à payer
                                $Reste=$MontantTotal-$Paye;

                                return "Montant à Payé : $MontantTotal FC | Montant Payé: $Paye FC | Reste : $Reste FC";
                        }

                        //le cas où l'étudiant n'a encore rien payé pour cette année académique
                        if(filled($get("etudiant_id")) && filled($get("annee_id")) && $get("classe_id") &&
                            !Paiement::Where("paiements.etudiant_id",$get("etudiant_id"))
                            ->Where("paiements.annee_id",$get("annee_id"))
                            ->Where("paiements.classe_id",$get("classe_id"))
                            ->exists()){

                                //détermination du montant à payer pour une promotion donnée à une année académique donnée
                                $Frais=Frais::where("classe_id",$get("classe_id"))
                                            ->where("annee_id",$get("annee_id"))
                                            ->first();
                                $MontantTotal=$Frais->montant*$Frais->taux;

                                return "Montant à Payé : $MontantTotal FC | Montant Payé: 0 | Reste : $MontantTotal FC";
                        }

                    })
                    ->columnSpanFull(),
                    Select::make('annee_id')
                    ->label("Annee Académique")
                    ->required()
                    ->options(function(){
                        return Annee::whereId(session("Annee_id") ?? 1)
                                    ->pluck("lib","id");
                    }),
                    Select::make('classe_id')
                        ->label("Classe")
                        ->live()
                        ->preload()
                        ->afterStateUpdated(function($state, Set $set,){
                            if($state==null){
                                $set("etudiant_id",null);
                            }
                        })
                        ->searchable()
                        ->required()
                        ->options(function(Get $get){
                            return Classe::
                                        join("frais","frais.classe_id","classes.id")
                                        ->where('annee_id',$get('annee_id'))
                                        ->pluck("classes.lib","classes.id");
                        }),
                    Select::make('etudiant_id')
                        ->label("Etudiant")
                        ->live()
                        ->options(function(Get $get){
                            return Etudiant::join('inscriptions',"inscriptions.etudiant_id","etudiants.id")
                                            ->where("etudiants.classe_id",$get("classe_id"))
                                            ->where("actif",true)
                                            ->pluck("nom","etudiants.id");
                        })
                        ->required()
                        ->searchable()
                        ->required(),
                    Select::make('frais_id')
                        ->label("Frais")
                        ->required()
                        ->options(function(Get $get){
                          return Frais::query()->whereClasse_id($get("classe_id"))->pluck("montant","id");

                        })
                        ->suffix(" $"),
                    TextInput::make('motif')
                        ->required()
                        ->placeholder("Ex: Frais Académique")
                        ->default("Frais Académique")
                        ->maxLength(255)->columnSpan(2),
                    Select::make('devise')
                            ->label("Devise")
                            ->required()
                            ->options(
                                [
                                    "CDF" =>"CDF",
                                    "USD" =>"USD",
                                ]
                            )->preload()
                            ->searchable(),
                    TextInput::make('montant')
                        ->required()
                        ->live()
                        ->afterStateUpdated(function($state, Get $get,Set $set){
                            if(filled($get("etudiant_id")) && filled($get("annee_id")) && $get("classe_id") &&
                                Paiement::Where("paiements.etudiant_id",$get("etudiant_id"))
                                ->Where("paiements.annee_id",$get("annee_id"))
                                ->Where("paiements.classe_id",$get("classe_id"))
                                ->exists()){
                                //détermination du montant à payer pour une promotion donnée à une année académique donnée
                                $Frais=Frais::where("classe_id",$get("classe_id"))
                                            ->where("annee_id",$get("annee_id"))
                                            ->first();

                                $MontantTotal=$Frais->montant*$Frais->taux;

                                //détermination du montant déjà payé par l'étudiant

                                $Paye=Paiement::query()
                                    ->where("etudiant_id",$get("etudiant_id"))
                                    ->where("annee_id",$get("annee_id"))
                                    ->SUM("montant");
                                    //Détermination du reste à payer
                                    $Reste=$MontantTotal-$Paye;
                                if($get("devise")=="USD"){
                                    $state=(int)$state*$Frais->taux;
                                }else{
                                    $state=(int)$state;

                                }



                                //on vérifie si l'étudiant paye un montant inférieur ou égal à celui qu'il est censé payé

                                if(filled($get('montant')) && $state>$Reste){
                                    Notification::make()
                                    ->title("Le montant saisi est supérieur par rapport à ce que l'étudiant doit payer!")
                                    ->danger()
                                    ->duration(5000)
                                    ->send();
                                    $set("montant",null);
                                }
                            }

                            //si l'étudiant n'a pas encore payé
                            if(filled($get("etudiant_id")) && filled($get("annee_id")) && $get("classe_id") &&
                                !Paiement::Where("paiements.etudiant_id",$get("etudiant_id"))
                                ->Where("paiements.annee_id",$get("annee_id"))
                                ->Where("paiements.classe_id",$get("classe_id"))
                                ->exists()){
                                //détermination du montant à payer pour une promotion donnée à une année académique donnée
                                $Frais=Frais::where("classe_id",$get("classe_id"))
                                            ->where("annee_id",$get("annee_id"))
                                            ->first();
                                $MontantTotal=$Frais->montant*$Frais->taux;
                                if($get("devise")=="USD"){
                                    $state=(int)$state*$Frais->taux;
                                }else{
                                    $state=(int)$state;
                                }

                                if(filled($get('montant')) && $state>$MontantTotal){
                                    Notification::make()
                                    ->title("Le montant saisi est supérieur par rapport à ce que l'étudiant doit payer!")
                                    ->danger()
                                    ->duration(5000)
                                    ->send();
                                    $set("montant",null);
                                }

                            }
                        })
                        ->placeholder("Ex: 300000")
                        ->suffix("FC")
                        ->numeric(),

                ])->columns(2)->columnSpan(2),
                Section::make()
                ->icon("heroicon-o-banknotes")
                ->description('Uploader le bordereau comme preuve de paiement')
                ->schema([
                    FileUpload::make('bordereau')
                        ->required()
                        ->openable()
                        ->downloadable()
                        ->disk("public")->directory('bordereaux'),
                        DateTimePicker::make('datepaie')
                        ->label("Date de Paiment")
                        ->default(now())
                        ->required(),
                        TextInput::make("Etudiant")
                        ->label('Etudiant Séléctionné')
                        ->placeholder(function(Get $get): string
                        {
                            // dd($get("etudiant_id"));
                            if($get("etudiant_id") <> Null){
                                $Etudiant=Etudiant::query()->whereId($get("etudiant_id"))->get(["nom","postnom","prenom"]);

                                return $Etudiant[0]->nom." ".$Etudiant[0]->postnom." ".$Etudiant[0]->prenom;
                            }else{
                                return "";
                            }
                        })
                        ->visible(fn(Get $get):bool => filled($get("etudiant_id")))
                        ->disabled(fn(Get $get):bool => filled($get("etudiant_id")))
                        ->columnSpanFull()

                ])->ColumnSpan(1),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('annee.lib')
                    ->label("Année Académique")
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('classe.lib')
                    ->label("Classe")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('etudiant.nom')
                    ->label("Nom Complet")
                    ->getStateUsing(function($record){

                        $Etud=Etudiant::whereId($record->etudiant_id)->first();
                        return $Etud->nom." ".$Etud->postnom." ".$Etud->prenom;

                    })
                    ->searchable()
                    ->sortable(),

                // Tables\Columns\TextColumn::make('frais.montant')
                //     ->label("Frais")
                //     ->suffix(" $")
                //     ->toggleable(isToggledHiddenByDefault: false)
                //     ->sortable(),
                Tables\Columns\TextColumn::make('montant')
                    ->getStateUsing(function($record){
                        $F=Frais::where("id",$record->frais_id)
                        ->where("annee_id",$record->annee_id)
                        ->first();


                        return $record->montant." FC / ".$F->montant*$F->taux." FC";
                    })
                    ->label("Montant Payé")
                    ->sortable(),
                // Tables\Columns\TextColumn::make('devise')
                //     ->label("Devise")
                //     ->searchable()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('motif')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('datepaie')
                    ->label("Date de Paiement")
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->date("d/m/Y à H:i:s")
                    ->sortable(),
                ImageColumn::make('bordereau')
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

                SelectFilter::make("Annee")
                ->label("Année Acadamique")
                ->relationship("annee","lib")
            ])
            ->actions([
                ActionGroup::make([

                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->label("Actions")
                ->button(),
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
            'index' => Pages\ListPaiements::route('/'),
            'create' => Pages\CreatePaiement::route('/create'),
            'edit' => Pages\EditPaiement::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
           CreatePaiementWidget::class,
        ];
    }
}
