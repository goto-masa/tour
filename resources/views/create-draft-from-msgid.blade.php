<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gmail 下書き作成フォーム（Message-IDから）</title>
</head>
<body>
    <h2>Gmail 下書き作成フォーム（Message-IDから）</h2>
    <p>Gmailの「メッセージのソースを表示」からコピーした <strong>Message-ID</strong> を貼り付けると、そのスレッドの最後のメールに返信する下書きを作成します。</p>
    <form method="POST" action="{{ route('gmail.drafts.store_from_msgid') }}" enctype="multipart/form-data">
        @csrf
        <label>Message-ID:</label><br>
        <input type="text" name="rfc822_msgid" style="width:100%" required placeholder="<xxxxxxxxxx@mail.gmail.com>"><br><br>

        <label>本文 (HTML 可):</label><br>
        <textarea name="body" rows="6" style="width:100%" required></textarea><br><br>

        <label>添付ファイル（任意・複数可）:</label><br>
        <input type="file" name="attachments[]" multiple><br><br>

        <button type="submit">下書きを作成する</button>
    </form>

    <hr>
    <p><a href="{{ url('/create-draft') }}">古い形式のメッセージIDで作成</a> | <a href="{{ url('/create-draft-from-url') }}">URLで作成（現在動作不可）</a></p>
</body>
</html> 