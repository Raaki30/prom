@php
    use App\Models\Tiket;

    $totalTiket = Tiket::sum('jumlah_tiket');
    $totalPendapatan = Tiket::where('status', 'completed')->sum(\DB::raw('jumlah_tiket * harga'));
    $terverifikasi = Tiket::where('status', 'completed')->count();
    $sudahCheckIn = Tiket::where('entry', 'yes')->count();
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm font-medium">Tiket Terjual</h3>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalTiket }}</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm font-medium">Total Pendapatan</h3>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm font-medium">Terverifikasi</h3>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $terverifikasi }}</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm font-medium">Sudah Check In</h3>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $sudahCheckIn }}</p>
                </div>

            </div>

            
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                <p class="font-bold">Reminder</p>
                <p>{{ __("Pastikan total pendapatan sesuai dengan mutasi rekening") }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
