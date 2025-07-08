<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PriceResource\Pages;
use App\Filament\Resources\PriceResource\RelationManagers;
use App\Models\Price;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;

class PriceResource extends Resource
{
    protected static ?string $model = Price::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '価格管理';
    protected static ?string $navigationGroup = null;
    protected static ?string $label = '価格';
    protected static ?string $pluralLabel = '価格';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        $isEdit = $form->getOperation() === 'edit';

        if ($isEdit) {
            return $form->schema([
                Grid::make(1)->schema([
                    TextInput::make('name')->label('ホテル名')->required(),
                    Select::make('service')->label('対応サービス')->options([
                        'ガイド' => 'ガイド',
                        '車' => '車',
                        'None (車は手配しない）' => 'None (車は手配しない）',
                        'その他' => 'その他',
                    ])->required()->reactive(),
                    TextInput::make('service_other')
                        ->label('その他の対応サービス')
                        ->visible(fn ($get) => $get('service') === 'その他')
                        ->reactive(),
                    Select::make('type')
                        ->label('車種')
                        ->options([
                            'VAN' => 'VAN',
                            'SEDAN' => 'SEDAN',
                            'その他' => 'その他',
                            '-' => '-',
                        ])
                        ->visible(fn ($get) => $get('service') === '車' || $get('type') !== '-')
                        ->default('-')
                        ->reactive(),
                    TextInput::make('type_other')
                        ->label('その他の車種')
                        ->visible(fn ($get) => $get('service') === '車' && $get('type') === 'その他')
                        ->reactive(),
                    TextInput::make('duration')->label('サービス利用時間'),
                    TextInput::make('price_including_tax')->label('税込価格')->required()->numeric()->minValue(0)->maxValue(10000000),
                ]),
            ]);
        }

        return $form
            ->schema([
                Grid::make(1)->schema([
                    TextInput::make('name')
                        ->label('ホテル名')
                        ->required(),
                    Select::make('service')
                        ->label('対応サービス')
                        ->options([
                            'ガイド' => 'ガイド',
                            '車' => '車',
                            'None (車は手配しない）' => 'None (車は手配しない）',
                            'その他' => 'その他',
                        ])
                        ->reactive(),
                    TextInput::make('service_other')
                        ->label('その他の対応サービス')
                        ->visible(fn ($get) => $get('service') === 'その他')
                        ->reactive(),
                    Select::make('type')
                        ->label('車種')
                        ->options(fn ($get) =>
                            $get('service') === '車'
                                ? [
                                    'VAN' => 'VAN',
                                    'SEDAN' => 'SEDAN',
                                    'その他' => 'その他',
                                ]
                                : []
                        )
                        ->visible(fn ($get) => $get('service') === '車')
                        ->reactive(),
                    // 4~15時間＋additionalの入力欄（ガイド用）
                    Fieldset::make('ガイドの単価テーブル')
                        ->visible(fn ($get) => $get('service') === 'ガイド')
                        ->schema([
                            ...collect(range(4, 15))->map(fn($h) =>
                                TextInput::make("guide_price_{$h}")
                                    ->label("{$h}時間の税込価格")
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10000000)
                            )->toArray(),
                            TextInput::make('guide_price_additional')
                                ->label('延長料金の税込価格')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(10000000),
                        ]),
                    // その他の車種入力欄（車>その他）
                    TextInput::make('type_other')
                        ->label('その他の車種')
                        ->visible(fn ($get) => $get('service') === '車' && !in_array($get('type'), ['VAN', 'SEDAN']))
                        ->required(fn ($get) => $get('service') === '車' && $get('type') === 'その他')
                        ->reactive(),
                    // 4~15時間＋additionalの入力欄（車用）
                    Fieldset::make('車の単価テーブル')
                        ->visible(fn ($get) => $get('service') === '車' && in_array($get('type'), ['VAN', 'SEDAN', 'その他']))
                        ->schema([
                            ...collect(range(4, 15))->map(fn($h) =>
                                TextInput::make("car_price_{$h}")
                                    ->label("{$h}時間の税込価格")
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10000000)
                            )->toArray(),
                            TextInput::make('car_price_additional')
                                ->label('延長料金の税込価格')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(10000000),
                        ]),
                    // 4~15時間＋additionalの入力欄（その他サービス用）
                    Fieldset::make(fn ($get) => ($get('service_other') ? $get('service_other') : 'その他') . 'の単価テーブル')
                        ->visible(fn ($get) => $get('service') === 'その他' && filled($get('service_other')))
                        ->schema([
                            ...collect(range(4, 15))->map(fn($h) =>
                                TextInput::make("other_price_{$h}")
                                    ->label("{$h}時間の税込価格")
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10000000)
                            )->toArray(),
                            TextInput::make('other_price_additional')
                                ->label('延長料金の税込価格')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(10000000),
                        ]),
                    // 4~15時間＋additionalの入力欄（None用）
                    Fieldset::make('単価テーブル')
                        ->visible(fn ($get) => $get('service') === 'None (車は手配しない）')
                        ->schema([
                            ...collect(range(4, 15))->map(fn($h) =>
                                TextInput::make("none_price_{$h}")
                                    ->label("{$h}時間の税込価格")
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10000000)
                            )->toArray(),
                            TextInput::make('none_price_additional')
                                ->label('延長料金の税込価格')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(10000000),
                        ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query
                    ->orderBy('name')
                    // サービス優先順位: ガイド→None（車は手配しない）→車→その他
                    ->orderByRaw("FIELD(service, 'ガイド', 'None (車は手配しない）', '車') DESC")
                    // その他の対応サービスは日本語・アルファベット順
                    ->orderByRaw("
                        CASE 
                            WHEN service NOT IN ('ガイド', 'None (車は手配しない）', '車') THEN service 
                            ELSE NULL 
                        END ASC
                    ")
                    // 車の場合はtypeでグルーピング（車以外は''でまとめる）
                    ->orderByRaw("CASE WHEN service = '車' THEN type ELSE '' END")
                    // サービス利用時間: 数値昇順、最後に延長
                    ->orderByRaw("
                        CASE 
                            WHEN duration REGEXP '^[0-9]+$' THEN CAST(duration AS UNSIGNED)
                            WHEN duration = '延長料金' OR duration = 'additional' THEN 99999
                            ELSE 99998
                        END
                    ");
            })
            ->columns([
                TextColumn::make('name')->label('ホテル名')->searchable(),
                TextColumn::make('service')->label('対応サービス')->searchable()
                    ->formatStateUsing(fn($state) => $state ?: 'None (車は手配しない）'),
                TextColumn::make('type')->label('車種')->searchable(),
                TextColumn::make('duration')->label('サービス利用時間')->searchable(),
                TextColumn::make('price_excluding_tax')
                    ->label('税抜価格')
                    ->formatStateUsing(fn ($record) => $record->price_including_tax ? round($record->price_including_tax / 1.1) : null)
                    ->searchable(),
                TextColumn::make('price_including_tax')->label('税込価格')->searchable(),
            ])
            ->defaultSort('name')
            ->filters([
                // 必要に応じて
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrices::route('/'),
            'create' => Pages\CreatePrice::route('/create'),
            'edit' => Pages\EditPrice::route('/{record}/edit'),
        ];
    }
}
