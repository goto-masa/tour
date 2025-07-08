<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuideReportResource\Pages;
use App\Filament\Resources\GuideReportResource\RelationManagers;
use App\Models\GuideReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GuideReportResource extends Resource
{
    protected static ?string $navigationLabel = 'ガイド報告書管理';
    protected static ?string $model = GuideReport::class;
    protected static ?string $label = 'ガイド報告書';
    protected static ?string $pluralLabel = 'ガイド報告書';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('guide_email')->label('ガイドメール')->required(),
                Forms\Components\TextInput::make('guide_name')->label('ガイド名')->required(),
                Forms\Components\TextInput::make('guest_name')->label('ゲスト名')->required(),
                Forms\Components\TextInput::make('number_of_guests')->label('ゲスト人数')->numeric()->required(),
                Forms\Components\DatePicker::make('service_date')->label('ガイド開始日時')->required(),
                Forms\Components\DatePicker::make('finish_time')->label('ガイド終了日時')->required(),
                Forms\Components\TextInput::make('duration')->label('ガイド合計時間')->numeric(),
                Forms\Components\Textarea::make('report')->label('ガイドレポート'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('guide_email')->label('ガイドメール')->searchable(),
                Tables\Columns\TextColumn::make('guide_name')->label('ガイド名')->searchable(),
                Tables\Columns\TextColumn::make('guest_name')->label('ゲスト名')->searchable(),
                Tables\Columns\TextColumn::make('number_of_guests')->label('ゲスト人数'),
                Tables\Columns\TextColumn::make('service_date')->label('ガイド開始日時')->date('Y/m/d'),
                Tables\Columns\TextColumn::make('finish_time')->label('ガイド終了日時')->date('Y/m/d'),
                Tables\Columns\TextColumn::make('duration')->label('ガイド合計時間'),
                Tables\Columns\TextColumn::make('report')->label('ガイドレポート')->limit(30),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // 一括削除も無効化
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
            'index' => Pages\ListGuideReports::route('/'),
            // create/editページは返さない
        ];
    }
}
