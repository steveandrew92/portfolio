<!DOCTYPE html>
<html lang="id" class="scroll-smooth dark" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $data['profile']['name'] }} — {{ $data['profile']['headline'] }}">
    <title>{{ $data['profile']['name'] }} · Portfolio</title>
    <link rel="icon" href="data:image/svg+xml,{{ rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><rect width="32" height="32" rx="8" fill="#0A192F"/><text x="16" y="21" text-anchor="middle" fill="#22d3ee" font-family="system-ui" font-size="12" font-weight="700">SA</text></svg>') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface text-ink antialiased selection:bg-accent/30 selection:text-ink">
    @yield('content')
</body>
</html>
