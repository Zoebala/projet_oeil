<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use Filament\Actions;
use Livewire\Attributes\On;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PermissionResource;
use App\Filament\Resources\PermissionResource\Widgets\CreatePermissionWidget;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CreatePermissionWidget::class,
        ];
    }

    #[On('permission-created')]
    public function refresh() {}
}
