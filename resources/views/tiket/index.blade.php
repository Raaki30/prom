@php
    $perPage = request('perPage', 10);
    $search = request('search');
    $query = \App\Models\Tiket::query();

    if ($search) {
        $query->where('nama', 'like', "%$search%")
              ->orWhere('kelas', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
    }

    $data = $query->orderBy('created_at', 'desc')->paginate($perPage);
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Pemesan Tiket') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="{ showModal: false, modalImg: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <!-- Header Filter -->
                <div class="px-4 py-3 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <form method="GET" class="flex items-center gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama, kelas, email..."
                            class="text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <button type="submit" class="text-sm px-3 py-1 bg-indigo-500 hover:bg-indigo-600 text-white rounded">
                            Cari
                        </button>
                    </form>

                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Data per halaman:</span>
                        <select id="perPage" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            
                        </select>
                    </div>
                </div>

                <!-- Tabel -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Metode</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Entry</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Beli</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bukti</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($data as $item)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $item->order_id }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $item->nis }}</td>
                                    <td class="px-4 py-2">{{ $item->nama }}</td>
                                    <td class="px-4 py-2">{{ $item->kelas }}</td>
                                    <td class="px-4 py-2">{{ $item->jumlah_tiket }}</td>
                                    <td class="px-4 py-2">{{ $item->metodebayar }}</td>
                                    <td class="px-4 py-2">
                                        @if($item->status === 'completed')
                                            <span class="text-green-600 font-semibold">Terverifikasi</span>
                                        @else
                                            <span class="text-yellow-500 font-semibold">Menunggu</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($item->entry === 'yes')
                                            <span class="text-green-600 font-semibold">Masuk</span>
                                        @else
                                            <span class="text-red-500 font-semibold">Belum</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        {{ $item->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($item->bukti)
                                            <button 
                                                @click="modalImg = '{{ $item->bukti }}'; showModal = true"
                                                class="text-blue-500 underline"
                                            >
                                                Lihat Bukti
                                            </button>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        
                                            <form method="POST" action="{{ route('tiket.verifikasi', $item->id) }}">
                                                @csrf
                                                <button class="bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-1 rounded">
                                                    Verifikasi
                                                </button>
                                            </form>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Menampilkan <span class="font-semibold">{{ $data->firstItem() }}</span>
                            sampai <span class="font-semibold">{{ $data->lastItem() }}</span>
                            dari <span class="font-semibold">{{ $data->total() }}</span> entri
                        </div>
                        <div class="text-sm">
                            {{ $data->appends(request()->query())->onEachSide(1)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Preview Gambar -->
        <div 
            x-show="showModal" 
            class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50"
            @click="showModal = false"
            x-transition
        >
            <div class="bg-white rounded-lg overflow-hidden shadow-lg max-w-2xl w-full relative"
                 @click.stop>
                <button @click="showModal = false" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-xl font-bold">&times;</button>
                <img :src="modalImg" class="w-full h-auto max-h-[80vh] object-contain" alt="Bukti Pembayaran">
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('perPage').addEventListener('change', function() {
            const perPage = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('perPage', perPage);
            window.location.href = url.toString();
        });
    </script>
    @endpush
</x-app-layout>
