@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <section class="mx-auto max-w-md space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-white">Login</h1>
            <p class="mt-1 text-sm text-slate-400">Use the admin account to manage contacts.</p>
        </div>

        <form action="{{ route('login.store') }}" method="POST" class="rounded-lg border border-white/10 bg-slate-900 p-6 shadow-xl shadow-black/20">
            @csrf

            <div class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-200">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        class="mt-2 block w-full rounded-md border border-white/10 bg-slate-950 px-3 py-2 text-sm text-white shadow-sm outline-none transition placeholder:text-slate-500 focus:border-red-400 focus:ring-2 focus:ring-red-400/30"
                        required
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-200">Password</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="mt-2 block w-full rounded-md border border-white/10 bg-slate-950 px-3 py-2 text-sm text-white shadow-sm outline-none transition placeholder:text-slate-500 focus:border-red-400 focus:ring-2 focus:ring-red-400/30"
                        required
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-300">
                    <input type="checkbox" name="remember" value="1" class="rounded border-white/10 bg-slate-950 text-red-600 focus:ring-red-500">
                    Remember me
                </label>

                <button type="submit" class="w-full rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 focus:ring-offset-slate-950">
                    Login
                </button>
            </div>
        </form>

        <p class="text-sm text-slate-400">
            Admin: <span class="font-medium text-slate-200">admin@admin.com</span> / <span class="font-medium text-slate-200">123456</span>
        </p>
    </section>
@endsection
