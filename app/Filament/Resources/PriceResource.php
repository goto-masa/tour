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
use Filament\Tables\Columns\TextColumn;

class PriceResource extends Resource
{
    protected static ?string $model = Price::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '価格管理';
    protected static ?string $navigationGroup = null;
    protected static ?string $label = '価格';
    protected static ?string $pluralLabel = '価格';

    public static function form(Form $form): Form
    {
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
                        ])
                        ->required()
                        ->reactive(),
                    Select::make('type')
                        ->label('車種')
                        ->options([
                            'VAN' => 'VAN',
                            'SEDAN' => 'SEDAN',
                        ])
                        ->visible(fn ($get) => $get('service') === '車'),
                    TextInput::make('duration')
                        ->label('サービス利用時間')
                        ->required()
                        ->numeric()
                        ->rule('integer')
                        ->minValue(1),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('ホテル名')->searchable(),
                TextColumn::make('service')->label('対応サービス')->searchable(),
                TextColumn::make('type')->label('車種')->searchable(),
                TextColumn::make('duration')->label('サービス利用時間')->searchable(),
            ])
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
