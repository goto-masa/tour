<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\HotelCase;
use Illuminate\Support\Facades\Log;

class UserRequestForm extends Component
{
    public $guide_name = '';
    public $guest_name = '';
    public $number_of_guests = '';
    public $service_date = '';
    public $finish_time = '';
    public $duration = '';
    public $schedule_time = '';
    public $schedule_item = '';
    public $expenses = [];
    public $expense_item = '';
    public $expense_amount = '';
    public $comment = '';
    public $guide_email = '';
    public $showConfirmModal = false;
    public $schedules = [];
    public $report = '';
    public $hotelCases = [];

    public function mount()
    {
        $this->hotelCases = HotelCase::all();
        $this->schedule_time = '';
        $this->schedule_item = '';
        $this->expenses = [];
    }

    public function addSchedule()
    {
        if ($this->schedule_time && $this->schedule_item) {
            $this->schedules[] = [
                'time' => $this->schedule_time,
                'place' => $this->schedule_item,
            ];
            $this->schedule_time = '';
            $this->schedule_item = '';
        }
    }

    public function removeSchedule($index)
    {
        unset($this->schedules[$index]);
        $this->schedules = array_values($this->schedules);
    }

    public function addExpense()
    {
        if ($this->expense_item && $this->expense_amount) {
            $this->expenses[] = [
                'item' => $this->expense_item,
                'amount' => $this->expense_amount,
            ];
            $this->expense_item = '';
            $this->expense_amount = '';
        }
    }

    public function removeExpense($index)
    {
        unset($this->expenses[$index]);
        $this->expenses = array_values($this->expenses);
    }

    public function openConfirmModal()
    {
        $validated = $this->validate([
            'guide_name' => 'required|string|max:255',
            'guest_name' => 'required|string|max:255',
            'number_of_guests' => 'required|integer|min:1|max:15',
            'service_date' => 'required|date',
            'finish_time' => 'required|date',
            'expenses' => 'array',
            'comment' => 'nullable|string',
            'guide_email' => 'required|email',
            'report' => 'nullable|string',
        ], [
            'guide_name.required' => 'ガイド名は必須です',
            'guest_name.required' => 'ゲスト名は必須です',
            'number_of_guests.required' => 'ゲスト人数は必須です',
            'number_of_guests.integer' => 'ゲスト人数は数字で入力してください',
            'number_of_guests.min' => 'ゲスト人数は1人以上で入力してください',
            'number_of_guests.max' => 'ゲスト人数は15人以下で入力してください',
            'service_date.required' => 'サービス日時は必須です',
            'finish_time.required' => 'ガイド終了日時は必須です',
            'guide_email.required' => 'ガイドメールは必須です',
            'guide_email.email' => 'ガイドメールの形式が正しくありません',
        ]);

        // Guide Finish TimeがGuide Start Timeより前ならエラー
        if (strtotime($this->finish_time) < strtotime($this->service_date)) {
            $this->addError('finish_time', 'ガイド終了日時は開始日時以降の日付を入力してください');
            return;
        }

        $this->showConfirmModal = true;
    }

    public function hideConfirmModal()
    {
        $this->showConfirmModal = false;
    }

    public function submit()
    {
        // 送信確認用のログ出力
        Log::info('UserRequestForm submit called at ' . now());

        session()->flash('message', '申請が送信されました！（' . now()->format('Y-m-d H:i:s') . '）');
        $this->reset(['guide_name', 'guest_name', 'service_date', 'finish_time', 'duration', 'schedules', 'schedule_time', 'schedule_item', 'expenses', 'expense_item', 'expense_amount', 'comment', 'guide_email', 'report']);
        $this->showConfirmModal = false;
    }

    public function render()
    {
        return view('livewire.user-request-form');
    }
}
