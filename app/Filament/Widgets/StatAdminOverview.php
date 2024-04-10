<?php

namespace App\Filament\Widgets;

use App\Models\Etudiant;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatAdminOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make("Etudiant", Etudiant::join("inscriptions","inscriptions.etudiant_id","=","etudiants.id")->Where("inscriptions.actif",1)->count())
            ->description("Etudiants Inscrits")
            ->color("warning")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-users"),
            Stat::make("Etudiant", Etudiant::join("paiements","paiements.etudiant_id","=","etudiants.id")->count())
            ->description("Etudiant ayant payé un accompte")
            ->color("success")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-users"),

        ];
    }
}
