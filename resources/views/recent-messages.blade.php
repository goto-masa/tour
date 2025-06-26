<h2>最近のGmailメッセージ（API経由）</h2>
<ul>
    @foreach ($messages as $msg)
        <li>
            <strong>Subject:</strong> {{ $msg['subject'] }}<br>
            <strong>From:</strong> {{ $msg['from'] }}<br>
            <strong>Snippet:</strong> {{ $msg['snippet'] }}<br>
            <strong>Message ID:</strong>
            <input type="text" readonly value="{{ $msg['messageId'] }}" style="width: 100%;">
            <hr>
        </li>
    @endforeach
</ul>
