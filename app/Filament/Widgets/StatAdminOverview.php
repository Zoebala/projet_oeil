<?php

namespace App\Filament\Widgets;

use App\Models\Section;
use App\Models\Etudiant;
use App\Models\Actualite;
use App\Models\Annee;
use App\Models\Departement;
use App\Models\Inscription;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatAdminOverview extends BaseWidget
{

    protected static bool $isLazy = false;


    public static function canView(): bool
    {

        return Annee::isActive();

    }


    protected function getStats(): array
    {
        return [
            //
            Stat::make("Sections/Facultés", Section::count())
            ->description("Nos Sections")
            ->color("warning")
            ->chart([34,2,5,23])

            ->Icon("heroicon-o-building-office-2"),
            Stat::make("Departements", Departement::count())
            ->description("Nos Départements")
            ->color("success")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-home-modern"),
            Stat::make("Actualités", Actualite::take(5)->Orderby("id","desc")->count())
            ->description("Nombre d'actualités publié")
            ->color("warning")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-clipboard-document-list"),
           

        ];
    }

    public function getColumns(): int
    {
        return 3;
    }
}
