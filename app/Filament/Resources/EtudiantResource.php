<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Annee;
use App\Models\Classe;
use Filament\Forms\Set;
use App\Models\Etudiant;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Inscription;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\EtudiantResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EtudiantResource\RelationManagers;
use App\Filament\Resources\EtudiantResource\Widgets\CreateEtudiantWidget;
use App\Filament\Resources\EtudiantResource\RelationManagers\PaiementsRelationManager;

class EtudiantResource extends Resource
{
    protected static ?string $model = Etudiant::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Etudiant(s)';
    protected static ?string $navigationGroup ="COGE Management";
    protected static ?int $navigationSort = 5;
    protected static ?string $recordTitleAttribute ="nom";






    public static function getGlobalSearchResultTitle(Model $record):string
    {
        return $record->nom.' '.$record->postnom." ".$record->prenom;
    }
    public static function getGloballySearchableAttributes():array
    {
        return [
            "nom",
            "postnom",
            "prenom",
            "classe.lib"
        ];
    }
    public static function getGlobalSearchResultDetails(Model $record):array
    {
        return [
            "Classe"=>$record->classe->lib,
        ];
    }

      public static function getGlobalSearchResultEloquentQuery(Model $record):Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['classes']);
    }
    public static function getNavigationBadge():string
    {
        if(Auth()->user()->hasRole(["Admin","SACAD","DG","SACAD","SGACAD","SGADMN","SECTION","COMGER"])){

            return static::getModel()::count();
        }else{

            return static::getModel()::whereUser_id(Auth()->user()->id)->count();
        }
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
                        Section::make()
                        ->schema([
                            TextInput::make('classe_id')
                            ->label("Code Classe")
                            ->disabled()
                            ->dehydrated(true)
                            ->required()
                            ->default(function(){
                                return session("classe_id")[0] ?? 1;
                            }),
                            TextInput::make('matricule')
                                ->placeholder('Ex: 2023/1')
                                ->maxLength(15),
                            TextInput::make('nom')
                                ->placeholder("Ex: Dupon")
                                ->required()
                                ->maxLength(25),
                            TextInput::make('postnom')
                                ->placeholder("Ex: Smith")
                                ->required()
                                ->maxLength(25),
                            TextInput::make('prenom')
                                ->required()
                                ->placeholder('Ex: joseph')
                                ->maxLength(25),
                                Select::make('genre')
                                ->options([
                                    "F"=>"F",
                                    "M"=>"M",
                                ]),
                                    // ->required(),


                        ])->columnSpan(2)->columns(2),
                        Section::make()
                        ->schema([
                            FileUpload::make('photo')
                            ->disk("public")->directory("photos"),
                            // ->visibleOn("edit"),
                            DatePicker::make('datenais')
                            ->label("Date de Naissance")
                            ->columnSpanFull()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function($state, Set $set){
                                $Annee_naissance=(int)date("Y",strtotime($state));
                                $A_Actuelle=(int)date("Y");
                                if(($A_Actuelle-$Annee_naissance)<17){
                                    $set("datenais",null);

                                    Notification::make()
                                    ->title("La candidature ne concerne pas les  moins de 17 ans")
                                    ->danger()
                                    ->duration(5000)
                                    ->send();
                                }

                            }),
                            // ->maxLength(255),
                        ])->columnSpan(1)->columns(1),

                    ])->columns(3),
                    Step::make("Contact & Provenance")
                    ->schema([
                        TextInput::make('teletudiant')
                            ->label("Téléphone Etudiant")
                            ->tel()
                            // ->required()
                            ->placeholder("Ex: 089XXXXXXX")
                            ->maxLength(10),
                        TextInput::make('teltutaire')
                            ->label("Téléphone Tutaire")
                            ->tel()
                            // ->required()
                            ->placeholder("Ex: 089XXXXXXX")
                            ->maxLength(25),
                        TextInput::make('adresse')
                            // ->required()
                            ->placeholder("Ex: 12, Av. Reservoi Q/Noki")
                            ->maxLength(50),
                         Select::make('province')
                            ->options([
                                    "Kongo-Central"=>"Kongo-Central",
                                    "Kinshasa"=>"Kinshasa",
                                    "Sud-kivu"=>"Sud-kivu",
                                    "Nord-kivu"=>"Nord-kivu",
                                    "Lualaba"=>"Lualaba",
                                    "Sub Ubangi"=>"Sub Ubangi",
                                    "Nord Ubangi"=>"Nord Ubangi",
                                    "Kwilu"=>"Kwilu",
                                    "Haut-Katanga"=>"Haut-Katanga",
                                    "Haut-Katanga"=>"Haut-Lomami",
                                    "Haut-Uélé"=>"Haut-Uélé",
                                    "Bas-Uélé"=>"Bas-Uélé",
                                    "Province Orientale"=>"Province Orientale",
                                    "Tshuapa"=>"Tshuapa",
                                    "Mai-ndombe"=>"Mai-ndombe",
                                    "Tshopo"=>"Tshopo",
                                    "Kwango"=>"Kwango",
                                    "Sankuru"=>"Kwango",

                            ])
                            ->searchable()
                            ->label("Province D'origine"),
                            // ->maxLength(50),
                        TextInput::make('territoire')
                            ->label("Territoire D'origine")
                            ->placeholder("Ex : Mbanza-Ngungu")
                            ->maxLength(50),
                        TextInput::make('territoireEcole')
                            ->label("Territoire Ecole")
                            ->placeholder("Ex : Mbanza-Ngungu")
                            ->maxLength(50),
                        TextInput::make('adresseEcole')
                            ->label("Adresse Ecole")
                            ->placeholder("Ex : 13, Av. Reservoir Q/Noki")
                            ->maxLength(50),
                        TextInput::make('ecole')
                            ->label("Ecole de Provenance")
                            ->placeHolder("Ex: Edap/ISP Mbanza-ngungu")
                            ->maxLength(50),
                        TextInput::make('optionSecondaire')
                            ->label("Option Faite au secondaire")
                            ->placeHolder("Ex: Mécanique Générale")
                            ->maxLength(50),
                    ])->columns(3),
                    Step::make("Autres Informations")
                    ->schema([

                        TextInput::make('pourcentage')
                            ->label("Pourcentage Obtenu")
                            ->placeHolder("Ex: 62")
                            // ->required()
                            ->numeric(),
                        TextInput::make('nompere')
                            ->label("Nom du père")
                            ->placeholder("Ex: Smith")
                            // ->required()
                            ->maxLength(25),
                        TextInput::make('nommere')
                            ->label("Nom de la mère")
                            ->placeholder("Ex: Saldivar")
                            // ->required()
                            ->maxLength(25),
                        TextInput::make('nationalite')
                            ->placeholder("Ex: congolaise")
                            ->maxLength(20),

                    ])->columns(2),
                    Step::make("Elements Dossiers")
                    ->schema([
                        FileUpload::make("files")
                        ->label("Mes éléments de dossiers")
                        ->multiple()
                        ->openable()
                        ->downloadable()

                        ->maxSize("2048")
                        ->disk("public")->directory("dossiers"),
                        // ->visibleOn("edit"),
                        // ->preserveFilenames(),
                    ])
                ])->columnSpanFull(),





            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('inscriptions.actif')
                    ->boolean()
                    ->label("Inscription Active ?")
                    ->placeholder("Non Inscrit(e)")
                    // ->default(false)
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('classe.lib')
                    ->label("Classe")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('matricule')
                    ->label("Matricule")
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nom')
                    ->label("Nom Complet")
                    ->getStateUsing(fn($record)=> $record->nom." ".$record->postnom." ".$record->prenom)
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('genre')
                    ->label("Genre")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('photo')
                    ->label("Photo")
                    ->placeholder("Pas de Profil")
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\ImageColumn::make('files')
                    ->label("Mes éléments de dossier")
                    ->placeholder("N'a pas d'éléménts dossier")
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('province')
                    ->label("Province")
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('territoire')
                    ->label("Territoire")
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('territoireEcole')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('adresseEcole')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ecole')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('optionSecondaire')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('datenais')
                    ->label("Date de naissance")
                    ->date("d/m/Y")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pourcentage')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nompere')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nommere')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('teletudiant')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('teltutaire')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('adresse')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nationalite')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make("Inscrire")
                    ->icon("heroicon-o-clipboard-document-list")
                    ->form([

                        Select::make("annee_id")
                        ->label("Année Académique")
                        ->searchable()
                        ->preload()
                        ->options(function(){
                            return Annee::whereId(session("Annee_id") ?? 1)->pluck("lib","id");
                        })->required(),
                        Select::make("classe_id")
                        ->label("Classe")
                        ->searchable()
                        ->preload()
                        ->options(function(){
                            return Classe::query()->pluck("lib","id");
                        })->required(),


                    ])->modalWidth(MaxWidth::Medium)
                    ->modalIcon("heroicon-o-clipboard-document-list")
                    ->Action(function(array $data,Etudiant $Etudiant){

                        // dd($data);

                        if(!Etudiant::join("inscriptions","inscriptions.etudiant_id","etudiants.id")
                                    ->join("annees","annees.id","inscriptions.annee_id")
                                    ->join("classes","classes.id","inscriptions.classe_id")
                                    ->Where("inscriptions.etudiant_id",$Etudiant->id)
                                    ->Where("inscriptions.annee_id",$data['annee_id'])
                                    ->Where("inscriptions.classe_id",$data['classe_id'])
                                    ->exists()){

                                Inscription::create([
                                    "actif"=>true,
                                    "annee_id"=>$data['annee_id'],
                                    "classe_id"=>$data['classe_id'],
                                    "etudiant_id"=>$Etudiant->id,
                                ]);

                                Notification::make()
                                    ->title("l'étudiant(e) $Etudiant->nom $Etudiant->postnom $Etudiant->prenom a  été inscrit avec succès!")
                                    // ->successRedirectUrl("presences.list")
                                    ->success()
                                    ->send();
                        }else{


                                    Notification::make()
                                        ->title("l'étudiant(e) $Etudiant->nom $Etudiant->postnom $Etudiant->prenom a déjà été inscrit!")
                                        // ->successRedirectUrl("presences.list")
                                        ->danger()
                                        ->send();

                        }

                    }),
                    Tables\Actions\Action::make("activer")
                    ->icon("heroicon-o-clipboard-document-list")
                    ->label("Activer/Désactiver")
                    ->Action(function(Etudiant $Etudiant){


                        //on vérifie si l'étudiant n'est pas déjà activé
                        if(Etudiant::join("inscriptions","inscriptions.etudiant_id","etudiants.id")
                                    ->Where("inscriptions.etudiant_id",$Etudiant->id)
                                    ->Where("inscriptions.classe_id",$Etudiant->classe_id)
                                    ->where("actif",true)
                                    ->exists()){

                                Inscription::where("etudiant_id",$Etudiant->id)->update([
                                    "actif"=>false,

                                ]);

                                Notification::make()
                                ->title("L'étudiant(e) $Etudiant->nom $Etudiant->postnom $Etudiant->prenom a  été désactivé(e) avec succès!")
                                ->danger()
                                ->send();
                        }elseif(Etudiant::join("inscriptions","inscriptions.etudiant_id","etudiants.id")
                            ->Where("inscriptions.etudiant_id",$Etudiant->id)
                            ->Where("inscriptions.classe_id",$Etudiant->classe_id)
                            ->where("actif",false)
                            ->exists()){


                                Inscription::where("etudiant_id",$Etudiant->id)->update([
                                    "actif"=>true,

                                ]);
                                Notification::make()
                                        ->title("L'étudiant(e) $Etudiant->nom $Etudiant->postnom $Etudiant->prenom a été activé(e) avec succès!")
                                        ->success()
                                        ->send();

                        }else{
                            Notification::make()
                                    ->title("L'étudiant(e) $Etudiant->nom $Etudiant->postnom $Etudiant->prenom doit d'abord se faire inscrire!")
                                    ->danger()
                                    ->send();

                        }

                    })->requiresConfirmation(),
                     Tables\Actions\Action::make("Generer")
                        ->requiresConfirmation()
                        ->label("Mon Profil")
                        ->icon("heroicon-o-user")
                        ->url(fn(Etudiant $Student)=>route("etudiant.profil",$Student))
                        ->openUrlInNewTab(),

                ])->button()

                // ->color('primary')
                ->label("Actions")
                ->visible(fn():bool => Auth()->user()->hasRole(["Admin","SACAD"])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make("Skip")
                    ->label("Passer de classe")
                    ->icon("heroicon-o-forward")
                    ->color("success")
                    ->form([
                        Select::make("annee_id")
                        ->label("Année Académique")
                        ->options(function(){
                            return Annee::whereId(session("Annee_id") ?? 1)->pluck("lib","id");
                        })->required(),
                        Select::make("classe_id")
                        ->label("Classe")
                        ->options(function(){
                            return Classe::query()->pluck("lib","id");
                        })->required()
                        ->preload()
                        ->searchable(),

                    ])->modalWidth(MaxWidth::Medium)
                    ->modalIcon("heroicon-o-chat-bubble-left")
                    ->Action(function(Collection $records,array $data){

                        //on vérifie l'année de travail

                        if(session("Annee_id")){

                            foreach($records as $record){

                                Inscription::whereEtudiant_id($record->id)->update([
                                    "actif"=>true,
                                    // "etudiant_id"=>$record->id,
                                    "annee_id"=>$data["annee_id"],
                                    "classe_id"=>$data["classe_id"],

                                ]);
                                Etudiant::whereId($record->id)->update([
                                    "classe_id"=>$data["classe_id"],
                                ]);
                            }
                            Notification::make()
                            ->title("Le passage de classe a été effetué avec succès!")
                            // ->successRedirectUrl("presences.list")
                            ->success()
                            ->send();
                        }else{
                            Notification::make()
                                ->title("Veuillez définir d'abord l'Année de travail Souhaitée!")
                                ->danger()
                                ->send();
                        }
                    }),
                    Tables\Actions\BulkAction::make("Inscrire")
                    ->icon("heroicon-o-clipboard-document-list")
                    ->color("warning")
                    ->form([
                        Select::make("annee_id")
                        ->label("Année Académique")
                        ->searchable()
                        ->options(function(){
                            return Annee::whereId(session("Annee_id") ?? 1)
                                        ->pluck("lib","id");
                        })->required(),
                        Select::make("classe_id")
                        ->label("Classe")
                        ->searchable()
                        ->options(function(){
                            return Classe::query()->pluck("lib","id");
                        })->required(),

                    ])->modalWidth(MaxWidth::Medium)
                    ->modalIcon("heroicon-o-clipboard-document-list")
                    ->Action(function(Collection $Etudiants,array $data){

                        //On vérifie l'année de travail
                        if(session("Annee_id")){


                                $cpt=1;
                                foreach($Etudiants as $Etudiant){

                                    //gestion des étudiants n'ayant pas encore été inscrits
                                    if(!Etudiant::join("inscriptions","inscriptions.etudiant_id","etudiants.id")
                                            ->join("annees","annees.id","inscriptions.annee_id")
                                            ->join("classes","classes.id","inscriptions.classe_id")
                                            ->Where("inscriptions.etudiant_id",$Etudiant->id)
                                            ->Where("inscriptions.annee_id",$data['annee_id'])
                                            ->Where("inscriptions.classe_id",$data['classe_id'])
                                            ->exists()){

                                                Inscription::create([
                                                    "actif"=>true,
                                                    "annee_id"=>$data['annee_id'],
                                                    "classe_id"=>$data['classe_id'],
                                                    "etudiant_id"=>$Etudiant->id,
                                                ]);
                                    }else{
                                        $Etudiants=Etudiant::join("inscriptions","inscriptions.etudiant_id","etudiants.id")
                                        ->join("annees","annees.id","inscriptions.annee_id")
                                        ->join("classes","classes.id","inscriptions.classe_id")
                                        ->Where("inscriptions.etudiant_id",$Etudiant->id)
                                        ->Where("inscriptions.annee_id",$data['annee_id'])
                                        ->Where("inscriptions.classe_id",$data['classe_id'])
                                        ->get(["nom","postnom","prenom"]);

                                        Notification::make()
                                        ->title("l'étudiant(e) $Etudiant->nom $Etudiant->postnom $Etudiant->prenom a déjà été inscrit!")
                                        // ->successRedirectUrl("presences.list")
                                        ->danger()
                                        ->send();
                                    }

                                    if($cpt==count($Etudiants)){

                                        Notification::make()
                                        ->title("L'inscription des étudiants a été effetuée avec succès!")
                                        // ->successRedirectUrl("presences.list")
                                        ->success()
                                        ->send();
                                    }
                                    $cpt++;
                                }



                        }else{
                            Notification::make()
                                ->title("Veuillez définir d'abord l'Année de travail Souhaitée!")
                                    // ->successRedirectUrl("presences.list")
                                ->danger()
                                ->send();
                        }

                         //message pour les étudiants ayant déjà été inscrits
                        //  foreach($Etudiants as $Etudiant){
                            // if(Etudiant::join("inscriptions","inscriptions.etudiant_id","etudiants.id")
                            // ->join("annees","annees.id","inscriptions.annee_id")
                            // ->join("classes","classes.id","inscriptions.classe_id")
                            // ->Where("inscriptions.etudiant_id",$Etudiant->id)
                            // ->Where("inscriptions.annee_id",$data['annee_id'])
                            // ->Where("inscriptions.classe_id",$data['classe_id'])
                            // ->exists()){


                            // }
                        // }







                    }),
                    // Tables\Actions\BulkAction::make("Generer")
                    // ->label("Génrérer Promotion")
                    // ->icon("heroicon-o-document-download")
                    // ->url(function(Collection $Students){
                    //     return route("etudiant.generate_promotion",$Etudiants);
                    // })
                    // ->openUrlInNewTab(),
                ])->visible(fn():bool => Auth()->user()->hasRole(["Admin","SACAD"])) ,
            ]);
            // ->headerActions([
            //     Tables\Actions\CreateAction::make(),
            // ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            PaiementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEtudiants::route('/'),
            'create' => Pages\CreateEtudiant::route('/create'),
            'edit' => Pages\EditEtudiant::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            // CreateEtudiantWidget::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if(!Auth()->user()->hasRole(["Admin","SACAD","DG","SACAD","SGACAD","SGADMN","SECTION","COMGER"])){

            return parent::getEloquentQuery()->where("user_id",Auth()->user()->id);
        }else{
            return parent::getEloquentQuery();

        }
    }
}
