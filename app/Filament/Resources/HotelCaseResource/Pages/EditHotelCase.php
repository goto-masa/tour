<?php

namespace App\Filament\Resources\HotelCaseResource\Pages;

use App\Filament\Resources\HotelCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHotelCase extends EditRecord
{
    protected static string $resource = HotelCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
