<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Manager - @yield('title')</title>
    @vite('resources/css/app.css')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"
            integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.min.css">
</head>
<body class="bg-zinc-900 min-h-screen">
<nav class="bg-zinc-950 border-b border-zinc-800 backdrop-blur-sm relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center justify-between w-full">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('tasks.index') }}"
                       class="text-2xl font-bold bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent tracking-tight">
                        Task Manager
                    </a>
                </div>

                <div class="hidden sm:flex sm:space-x-1">
                    <a href="{{ route('tasks.index') }}"
                       class="{{ request()->routeIs('tasks.*') ? 'bg-zinc-800 text-cyan-400 font-medium' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-800/50' }} inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                        Tasks
                    </a>
                    <a href="{{ route('projects.index') }}"
                       class="{{ request()->routeIs('projects.*') ? 'bg-zinc-800 text-cyan-400 font-medium' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-800/50' }} inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                        Projects
                    </a>
                </div>

                <div class="sm:hidden flex items-center">
                    <button id="hamburger-btn" class="relative w-6 h-5 flex flex-col justify-between cursor-pointer group">
                        <span class="hamburger-line block h-0.5 w-full bg-zinc-400 rounded transition-all duration-300 ease-in-out group-hover:bg-cyan-400"></span>
                        <span class="hamburger-line block h-0.5 w-full bg-zinc-400 rounded transition-all duration-300 ease-in-out group-hover:bg-cyan-400"></span>
                        <span class="hamburger-line block h-0.5 w-full bg-zinc-400 rounded transition-all duration-300 ease-in-out group-hover:bg-cyan-400"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden sm:hidden absolute top-full left-0 right-0 bg-zinc-950 border-b border-zinc-800 shadow-lg">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('tasks.index') }}"
               class="{{ request()->routeIs('tasks.*') ? 'bg-zinc-800 text-cyan-400 font-medium' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-800/50' }} block px-4 py-3 rounded-lg text-base font-medium transition-all duration-200">
                Tasks
            </a>
            <a href="{{ route('projects.index') }}"
               class="{{ request()->routeIs('projects.*') ? 'bg-zinc-800 text-cyan-400 font-medium' : 'text-zinc-400 hover:text-zinc-100 hover:bg-zinc-800/50' }} block px-4 py-3 rounded-lg text-base font-medium transition-all duration-200">
                Projects
            </a>
        </div>
    </div>
</nav>

<main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    @if(session('success'))
        <div
            class="mb-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl backdrop-blur-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                          clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl backdrop-blur-sm">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                          clip-rule="evenodd"/>
                </svg>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @yield('content')
</main>

<script>
    $(document).ready(function() {
        $('#hamburger-btn').on('click', function() {
            const $lines = $(this).find('.hamburger-line');
            const $menu = $('#mobile-menu');

            $menu.toggleClass('hidden');

            if (!$menu.hasClass('hidden')) {
                $lines.eq(0).css('transform', 'rotate(45deg) translate(5px, 5px)');
                $lines.eq(1).css('opacity', '0');
                $lines.eq(2).css('transform', 'rotate(-45deg) translate(6px, -6px)');
            } else {
                $lines.eq(0).css('transform', 'rotate(0) translate(0, 0)');
                $lines.eq(1).css('opacity', '1');
                $lines.eq(2).css('transform', 'rotate(0) translate(0, 0)');
            }
        });
        // Zamknij menu po kliknięciu w link
        $('#mobile-menu a').on('click', function() {
            const $lines = $('#hamburger-btn').find('.hamburger-line');
            $('#mobile-menu').addClass('hidden');
            $lines.eq(0).css('transform', 'rotate(0) translate(0, 0)');
            $lines.eq(1).css('opacity', '1');
            $lines.eq(2).css('transform', 'rotate(0) translate(0, 0)');
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#hamburger-btn, #mobile-menu').length) {
                const $lines = $('#hamburger-btn').find('.hamburger-line');
                $('#mobile-menu').addClass('hidden');
                $lines.eq(0).css('transform', 'rotate(0) translate(0, 0)');
                $lines.eq(1).css('opacity', '1');
                $lines.eq(2).css('transform', 'rotate(0) translate(0, 0)');
            }
        });
    });
</script>

@stack('scripts')
</body>
</html>
