<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\UserRequestForm;
use App\Livewire\BillDraftForm;

Route::get('/', function () {
    return view('welcome');
});

// googleアカウント認証
Route::get('/google/oauth', [\App\Http\Controllers\GoogleAuthController::class, 'redirect']);
Route::get('/google/oauth/callback', [\App\Http\Controllers\GoogleAuthController::class, 'callback'])->name('google.oauth.callback');

// Message-IDから下書きを作成するためのルート
Route::get('/create-draft-from-msgid', function () {
    return view('create-draft-from-msgid');
})->name('gmail.drafts.create_from_msgid');
Route::post('/gmail/drafts/from-msgid', [\App\Http\Controllers\GmailDraftController::class, 'storeFromMsgId'])
    ->name('gmail.drafts.store_from_msgid');

// Billの下書き作成フォーム
Route::get('/bills/{bill}/create-draft', BillDraftForm::class)->name('bills.create-draft');

// ガイド報告書入力フォーム
Route::get('/user-request', UserRequestForm::class)->name('user-request');
Route::get('/user-request/submitted', function () {
    return view('submitted');
})->name('guide-report.submitted');
