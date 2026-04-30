@extends('layouts.app')

@section('title', $contact->name)

@section('content')
    <section class="mx-auto max-w-2xl space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-white">{{ $contact->name }}</h1>
                <p class="mt-1 text-sm text-slate-400">Contact details.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('contacts.edit', $contact) }}" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 focus:ring-offset-slate-950">
                    Edit
                </a>
                <form action="{{ route('contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Delete this contact?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-md border border-red-400/40 px-4 py-2 text-sm font-semibold text-red-300 transition hover:border-red-300 hover:text-red-200">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border border-white/10 bg-slate-900 shadow-xl shadow-black/20">
            <dl class="divide-y divide-white/10">
                <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-400">ID</dt>
                    <dd class="text-sm text-white sm:col-span-2">{{ $contact->id }}</dd>
                </div>
                <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-400">Name</dt>
                    <dd class="text-sm text-white sm:col-span-2">{{ $contact->name }}</dd>
                </div>
                <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-400">Contact</dt>
                    <dd class="text-sm text-white sm:col-span-2">{{ $contact->contact }}</dd>
                </div>
                <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-400">Email</dt>
                    <dd class="text-sm text-white sm:col-span-2">{{ $contact->email }}</dd>
                </div>
                <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-400">Created at</dt>
                    <dd class="text-sm text-white sm:col-span-2">{{ $contact->created_at?->format('Y-m-d H:i') }}</dd>
                </div>
                <div class="grid gap-1 px-6 py-4 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-400">Updated at</dt>
                    <dd class="text-sm text-white sm:col-span-2">{{ $contact->updated_at?->format('Y-m-d H:i') }}</dd>
                </div>
            </dl>
        </div>

        <a href="{{ route('contacts.index') }}" class="inline-flex text-sm font-medium text-slate-300 transition hover:text-white">
            Back to contacts
        </a>
    </section>
@endsection
