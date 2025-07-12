<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HotelCaseResource\Pages;
use App\Filament\Resources\HotelCaseResource\RelationManagers;
use App\Models\HotelCase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\DateTimePicker;

class HotelCaseResource extends Resource
{
    protected static ?string $model = HotelCase::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = '案件管理';
    protected static ?string $navigationGroup = null;
    protected static ?string $label = '案件';
    protected static ?string $pluralLabel = '案件';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)->schema([
                    Select::make('guide_report_id')
                        ->label('ガイド報告書ID')
                        ->options(function () {
                            return \App\Models\GuideReport::all()->pluck('id', 'id');
                        })
                        ->searchable()
                        ->nullable(),
                    TextInput::make('hotel_name')
                        ->label('ホテル名')
                        ->required(),
                    TextInput::make('writer_name')
                        ->label('記入者名')
                        ->required(),
                    TextInput::make('guest_name')
                        ->label('ゲスト名（代表者名）')
                        ->required(),
                    TextInput::make('guest_count')
                        ->label('ゲスト人数')
                        ->required()
                        ->numeric()
                        ->rule('integer')
                        ->minValue(1),
                    Textarea::make('request_detail')
                        ->label('ご依頼内容（サービスの内容）')
                        ->required(),
                    TextInput::make('dispatch_location')
                        ->label('ガイドを派遣する場所')
                        ->required(),
                    DateTimePicker::make('service_start')
                        ->label('サービス手配日時')
                        ->required()
                        ->default(now())
                        ->withoutSeconds()
                        ->displayFormat('Y-m-d H:i')
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $start = $state;
                            $end = $get('service_end');
                            if ($start && $end) {
                                $hours = (int) ((strtotime($end) - strtotime($start)) / 3600);
                                $set('service_hours', $hours > 0 ? $hours : 1);
                            }
                        }),
                    DateTimePicker::make('service_end')
                        ->label('サービス終了日時')
                        ->required()
                        ->default(now())
                        ->withoutSeconds()
                        ->displayFormat('Y-m-d H:i')
                        ->minDate(fn ($get) => $get('service_start'))
                        ->reactive()
                        ->after('service_start')
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $start = $get('service_start');
                            $end = $state;
                            if ($start && $end) {
                                $hours = (int) ((strtotime($end) - strtotime($start)) / 3600);
                                $set('service_hours', $hours > 0 ? $hours : 1);
                            }
                        }),
                    TextInput::make('service_hours')
                        ->label('サービス提供時間')
                        ->required()
                        ->numeric()
                        ->rule('integer')
                        ->minValue(1)
                        ->default(fn ($get) => (
                            $get('service_start') && $get('service_end')
                                ? (int) ((strtotime($get('service_end')) - strtotime($get('service_start'))) / 3600)
                                : null
                        ))
                        ->reactive(),
                    Select::make('guide_language')
                        ->label('ガイド言語')
                        ->options(require app_path('Support/Languages.php'))
                        ->required(),
                    Select::make('vehicle_type')
                        ->label('希望車種')
                        ->options([
                            'VAN' => 'VAN',
                            'SEDAN' => 'SEDAN',
                        ])
                        ->required(),
                    Textarea::make('desired_areas')
                        ->label('ゲストの方がご希望されている観光エリア、観光スポット、アクティビティ')
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('guide_report_id')
                    ->label('ガイド報告書ID')
                    ->searchable(),
                // 連携済みバッジ（BadgeColumnでラベル表示）
                \Filament\Tables\Columns\BadgeColumn::make('guide_report_linked')
                    ->label('ガイド報告書連携')
                    ->getStateUsing(fn ($record) => $record->guide_report_id ? '連携済み' : null)
                    ->colors(['success' => '連携済み']),
                TextColumn::make('hotel_name')->label('ホテル名')->searchable(),
                TextColumn::make('writer_name')->label('記入者名')->searchable(),
                TextColumn::make('guest_name')->label('ゲスト名（代表者名）')->searchable(),
                TextColumn::make('guest_count')->label('ゲスト人数')->searchable(),
                TextColumn::make('request_detail')->label('ご依頼内容（サービスの内容）')->searchable(),
                TextColumn::make('dispatch_location')->label('ガイドを派遣する場所')->searchable(),
                TextColumn::make('service_start')->label('サービス手配日時')->dateTime('Y/m/d H:i:s')->searchable(),
                TextColumn::make('service_end')->label('サービス終了日時')->dateTime('Y/m/d H:i:s')->searchable(),
                TextColumn::make('service_hours')->label('サービス提供時間')->searchable(),
                TextColumn::make('guide_language')
                    ->label('ガイド言語')
                    ->formatStateUsing(function ($state) {
                        $langs = require app_path('Support/Languages.php');
                        return $langs[$state] ?? $state;
                    })->searchable(),
                TextColumn::make('vehicle_type')->label('希望車種')->searchable(),
                TextColumn::make('desired_areas')->label('ゲストの方がご希望されている観光エリア、観光スポット、アクティビティ')->searchable(),
            ])
            ->filters([
                // 必要に応じて
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->label('コピー')
                    ->icon('heroicon-o-document-duplicate')
                    ->url(fn ($record) => HotelCaseResource::getUrl('create') . '?copy_from=' . $record->id)
                    ->color('secondary'),
                // 請求書作成ボタン（generate_invoice）を削除
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // 請求書作成（generate_invoices）を削除
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
            'index' => Pages\ListHotelCases::route('/'),
            'create' => Pages\CreateHotelCase::route('/create'),
            'edit' => Pages\EditHotelCase::route('/{record}/edit'),
        ];
    }
}
