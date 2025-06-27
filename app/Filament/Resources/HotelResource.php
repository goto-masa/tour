<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HotelResource\Pages;
use App\Filament\Resources\HotelResource\RelationManagers;
use App\Models\Hotel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class HotelResource extends Resource
{
    protected static ?string $model = Hotel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'マスター管理（ホテル管理）';//サイドバー
    protected static ?string $navigationGroup = null;
    protected static ?string $label = 'ホテル';//新規作成見出し
    protected static ?string $pluralLabel = 'ホテル';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)->schema([
                    TextInput::make('name')
                        ->label('ホテル名')
                        ->required(),
                    TextInput::make('address')
                        ->label('住所')
                        ->required(),
                    TextInput::make('tel')
                        ->label('電話番号')
                        ->required()
                        ->numeric()
                        ->rule('integer')
                        ->minValue(1),
                    TextInput::make('contact')
                        ->label('担当連絡先')
                        ->required(),
                    Select::make('lang')
                        ->label('言語')
                        ->options(require app_path('Support/Languages.php'))
                        ->required(),
                    Textarea::make('note')
                        ->label('備考'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('ホテル名')->searchable(),
                TextColumn::make('address')->label('住所')->searchable(),
                TextColumn::make('tel')->label('電話番号')->searchable(),
                TextColumn::make('contact')->label('担当連絡先')->searchable(),
                TextColumn::make('lang')
                    ->label('言語')
                    ->formatStateUsing(function ($state) {
                        $langs = require app_path('Support/Languages.php');
                        return $langs[$state] ?? $state;
                    })->searchable(),
                TextColumn::make('note')->label('備考')->limit(30)->searchable(),
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
            'index' => Pages\ListHotels::route('/'),
            'create' => Pages\CreateHotel::route('/create'),
            'edit' => Pages\EditHotel::route('/{record}/edit'),
        ];
    }
}
