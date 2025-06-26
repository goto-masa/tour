<?php

namespace App\Filament\Resources\PriceResource\Pages;

use App\Filament\Resources\PriceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrices extends ListRecords
{
    protected static string $resource = PriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('新規作成'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return '一覧';
    }

    protected function getEmptyStateHeading(): ?string
    {
        return '登録されている価格がありません';
    }
}
