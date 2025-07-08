<?php

namespace App\Filament\Resources\PriceResource\Pages;

use App\Filament\Resources\PriceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreatePrice extends CreateRecord
{
    protected static string $resource = PriceResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // 価格欄のいずれか1つ必須バリデーション
        $priceKeys = [
            ...array_map(fn($h) => "guide_price_{$h}", range(4, 15)),
            'guide_price_additional',
            ...array_map(fn($h) => "car_price_{$h}", range(4, 15)),
            'car_price_additional',
            ...array_map(fn($h) => "none_price_{$h}", range(4, 15)),
            'none_price_additional',
            ...array_map(fn($h) => "other_price_{$h}", range(4, 15)),
            'other_price_additional',
        ];
        $hasPrice = false;
        foreach ($priceKeys as $key) {
            if (!empty($data[$key])) {
                $hasPrice = true;
                break;
            }
        }
        if (!$hasPrice) {
            Notification::make()
                ->danger()
                ->title('価格欄のいずれか1つは必須です')
                ->send();
            return new \App\Models\Price();
        }

        // \Log::debug('フォームデータ', $data);

        $service = $data['service'] ?? null;
        $type = $data['type'] ?? null;
        $name = $data['name'] ?? null;
        $serviceOther = $data['service_other'] ?? null;
        $last = null;

        if ($service === 'ガイド') {
            foreach (range(4, 15) as $h) {
                $key = "guide_price_{$h}";
                if (!empty($data[$key])) {
                    // \Log::debug('作成するガイドレコード', [
                    //     'name' => $name,
                    //     'service' => $service,
                    //     'duration' => $h,
                    //     'price_including_tax' => $data[$key],
                    //     'type' => '-',
                    // ]);
                    $last = \App\Models\Price::create([
                        'name' => $name,
                        'service' => $service,
                        'duration' => $h,
                        'price_including_tax' => $data[$key],
                        'type' => '-',
                    ]);
                }
            }
            if (!empty($data['guide_price_additional'])) {
                \Log::debug('作成するガイドレコード(additional)', [
                    'name' => $name,
                    'service' => $service,
                    'duration' => '延長料金',
                    'price_including_tax' => $data['guide_price_additional'],
                    'type' => '-',
                ]);
                $last = \App\Models\Price::create([
                    'name' => $name,
                    'service' => $service,
                    'duration' => '延長料金',
                    'price_including_tax' => $data['guide_price_additional'],
                    'type' => '-',
                ]);
            }
        } elseif ($service === '車' && (in_array($type, ['VAN', 'SEDAN']) || !empty($data['type_other']))) {
            $saveType = $type;
            if ($type === 'その他' && !empty($data['type_other'])) {
                $saveType = $data['type_other'];
            }
            foreach (range(4, 15) as $h) {
                $key = "car_price_{$h}";
                if (!empty($data[$key])) {
                    \Log::debug('作成する車レコード', [
                        'name' => $name,
                        'service' => $service,
                        'type' => $saveType,
                        'duration' => $h,
                        'price_including_tax' => $data[$key],
                    ]);
                    $last = \App\Models\Price::create([
                        'name' => $name,
                        'service' => $service,
                        'type' => $saveType,
                        'duration' => $h,
                        'price_including_tax' => $data[$key],
                    ]);
                }
            }
            if (!empty($data['car_price_additional'])) {
                \Log::debug('作成する車レコード(additional)', [
                    'name' => $name,
                    'service' => $service,
                    'type' => $saveType,
                    'duration' => '延長料金',
                    'price_including_tax' => $data['car_price_additional'],
                ]);
                $last = \App\Models\Price::create([
                    'name' => $name,
                    'service' => $service,
                    'type' => $saveType,
                    'duration' => '延長料金',
                    'price_including_tax' => $data['car_price_additional'],
                ]);
            }
        } elseif ($service === 'None (車は手配しない）') {
            foreach (range(4, 15) as $h) {
                $key = "none_price_{$h}";
                if (!empty($data[$key])) {
                    $last = \App\Models\Price::create([
                        'name' => $name,
                        'service' => $service,
                        'type' => '-',
                        'duration' => $h,
                        'price_including_tax' => $data[$key],
                    ]);
                }
            }
            if (!empty($data['none_price_additional'])) {
                $last = \App\Models\Price::create([
                    'name' => $name,
                    'service' => $service,
                    'type' => '-',
                    'duration' => '延長料金',
                    'price_including_tax' => $data['none_price_additional'],
                ]);
            }
        } elseif ($service === 'その他' && filled($serviceOther)) {
            foreach (range(4, 15) as $h) {
                $key = "other_price_{$h}";
                if (!empty($data[$key])) {
                    $last = \App\Models\Price::create([
                        'name' => $name,
                        'service' => $serviceOther,
                        'type' => '-',
                        'duration' => $h,
                        'price_including_tax' => $data[$key],
                    ]);
                }
            }
            if (!empty($data['other_price_additional'])) {
                $last = \App\Models\Price::create([
                    'name' => $name,
                    'service' => $serviceOther,
                    'type' => '-',
                    'duration' => '延長料金',
                    'price_including_tax' => $data['other_price_additional'],
                ]);
            }
        }
        return $last ?? new \App\Models\Price();
    }

    protected function getCreatedNotification(): ?\Filament\Notifications\Notification
    {
        if ($this->record && !$this->record->exists) {
            return null;
        }
        return parent::getCreatedNotification();
    }
}
