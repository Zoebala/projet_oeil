<?php

namespace App\Filament\Pages;

use App\Models\Etudiant;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Tables\Concerns\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;


class Etats extends Page
{
//    use HasActions, InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.etats';

    public static function canAccess():bool
    {
        return Auth()->user()->hasRole('Admin');


    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make("toto")
            // ->url(fn(String $state)=>route("etudiant.generate_promotion", $state))
            //         ->openUrlInNewTab(),
            // ->url(fn(Etudiant $Student) =>route("etudiant.generate_promotion",$Student)
            //         ->openUrlInNewTab()),
        ];

    }
}
