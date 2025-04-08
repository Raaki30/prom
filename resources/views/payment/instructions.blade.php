<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petunjuk Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
<div class="mt-4">
        <x-header :step="3" />
    </div>
    <div class="flex-grow container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="p-6">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-semibold mb-4">Petunjuk Pembayaran</h3>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h5 class="text-lg font-medium text-blue-800">Order ID: {{ $tiket->order_id }}</h5>
                            <p class="text-blue-700">Total Harga: Rp {{ number_format($tiket->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h5 class="text-lg font-medium mb-4">Silakan ikuti langkah-langkah berikut:</h5>
                        <ol class="list-decimal pl-5 space-y-4">
                            <li>Transfer ke nomor rekening di bawah ini:</li>

                            <li>
                                @if($tiket->metodebayar === 'bca')
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 transition hover:shadow-md">
                                        <h6 class="font-medium mb-2">Bank BCA Virtual Account:</h6>
                                        <p class="mb-1">Bank: BCA</p>
                                        <p class="mb-1">Nomor Rekening: 1234567890</p>
                                        <p class="mb-4">Atas Nama: Your Company Name</p>
                                        <div>
                                            <h6 class="font-medium mb-2">Cara Pembayaran via BCA VA:</h6>
                                            <ol class="list-decimal pl-5 space-y-1">
                                                <li>Buka aplikasi BCA Mobile.</li>
                                                <li>Pilih menu "Transfer".</li>
                                                <li>Pilih "BCA Virtual Account".</li>
                                                <li>Masukkan nomor VA di atas.</li>
                                                <li>Konfirmasi dan selesaikan pembayaran.</li>
                                            </ol>
                                        </div>
                                    </div>
                                @elseif($tiket->metodebayar === 'mandiri')
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 transition hover:shadow-md">
                                        <h6 class="font-medium mb-2">Bank Mandiri Virtual Account:</h6>
                                        <p class="mb-1">Bank: Mandiri</p>
                                        <p class="mb-1">Nomor Rekening: 0987654321</p>
                                        <p class="mb-4">Atas Nama: Your Company Name</p>
                                        <div>
                                            <h6 class="font-medium mb-2">Cara Pembayaran via Mandiri VA:</h6>
                                            <ol class="list-decimal pl-5 space-y-1">
                                                <li>Buka aplikasi Livin' by Mandiri.</li>
                                                <li>Pilih menu "Transfer".</li>
                                                <li>Pilih "Virtual Account".</li>
                                                <li>Masukkan nomor VA di atas.</li>
                                                <li>Konfirmasi dan selesaikan pembayaran.</li>
                                            </ol>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-red-600 font-semibold">
                                        Metode pembayaran tidak dikenali. Silakan hubungi admin.
                                    </div>
                                @endif
                            </li>

                            <li>Simpan bukti pembayaran Anda.</li>
                            <li>Upload bukti pembayaran melalui form di bawah ini.</li>
                        </ol>
                    </div>

                    <form action="/payment/upload" method="POST" enctype="multipart/form-data" class="mb-8">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $tiket->order_id }}">
                        <div class="mb-4">
                            <label for="bukti" class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Bayar</label>
                            <input type="file" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   id="bukti" 
                                   name="bukti" 
                                   accept="image/*"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">Format: JPG, JPEG, PNG. Max: 2MB</p>
                        </div>
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            Upload Bukti
                        </button>
                    </form>

                    
                </div>
            </div>
        </div>
    </div>
    @include('components.footer')
    @include('components.whatsapp')
</body>
</html>
