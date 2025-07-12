<?php

namespace App\Filament\Resources\HotelCaseResource\Pages;

use App\Filament\Resources\HotelCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\HotelCase;
use Illuminate\Support\Arr;

class CreateHotelCase extends CreateRecord
{
    protected static string $resource = HotelCaseResource::class;

    protected function getFormDefaults(): array
    {
        $copyFromId = request()->query('copy_from');
        if ($copyFromId) {
            $copy = HotelCase::find($copyFromId);
            if ($copy) {
                $exclude = ['id', 'created_at', 'updated_at', 'deleted_at', 'guide_report_id'];
                return Arr::except($copy->toArray(), $exclude);
            }
        }
        return [];
    }

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
