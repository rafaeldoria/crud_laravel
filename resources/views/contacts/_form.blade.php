@csrf

<div class="space-y-5">
    <div>
        <label for="name" class="block text-sm font-medium text-slate-200">Name</label>
        <input
            type="text"
            name="name"
            id="name"
            value="{{ old('name', $contact->name ?? '') }}"
            class="mt-2 block w-full rounded-md border border-white/10 bg-slate-950 px-3 py-2 text-sm text-white shadow-sm outline-none transition placeholder:text-slate-500 focus:border-red-400 focus:ring-2 focus:ring-red-400/30"
            required
        >
        @error('name')
            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="contact" class="block text-sm font-medium text-slate-200">Contact</label>
        <input
            type="text"
            name="contact"
            id="contact"
            value="{{ old('contact', $contact->contact ?? '') }}"
            maxlength="9"
            inputmode="numeric"
            class="mt-2 block w-full rounded-md border border-white/10 bg-slate-950 px-3 py-2 text-sm text-white shadow-sm outline-none transition placeholder:text-slate-500 focus:border-red-400 focus:ring-2 focus:ring-red-400/30"
            required
        >
        @error('contact')
            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-slate-200">Email</label>
        <input
            type="email"
            name="email"
            id="email"
            value="{{ old('email', $contact->email ?? '') }}"
            class="mt-2 block w-full rounded-md border border-white/10 bg-slate-950 px-3 py-2 text-sm text-white shadow-sm outline-none transition placeholder:text-slate-500 focus:border-red-400 focus:ring-2 focus:ring-red-400/30"
            required
        >
        @error('email')
            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end gap-3 border-t border-white/10 pt-5">
        <a href="{{ route('contacts.index') }}" class="rounded-md px-4 py-2 text-sm font-medium text-slate-300 transition hover:text-white">
            Cancel
        </a>
        <button type="submit" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 focus:ring-offset-slate-950">
            Save
        </button>
    </div>
</div>
