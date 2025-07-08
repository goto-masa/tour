<?php

namespace App\Filament\Resources\GuideReportResource\Pages;

use App\Filament\Resources\GuideReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGuideReports extends ListRecords
{
    protected static string $resource = GuideReportResource::class;

    // 作成ボタンを消す
    protected function getHeaderActions(): array
    {
        return [];
    }
}
