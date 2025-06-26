<?php

namespace App\Filament\Resources\HotelResource\Pages;

use App\Filament\Resources\HotelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;

class ListHotels extends ListRecords
{
    protected static string $resource = HotelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('新規作成'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return '一覧';
    }

    protected function configureTable(Table $table): void
    {
        $table->emptyStateHeading('登録されているホテルがありません');
    }
}
