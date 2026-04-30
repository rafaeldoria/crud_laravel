<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Contact Management')</title>

        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
        <div class="min-h-screen">
            <header class="border-b border-white/10 bg-slate-900/80">
                <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                    <a href="{{ route('contacts.index') }}" class="text-lg font-semibold tracking-tight text-white">
                        Contact Management
                    </a>

                    <nav class="flex items-center gap-3 text-sm">
                        <a href="{{ route('contacts.index') }}" class="text-slate-300 transition hover:text-white">
                            Contacts
                        </a>

                        @auth
                            <span class="hidden text-slate-500 sm:inline">{{ auth()->user()->email }}</span>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-slate-300 transition hover:text-white">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-slate-300 transition hover:text-white">
                                Login
                            </a>
                        @endauth
                    </nav>
                </div>
            </header>

            <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-6 rounded-md border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </body>
</html>
