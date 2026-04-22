{{-- TOP NAVBAR --}}
<div class="flex items-center justify-between px-8 mx-6 py-4 bg-gray-300/40 backdrop-blur-lg border border-white/30 rounded-2xl shadow-lg mb-6">

    {{-- KIRI: JUDUL / BREADCRUMB --}}
    <div class="text-lg font-semibold text-gray-200">
        {{ ucfirst(str_replace('-', ' ', request()->segment(2) ?? 'Dashboard')) }}
    </div>

    {{-- KANAN: USER DROPDOWN --}}
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open"
            class="flex items-center gap-3 bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-2xl transition">

            {{-- AVATAR --}}
            <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            {{-- NAMA --}}
            <div class="text-sm font-medium text-gray-700">
                {{ auth()->user()->name }}
            </div>

            {{-- ICON --}}
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        {{-- DROPDOWN --}}
        <div x-show="open" @click.outside="open = false"
            x-transition
            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg overflow-visible z-50">

            <a href="{{ route('profile.edit') }}"
               class="block px-4 py-3 text-sm hover:bg-gray-100">
                Profil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left px-4 py-3 text-sm hover:bg-red-50 text-red-600">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
