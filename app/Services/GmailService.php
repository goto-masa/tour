<?php

namespace App\Services;

use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Draft;
use Google\Service\Gmail\Message;
use Symfony\Component\Mime\Email;
use Illuminate\Support\Collection;

class GmailService
{
    private Gmail   $gmail;
    private string  $userEmail;   // 自分の Gmail アドレスを保持

    public function __construct()
    {
        /* ── ① Google Client 設定 ───────────────────── */
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));

        /*  セッションからトークン取得  */
        if (!$token = session('google_token')) {
            throw new \RuntimeException('Google token not found. Authenticate first.');
        }
        $client->setAccessToken($token);

        /*  アクセストークンの自動更新  */
        if ($client->isAccessTokenExpired() && $client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            session(['google_token' => $client->getAccessToken()]);
        }

        /*  下書き作成＋メール読み取りに必要なスコープ */
        $client->setScopes([
            Gmail::GMAIL_COMPOSE,   // ドラフト作成
            Gmail::GMAIL_MODIFY     // メッセージ取得・スレッド操作
        ]);

        $this->gmail = new Gmail($client);

        /* ── ② プロフィール取得（1回だけ） ───────────── */
        $profile          = $this->gmail->users->getProfile('me');
        $this->userEmail  = $profile->getEmailAddress(); // 例: you@example.com
    }

    /* ────────────────────────────────────────────────
       返信下書きを作成
    ──────────────────────────────────────────────── */
    public function createReplyDraftFromMessageId(
        string $messageId,
        string $htmlBody,
        array  $attachments = []
    ): Draft {

        /* ① 元メールのメタ取得 */
        $msg      = $this->gmail->users_messages->get('me', $messageId, ['format' => 'metadata']);
        $threadId = $msg->getThreadId();
        $headers  = collect($msg->getPayload()->getHeaders());

        $from        = $headers->firstWhere('name', 'From')->value  ?? '';
        $subjectBase = $headers->firstWhere('name', 'Subject')->value ?? '';
        $inReplyTo   = $headers->firstWhere('name', 'Message-ID')->value ?? '';

        /* ② MIME メール生成 */
        $email = (new Email())
            ->from($this->userEmail)       // ★ 実メールアドレスで RFC 2822 合格
            ->to($from)
            ->subject('Re: ' . $subjectBase)
            ->html($htmlBody)
            ->text(strip_tags($htmlBody));

        $email->getHeaders()
            ->addTextHeader('In-Reply-To', $inReplyTo)
            ->addTextHeader('References',  $inReplyTo);

        foreach ($attachments as $path) {
            if (is_file($path)) {
                $email->attachFromPath($path);
            }
        }

        $raw = rtrim(strtr(base64_encode($email->toString()), '+/', '-_'), '=');

        /* ③ Draft 作成 */
        $message = new Message([
            'raw'      => $raw,
            'threadId' => $threadId,
        ]);

        $draft = new Draft(['message' => $message]);

        return $this->gmail->users_drafts->create('me', $draft);
    }

    /* ────────────────────────────────────────────────
       スレッドの最終メールに返信下書きを作成
    ──────────────────────────────────────────────── */
    public function createReplyDraftForLastMessageInThread(
        string $idFromUrl,
        string $htmlBody,
        array $attachments = []
    ): Draft {
        try {
            // 1. URLから取得したIDはメッセージIDなので、まずメッセージ情報を取得
            $message = $this->gmail->users_messages->get('me', $idFromUrl, ['format' => 'minimal']);
        } catch (\Google\Service\Exception $e) {
            if ($e->getCode() === 404 || $e->getCode() === 400) {
                throw new \RuntimeException('有効なメッセージIDがURLから見つかりません。URLを確認してください。', $e->getCode(), $e);
            }
            throw $e;
        }

        // 2. メッセージ情報から本当のスレッドIDを取得
        $threadId = $message->getThreadId();

        // 3. スレッド情報を取得
        $thread = $this->gmail->users_threads->get('me', $threadId);

        // 4. スレッド内のメッセージリストを取得
        $messages = $thread->getMessages();
        if (empty($messages)) {
            throw new \RuntimeException("スレッドにメッセージがありません。");
        }

        // 5. 配列の最後の要素（＝最新のメッセージ）を取得
        $lastMessage = end($messages);

        // 6. 最新メッセージのIDを使い、既存の返信作成メソッドを呼び出す
        return $this->createReplyDraftFromMessageId(
            $lastMessage->getId(),
            $htmlBody,
            $attachments
        );
    }

    /* ────────────────────────────────────────────────
       RFC822 Message-ID から返信下書きを作成
    ──────────────────────────────────────────────── */
    public function createReplyDraftFromRfc822MsgId(
        string $rfc822MsgIdWithBrackets,
        string $htmlBody,
        array $attachments = []
    ): Draft {
        // 1. Message-IDから不要な文字を除去
        $rfc822MsgId = trim($rfc822MsgIdWithBrackets, '<> ');

        // 2. Message-IDを使ってメッセージを検索
        $query = 'rfc822msgid:' . $rfc822MsgId;
        $list = $this->gmail->users_messages->listUsersMessages('me', [
            'q' => $query,
            'maxResults' => 1
        ]);

        $messages = $list->getMessages();
        if (empty($messages)) {
            throw new \RuntimeException("指定されたMessage-IDのメールが見つかりません。");
        }

        // 3. API内部用のメッセージIDを取得
        $messageId = $messages[0]->getId();

        // 4. 既存の「スレッドの最後に返信」機能を呼び出す
        return $this->createReplyDraftForLastMessageInThread(
            $messageId,
            $htmlBody,
            $attachments
        );
    }

    /* ────────────────────────────────────────────────
       受信トレイ最新メールを取得（デバッグ／選択用）
    ──────────────────────────────────────────────── */
    public function listRecentMessages(int $max = 5): array
    {
        $list = $this->gmail->users_messages->listUsersMessages('me', [
            'q'          => 'in:inbox',
            'maxResults' => $max,
        ]);

        $result = [];
        foreach ($list->getMessages() as $msg) {
            $meta    = $this->gmail->users_messages->get('me', $msg->getId(), ['format' => 'metadata']);
            $headers = collect($meta->getPayload()->getHeaders());

            $result[] = [
                'messageId' => $msg->getId(),
                'subject'   => $headers->firstWhere('name', 'Subject')->value ?? '(No Subject)',
                'from'      => $headers->firstWhere('name', 'From')->value ?? '(Unknown)',
                'snippet'   => $meta->getSnippet(),
            ];
        }
        return $result;
    }
}
