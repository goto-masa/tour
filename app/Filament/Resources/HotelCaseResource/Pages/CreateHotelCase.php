<?php

namespace App\Filament\Resources\HotelCaseResource\Pages;

use App\Filament\Resources\HotelCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHotelCase extends CreateRecord
{
    protected static string $resource = HotelCaseResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
