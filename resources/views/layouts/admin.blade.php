@props(['title' => 'Dashboard'])
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>

<body class="bg-gray-50" x-data="{ sidebarOpen: true }">

    <!-- Sidebar Component -->
    <x-admin.sidebar />

    <!-- Header Component -->
    <x-admin.header :title="$title ?? 'Dashboard'" />

    <!-- Main Content -->
    <main class="transition-all duration-300 ease-in-out pt-16" :class="sidebarOpen ? 'ml-64' : 'ml-16'">
        <div class="p-6">
            {{ $slot }}
        </div>
    </main>

</body>

</html>
