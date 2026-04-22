{{-- resources/views/kasir/sale.blade.php --}}
@extends('layouts.kasirlayout')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-600 to-blue-900 text-white px-14 py-8"
        x-data="kasir()">

        {{-- TOP BAR --}}
        <div class="flex items-center justify-between mb-8">

            {{-- Logo --}}
            <div class="flex flex-col items-center gap-2">
                <img src="{{ asset('logo1.png') }}" class="w-20 rounded-lg">
                <p class="text-xs text-gray-200">Cafe & Space</p>
            </div>

            {{-- Kategori --}}
            <div class="flex gap-3">
                <template x-for="k in kategori" :key="k.id">
                    <button
                        @click="kategoriAktif = k.id"
                        :class="kategoriAktif === k.id
                            ? 'bg-white text-blue-700'
                            : 'border border-white text-white'"
                        class="px-6 py-2 rounded-full text-sm">
                        <span x-text="k.nama"></span>
                    </button>
                </template>
            </div>

            {{-- KANAN: PROFIL + CART --}}
            <div class="flex items-center gap-3">
                {{-- Checkout Button --}}
                <button @click="showCheckout = true"
                        class="bg-white text-blue-700 px-5 py-2 rounded-lg font-semibold">
                    🛒 <span x-text="cart.length"></span>
                </button>
                {{-- PROFIL DROPDOWN --}}
                <div x-data="{ open: false }" class="relative z-50">

                    <button
                        @click="open = !open"
                        class="w-10 h-10
                            rounded-full
                            border-2 border-white
                            bg-white hover:bg-blue-700
                            flex items-center justify-center
                            text-blue-600 font-bold
                            transition">

                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </button>
                    
                    {{-- DROPDOWN --}}
                    <div x-show="open"
                        x-transition
                        @click.outside="open = false"
                        class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50">

                        <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-2 px-4 py-3 text-sm text-black hover:bg-gray-100">
                            Profil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left flex items-center gap-2 px-4 py-3 text-sm hover:bg-red-50 text-red-600">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div> 

        {{-- PRODUK --}}
        <div class="grid grid-cols-6 gap-6">
            <template x-for="p in filteredProduk" :key="p.id">
                <div class="bg-white text-gray-800 rounded-xl p-3 shadow">
                    <img :src="p.gambar" class="h-32 w-full object-cover rounded-lg">
                    <p class="mt-2 font-semibold text-sm" x-text="p.nama"></p>
                    <p class="text-xs text-gray-500">Rp <span x-text="p.harga"></span></p>
                    <button @click="tambah(p)"
                            class="mt-2 w-full bg-blue-600 text-white rounded-lg py-1">
                        +
                    </button>
                </div>
            </template>
        </div>

        {{-- OVERLAY --}}
        <div x-show="showCheckout"
            class="fixed inset-0 bg-black/40 z-40"
            @click="showCheckout = false">
        </div>

        {{-- PANEL TRANSAKSI --}}
        <div
            x-show="showCheckout"
            @click.outside="showCheckout = false"
            x-transition:enter="transition transform duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition transform duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed top-0 right-0 w-96 h-full bg-white text-gray-800 shadow-xl p-6 z-50"
        >


        <h2 class="text-lg font-bold mb-4">Transaksi</h2>

        {{-- LIST ITEM --}}
        <div class="space-y-3 max-h-[50vh] overflow-y-auto">
            <template x-for="item in cart" :key="item.id">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-sm" x-text="item.nama"></p>
                        <p class="text-xs text-gray-500">
                            x<span x-text="item.qty"></span>
                        </p>
                    </div>

                    <div class="font-semibold text-sm">
                        Rp <span
                            x-text="(item.qty * item.harga).toLocaleString()">
                        </span>
                    </div>
                </div>
            </template>
        </div>

        {{-- TOTAL --}}
        <div class="border-t mt-4 pt-4 flex justify-between font-bold">
            <span>Total</span>
            <span>
                Rp <span x-text="total.toLocaleString()"></span>
            </span>
        </div>

        {{-- METODE BAYAR --}}
        <div class="mt-4">
            <p class="font-semibold mb-2">Metode Bayar</p>
            <div class="flex gap-2">
                <template x-for="m in ['cash','transfer','qr']">
                    <button
                        @click="metodeBayar = m"
                        :class="metodeBayar === m
                            ? 'bg-blue-600 text-white'
                            : 'border'"
                        class="px-3 py-1 rounded text-sm capitalize">
                        <span x-text="m"></span>
                    </button>
                </template>
            </div>
        </div>

        {{-- CATATAN --}}
        <textarea
            x-model="catatan"
            class="mt-4 w-full border rounded p-2 text-sm"
            placeholder="Catatan (opsional)">
        </textarea>

        {{-- BAYAR --}}
        <button
            @click="simpan()"
            :disabled="!metodeBayar || loading"
            class="mt-4 w-full bg-green-600 text-white py-2 rounded-lg
                font-semibold disabled:opacity-50">
            <span x-show="!loading">BAYAR</span>
            <span x-show="loading">MEMPROSES...</span>
        </button>

    </div>
    {{-- LOADING TRANSAKSI --}}
    <div
        x-show="loading"
        class="fixed inset-0 z-[60] bg-white/70 backdrop-blur
            flex items-center justify-center"
    >
        <div class="text-center">
            <div class="relative w-20 h-20 mx-auto">
                <div class="absolute inset-0 rounded-full
                            bg-blue-600 animate-ping"></div>
                <div class="relative rounded-full w-20 h-20
                            bg-blue-700 flex items-center justify-center
                            text-white font-bold">
                    Hay
                </div>
            </div>
            <p class="mt-4 text-sm text-gray-700">
                Memproses transaksi...
            </p>
        </div>
    </div>
    {{-- TOAST --}}
    <div
        x-show="toast.show"
        x-transition
        class="fixed bottom-6 right-6 z-[70]"
    >
        <div
            :class="toast.type === 'success'
                ? 'bg-green-600'
                : 'bg-red-600'"
            class="text-white px-6 py-4 rounded-xl shadow-lg
                flex items-center gap-3"
        >
            <span class="text-xl">
                <template x-if="toast.type === 'success'">✅</template>
                <template x-if="toast.type === 'error'">❌</template>
            </span>
            <span x-text="toast.message"></span>
        </div>
    </div>
