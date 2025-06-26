<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GmailService;
use Illuminate\Support\Facades\File;

class GmailDraftController extends Controller
{
    public function store(Request $req, GmailService $gmail)
    {
        $req->validate([
            'message_id'  => 'required|string',
            'body'        => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file',
        ]);

        $attachmentPaths = [];
        if ($req->hasFile('attachments')) {
            $tempDir = storage_path('app/temp/' . uniqid());
            File::makeDirectory($tempDir, 0755, true);

            foreach ($req->file('attachments') as $file) {
                $path = $file->move($tempDir, $file->getClientOriginalName());
                $attachmentPaths[] = $path->getPathname();
            }
        }

        try {
            $draft = $gmail->createReplyDraftFromMessageId(
                $req->input('message_id'),
                $req->input('body'),
                $attachmentPaths
            );
            $status = '下書きを作成しました: ' . $draft->getId();
        } finally {
            if (!empty($attachmentPaths)) {
                File::deleteDirectory($tempDir);
            }
        }

        return back()->with('status', $status);
    }

    public function storeFromMsgId(Request $req, GmailService $gmail)
    {
        $req->validate([
            'rfc822_msgid' => 'required|string',
            'body'         => 'required|string',
            'attachments'  => 'nullable|array',
            'attachments.*'  => 'file',
        ]);

        // 添付ファイルの一時保存
        $attachmentPaths = [];
        $tempDir = null;
        if ($req->hasFile('attachments')) {
            $tempDir = storage_path('app/temp/' . uniqid());
            File::makeDirectory($tempDir, 0755, true);

            foreach ($req->file('attachments') as $file) {
                $path = $file->move($tempDir, $file->getClientOriginalName());
                $attachmentPaths[] = $path->getPathname();
            }
        }

        try {
            $draft = $gmail->createReplyDraftFromRfc822MsgId(
                $req->input('rfc822_msgid'),
                $req->input('body'),
                $attachmentPaths
            );
            $status = 'Message-IDからの下書きを作成しました: ' . $draft->getId();
        } finally {
            if ($tempDir) {
                File::deleteDirectory($tempDir);
            }
        }

        return back()->with('status', $status);
    }

    public function showRecent(GmailService $gmail)
    {
        $messages = $gmail->listRecentMessages(3); // 最新3件
        return view('recent-messages', ['messages' => $messages]);
    }
}
