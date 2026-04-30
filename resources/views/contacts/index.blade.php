@extends('layouts.app')

@section('title', 'Contacts')

@section('content')
    <section class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-white">Contacts</h1>
                <p class="mt-1 text-sm text-slate-400">Manage the contact list registered in the application.</p>
            </div>

            @auth
                <a href="{{ route('contacts.create') }}" class="inline-flex items-center justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 focus:ring-offset-slate-950">
                    New contact
                </a>
            @endauth
        </div>

        <div class="overflow-hidden rounded-lg border border-white/10 bg-slate-900 shadow-xl shadow-black/20">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10">
                    <thead class="bg-slate-800/80">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-300">ID</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-300">Name</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-300">Contact</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-300">Email</th>
                            @auth
                                <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-300">Actions</th>
                            @endauth
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse ($contacts as $contact)
                            <tr class="transition hover:bg-white/5">
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-300">{{ $contact->id }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm font-medium text-white">{{ $contact->name }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-300">{{ $contact->contact }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-300">{{ $contact->email }}</td>
                                @auth
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('contacts.show', $contact) }}" class="text-sm font-medium text-slate-300 transition hover:text-white">
                                                Details
                                            </a>
                                            <a href="{{ route('contacts.edit', $contact) }}" class="text-sm font-medium text-red-300 transition hover:text-red-200">
                                                Edit
                                            </a>
                                            <form action="{{ route('contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Delete this contact?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-medium text-red-400 transition hover:text-red-300">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endauth
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->check() ? 5 : 4 }}" class="px-4 py-10 text-center text-sm text-slate-400">
                                    No contacts found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
