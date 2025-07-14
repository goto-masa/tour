<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Bill;
use App\Models\GoogleToken;
use Illuminate\Support\Facades\Auth;
use App\Services\GmailService;

class BillDraftForm extends Component
{
    public Bill $bill;
    public $rfc822_msgid = '';
    public $body = '';
    public $message = '';
    public $error = '';

    public function mount(Bill $bill)
    {
        $this->bill = $bill;
        // 認証チェック
        $user = Auth::user();
        if (!$user || !GoogleToken::where('user_id', $user->id)->exists()) {
            session(['after_google_auth_redirect' => url()->current()]);
            return redirect('/google/oauth');
        }
        if (session()->has('status')) {
            $this->message = session('status');
        }
    }

    public function submit()
    {
        $this->validate([
            'rfc822_msgid' => 'required|string',
            'body' => 'required|string',
        ]);

        try {
            $gmail = app(GmailService::class);
            $gmail->createReplyDraftFromRfc822MsgId($this->rfc822_msgid, $this->body);
            $this->reset(['rfc822_msgid', 'body']);
            session()->flash('status', '下書きを作成しました');
            return redirect()->route('bills.create-draft', $this->bill);
        } catch (\Throwable $e) {
            $this->addError('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.bill-draft-form');
    }
}
