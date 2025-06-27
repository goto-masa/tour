<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? '業務管理システム' }}</title>
    
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto py-8 px-2 sm:px-4">
        {{ $slot }}
    </div>
    @livewireScripts
</body>
</html>