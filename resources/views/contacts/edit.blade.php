@extends('layouts.app')

@section('title', 'Edit contact')

@section('content')
    <section class="mx-auto max-w-2xl space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-white">Edit contact</h1>
            <p class="mt-1 text-sm text-slate-400">Update the contact information.</p>
        </div>

        <form action="{{ route('contacts.update', $contact) }}" method="POST" class="rounded-lg border border-white/10 bg-slate-900 p-6 shadow-xl shadow-black/20">
            @method('PUT')
            @include('contacts._form', ['contact' => $contact])
        </form>
    </section>
@endsection
