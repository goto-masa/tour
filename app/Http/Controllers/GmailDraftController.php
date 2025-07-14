<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GmailService;
use Illuminate\Support\Facades\File;

class GmailDraftController extends Controller
{

    public function showRecent(GmailService $gmail)
    {
        $messages = $gmail->listRecentMessages(3); // 最新3件
        return view('recent-messages', ['messages' => $messages]);
    }
}
