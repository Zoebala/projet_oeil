<?php

namespace App\Filament\Resources\RoleResource\Pages;

use Filament\Actions;
use Livewire\Attributes\On;
use App\Filament\Resources\RoleResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\RoleResource\Widgets\CreateRoleWidget;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            CreateRoleWidget::class,
        ];
    }

    #[On('role-created')]
    public function refresh() {}
}
