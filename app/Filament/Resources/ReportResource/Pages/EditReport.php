<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReport extends EditRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
