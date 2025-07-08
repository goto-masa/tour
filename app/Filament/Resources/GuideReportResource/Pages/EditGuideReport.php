<?php

namespace App\Filament\Resources\GuideReportResource\Pages;

use App\Filament\Resources\GuideReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGuideReport extends EditRecord
{
    protected static string $resource = GuideReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
