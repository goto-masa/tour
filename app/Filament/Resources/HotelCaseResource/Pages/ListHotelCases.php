<?php

namespace App\Filament\Resources\HotelCaseResource\Pages;

use App\Filament\Resources\HotelCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHotelCases extends ListRecords
{
    protected static string $resource = HotelCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
