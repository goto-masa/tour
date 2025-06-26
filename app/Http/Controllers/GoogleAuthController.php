<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Gmail;

class GoogleAuthController extends Controller
{
    private function client(): Client
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        // 必要なスコープを配列で一括指定
        $client->setScopes([
            Gmail::GMAIL_COMPOSE,   // 下書き・送信
            Gmail::GMAIL_MODIFY     // メッセージ読み取り＆下書き threadId 取得
        ]);
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->setRedirectUri(route('google.oauth.callback'));
        return $client;
    }

    public function redirect()
    {
        // dd(route('google.oauth.callback'));
        return redirect()->away($this->client()->createAuthUrl());
    }

    public function callback(Request $request)
    {
        $client = $this->client();
        $client->fetchAccessTokenWithAuthCode($request->code);

        session(['google_token' => $client->getAccessToken()]);

        return redirect('/create-draft')
            ->with('status', 'Google 認証が完了しました');
    }
}
