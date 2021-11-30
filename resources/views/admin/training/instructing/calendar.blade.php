<!doctype html>
<html lang="en" class="overflow-x-hidden">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="https://ganderoceanicoca.ams3.digitaloceanspaces.com/resources/media/img/brand/sqr/2021-square-bluetsp.png">
    <meta property="og:description" content="@yield('og-description')">
    <meta property="og:image" content="@yield('og-image')">
    <meta property="og:url" content="{{ Request::url() }}">
    <meta name="twitter:card" content="summary_large_image">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    @livewireStyles
</head>
<body class="flex flex-col h-screen">
<nav class="bg-gray-100 dark:bg-gray-800">
    <div class="lg:mx-auto lg:max-w-6xl px-14 py-5 flex flex-row items-center md:space-y-0 justify-between">
        <div class="">
            <a href="{{route('index')}}">
                <img class="h-12 block dark:hidden" src="https://ganderoceanicoca.ams3.digitaloceanspaces.com/resources/media/img/brand/bnr/2021-bnr-bluetsp.png" alt="">
                <img class="h-12 hidden dark:block" src="https://ganderoceanicoca.ams3.digitaloceanspaces.com/resources/media/img/brand/bnr/ZQO_BNR_TSPWHITE.png" alt="">
            </a>
        </div>
    </div>
</nav>
<main class="flex-grow">
    <div class="lg:mx-auto lg:max-w-6xl px-14 py-6">
        <h1 class="text-xl">Calendar</h1>
        <p class="mb-2 ">Upcoming training and OTS sessions</p>
        <div>
            <livewire:training.instructing.calendar before-calendar-view="livewire/training/instructing/before-calendar" :drag-and-drop-enabled="false"/>
        </div>
    </div>
</main>
@livewireScripts
@livewireCalendarScripts
</body>
</html>

