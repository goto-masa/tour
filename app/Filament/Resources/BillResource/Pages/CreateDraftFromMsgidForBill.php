<?php

namespace App\Filament\Resources\BillResource\Pages;

use App\Filament\Resources\BillResource;
use Filament\Actions;
// use Filament\Resources\Pages\edit;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\GoogleToken;
use App\Services\GmailService;

class CreateDraftFromMsgidForBill extends \Filament\Resources\Pages\EditRecord
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = \App\Filament\Resources\BillResource::class;
    protected static string $view = 'filament.resources.bill-resource.pages.create-draft-from-msgid-for-bill';
    protected static string $recordTitleAttribute = 'name';

    public function mount($record): void
    {
        parent::mount($record);
        // 認証チェック
        $user = Auth::user();
        if (!$user || !GoogleToken::where('user_id', $user->id)->exists()) {
            session(['after_google_auth_redirect' => url()->current()]);
            redirect('/google/oauth');
            exit;
        }
    }

    public function getFormStatePath(): ?string
    {
        return 'data';
    }

    protected function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\TextInput::make('test')->label('テスト'),
        ];
        // return [
        //     Forms\Components\TextInput::make('rfc822_msgid')
        //         ->label('Message-ID')
        //         ->required()
        //         ->placeholder('<xxxxxxxxxx@mail.gmail.com>'),
        //     Forms\Components\Textarea::make('body')
        //         ->label('本文 (HTML 可)')
        //         ->rows(6)
        //         ->required(),
        // ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        // 下書き作成処理
        try {
            $gmail = app(GmailService::class);
            $gmail->createReplyDraftFromRfc822MsgId($data['rfc822_msgid'], $data['body']);
            Notification::make()
                ->title('下書きを作成しました')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('エラー: ' . $e->getMessage())
                ->danger()
                ->send();
        }
        // レコード自体は更新しないのでそのまま返す
        return $record;
    }
}
