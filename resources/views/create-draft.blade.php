<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gmail 下書き作成フォーム</title>
</head>
<body>
    <h2>Gmail 下書き作成フォーム</h2>
    <form method="POST" action="{{ route('gmail.drafts.store') }}" enctype="multipart/form-data">
        @csrf
        <label>Gmail メッセージ ID（URL の末尾）:</label><br>
        <input type="text" name="message_id" style="width:100%" required><br><br>

        <label>本文 (HTML 可):</label><br>
        <textarea name="body" rows="6" style="width:100%" required></textarea><br><br>

        <label>添付ファイル（任意・複数可）:</label><br>
        <input type="file" name="attachments[]" multiple><br><br>

        <button type="submit">下書きを作成する</button>
    </form>
</body>
</html>
