<x-guest-layout>
    <x-auth-session-status class="mb-4 text-white" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" class="text-white/90" />
            <x-text-input id="email" name="email" type="email"
                class="mt-1 block w-full rounded-xl bg-white/80 border-0
                       focus:ring-2 focus:ring-blue-500"
                required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-200" />
        </div>

        <div>
            <x-input-label for="password" value="Password" class="text-white/90" />
            <x-text-input id="password" name="password" type="password"
                class="mt-1 block w-full rounded-xl bg-white/80 border-0
                       focus:ring-2 focus:ring-blue-500"
                required />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-200" />
        </div>

        <button class="w-full py-3 rounded-xl
                       bg-blue-600 hover:bg-blue-700
                       text-white font-semibold transition">
            Masuk
        </button>
    </form>
</x-guest-layout>
