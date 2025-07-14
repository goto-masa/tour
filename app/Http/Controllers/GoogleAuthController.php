<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Gmail;
use Illuminate\Support\Facades\Auth;

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
        $tokenData = $client->fetchAccessTokenWithAuthCode($request->code);

        // セッションにも一応保存（従来通り）
        session(['google_token' => $client->getAccessToken()]);

        // DB保存処理
        $user = Auth::user();
        if (!$user) {
            abort(401, '認証ユーザーが見つかりません');
        }

        $accessToken  = $tokenData['access_token'] ?? null;
        $refreshToken = $tokenData['refresh_token'] ?? null;
        $expiresIn    = $tokenData['expires_in'] ?? null;
        $tokenType    = $tokenData['token_type'] ?? null;
        $scope        = $tokenData['scope'] ?? null;
        $expiresAt    = $expiresIn ? now()->addSeconds($expiresIn) : null;

        \App\Models\GoogleToken::updateOrCreate(
            ['user_id' => $user->id],
            [
                'access_token'  => $accessToken,
                'refresh_token' => $refreshToken,
                'expires_at'    => $expiresAt,
                'token_type'    => $tokenType,
                'scope'         => $scope,
            ]
        );

        return redirect('/create-draft')
            ->with('status', 'Google 認証が完了しました');
    }
}