</div>

<script>
    function kasir() {
        return {
            kategori: @json($kategori),
            produk: @json($produk),
            kategoriAktif: {{ $kategori->first()['id'] ?? 'null' }},
            cart: [],
            showCheckout: false,
            metodeBayar: null,
            catatan: '',
            loading: false,
            toast: {
                show: false,
                message: '',
                type: 'success'
            },

            showToast(msg, type = 'success') {
                this.toast.message = msg
                this.toast.type = type
                this.toast.show = true

                setTimeout(() => {
                    this.toast.show = false
                }, 3000)
            },

            get filteredProduk() {
                return this.produk.filter(
                    p => p.id_kategori === this.kategoriAktif
                )
            },

            get total() {
                return this.cart.reduce(
                    (sum, i) => sum + (i.qty * i.harga), 0
                )
            },

            tambah(p) {
                const item = this.cart.find(i => i.id === p.id)
                item ? item.qty++ : this.cart.push({ ...p, qty: 1 })
            },

            async simpan() {
                if (this.loading) return
                this.loading = true

                try {
                    const res = await fetch("{{ route('kasir.transaksi.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            metode_bayar: this.metodeBayar,
                            catatan: this.catatan,
                            cart: this.cart
                        })
                    })

                    const data = await res.json()
                    if (!res.ok) throw data

                    this.showToast('Transaksi berhasil')

                    this.cart = []
                    this.metodeBayar = null
                    this.catatan = ''
                    this.showCheckout = false

                } catch (e) {
                    this.showToast('Transaksi gagal', 'error')
                    console.error(e)
                } finally {
                    this.loading = false
                }
            }
        }
    }
</script>

@endsection
