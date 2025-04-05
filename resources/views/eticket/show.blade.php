<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - {{ $tiket->nama }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white shadow-2xl rounded-2xl p-8 max-w-lg w-full border-t-8 border-blue-600 relative">

        {{-- Judul --}}
        <h1 class="text-3xl font-extrabold text-blue-600 text-center mb-6">E-Ticket</h1>

        {{-- QR Code atau status --}}
        @if($tiket->entry !== 'yes')
            <div class="flex justify-center mb-6">
                <div class="bg-white p-4 border border-gray-300 rounded-xl shadow">
                    <img 
                        src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $tiket->order_id }}" 
                        alt="QR Code"
                        class="w-36 h-36"
                    >
                    <p class="text-center text-xs mt-2 text-gray-500">Scan untuk check-in</p>
                </div>
            </div>
        @else
            <div class="text-center mb-6">
                <span class="inline-block px-4 py-2 bg-red-100 text-red-600 text-sm font-semibold rounded-full">
                    Tiket Sudah Digunakan
                </span>
            </div>
        @endif

        {{-- Informasi Tiket --}}
        <div class="space-y-3 text-sm">
            <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700">Order ID</span>
                <span class="text-gray-600">{{ $tiket->order_id }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700">Nama</span>
                <span class="text-gray-600">{{ $tiket->nama }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700">NIS</span>
                <span class="text-gray-600">{{ $tiket->nis }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700">Kelas</span>
                <span class="text-gray-600">{{ $tiket->kelas }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700">Email</span>
                <span class="text-gray-600">{{ $tiket->email }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="font-medium text-gray-700">No. HP</span>
                <span class="text-gray-600">{{ $tiket->phone }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Status</span>
                <span class="capitalize text-gray-600">{{ $tiket->status }}</span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-8 text-center text-xs text-gray-400">
            Tunjukkan halaman ini saat check-in
        </div>

    </div>
</body>
</html>
