@extends('layouts.app')

@section('title', 'New contact')

@section('content')
    <section class="mx-auto max-w-2xl space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-white">New contact</h1>
            <p class="mt-1 text-sm text-slate-400">Fill in the fields below to create a contact.</p>
        </div>

        <form action="{{ route('contacts.store') }}" method="POST" class="rounded-lg border border-white/10 bg-slate-900 p-6 shadow-xl shadow-black/20">
            @include('contacts._form')
        </form>
    </section>
@endsection
