<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>...</title>
    <!-- ... -->
</head>
<style>
    html{
        background-color: oklch(96.7% 0.003 264.542);
    }
    .p-form-submited{
        max-width: 42rem;
        margin: 30px auto 0;
        padding: 20px;
        background-color: #fff;
        text-align: center;
        border-radius: 0.75rem;
    }
    .p-form-submited h2 {
        font-size: 1.25rem;
        font-weight: bold;
        margin-bottom: 2rem;
        text-align: center;
    }
    .p-form-submited p {
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
    .p-form-submited a {
        display: inline-block;
        width: auto;
        min-width: 180px;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        border-radius: 0.5rem;
        background: #2563eb;
        color: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: background 0.2s;
        text-decoration: none;
    }
    .p-form-submited a:hover {
        background: #1d4ed8;
    }
    @media screen and (max-width:768px){
        .p-form-submited{
            margin: 0;
            max-width: 100vw;
            padding: 1rem 0.5rem;
            border-radius: 0;
        }
        .p-form-submited h2 {
            font-size: 1.1rem;
            margin-bottom: 1.2rem;
        }
        .p-form-submited p {
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }
        .p-form-submited a {
            width: 100%;
            min-width: 0;
            padding: 0.9rem 0;
            font-size: 1rem;
        }
    }
</style>
<div class="p-form-submited shadow">
    <h2>申請が送信されました</h2>
    <div class="mb-8 text-center">
        <p>ご入力いただきありがとうございました。<br>Thank you for your submission.</p>
        {{-- <a href="{{ route('user-request', ['from' => 'submitted']) }}" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">フォームに戻る</a> --}}
    </div>
</div>