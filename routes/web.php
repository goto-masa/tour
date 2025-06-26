<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/google/oauth', [\App\Http\Controllers\GoogleAuthController::class, 'redirect']);
Route::get('/google/oauth/callback', [\App\Http\Controllers\GoogleAuthController::class, 'callback'])->name('google.oauth.callback');

// メッセージIDから下書きを作成するためのルート
Route::get('/create-draft', function () {
    return view('create-draft');
});
Route::post('/gmail/drafts', [\App\Http\Controllers\GmailDraftController::class, 'store'])
    ->name('gmail.drafts.store');

    // routes/web.php に追加
Route::get('/gmail/recent', [\App\Http\Controllers\GmailDraftController::class, 'showRecent']);

// Message-IDから下書きを作成するためのルート
Route::get('/create-draft-from-msgid', function () {
    return view('create-draft-from-msgid');
})->name('gmail.drafts.create_from_msgid');

Route::post('/gmail/drafts/from-msgid', [\App\Http\Controllers\GmailDraftController::class, 'storeFromMsgId'])
    ->name('gmail.drafts.store_from_msgid');
