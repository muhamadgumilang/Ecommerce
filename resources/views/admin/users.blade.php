<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengguna Admin</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="p-6">
    <main class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Pengguna</h1>
        @foreach ($users as $user)
            <div class="border-b py-3">{{ $user->name }} - {{ $user->email }} - {{ $user->role }}</div>
        @endforeach
    </main>
</body>

</html>
