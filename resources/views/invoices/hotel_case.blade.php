<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>請求書</title>
    <style>
        body { font-family: 'Meiryo', sans-serif; }
        .header { background: #2c4a7a; color: #fff; padding: 10px; font-size: 2em; }
        .info-table, .service-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .info-table td { padding: 4px; }
        .service-table th, .service-table td { border: 1px solid #333; padding: 6px; }
        .service-table th { background: #e6f0fa; }
    </style>
</head>
<body>
    <div class="header">ご請求書</div>
    <table class="info-table">
        <tr>
            <td>
                <b>株式会社KRO</b><br>
                東京都中央区銀座1-12-4<br>
                N&E・BLD-7階<br>
                登録番号: T2010401147387<br>
                service@krotravelengineering.jp
            </td>
            <td align="right">
                To<br>
                {{ $hotelCase->hotel_name }}
            </td>
        </tr>
    </table>
    <table class="service-table">
        <tr>
            <th>サービス内容 / 項目</th>
            <th>日付</th>
            <th>単価</th>
            <th>数量</th>
            <th>合計</th>
        </tr>
        <tr>
            <td>{{ $hotelCase->request_detail }}</td>
            <td>{{ optional($hotelCase->service_start)->format('n/j') }}</td>
            <td>例: 54,545</td>
            <td>1</td>
            <td>例: 54,545</td>
        </tr>
        {{-- 必要に応じて複数行 --}}
    </table>
</body>
</html> 