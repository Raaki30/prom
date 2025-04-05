<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#1D4ED8',
                        secondary: '#F1F5F9',
                        accent: '#0EA5E9'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-secondary font-sans min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-3xl">
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
            <div class="px-8 py-10">
                <h1 class="text-2xl font-bold text-center text-gray-800 mb-8">🧾 Rincian Pembayaran</h1>

                <!-- Info Siswa -->
                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-700">👨‍🎓 Informasi Siswa</h2>
                    <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                        <div>
                            <p class="mb-2">NIS:</p>
                            <p class="mb-2">Nama:</p>
                            <p class="mb-2">Kelas:</p>
                        </div>
                        <div class="text-right font-medium">
                            <p class="mb-2">{{ $nis }}</p>
                            <p class="mb-2">{{ $nama_siswa }}</p>
                            <p class="mb-2">{{ $kelas }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Pesanan -->
                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-700">📦 Ringkasan Pesanan</h2>
                    <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                        <div>
                            <p class="mb-2">Tiket Siswa:</p>
                            @if($bawa_tamu)
                            <p class="mb-2">Tiket Tamu:</p>
                            @endif
                            <p class="mb-2">Pajak (11%):</p>
                            <hr class="my-2">
                            <p class="font-bold">Total Bayar:</p>
                        </div>
                        <div class="text-right font-medium">
                            <p class="mb-2">1 x Rp 405.000</p>
                            @if($bawa_tamu)
                            <p class="mb-2">1 x Rp 405.000</p>
                            @endif
                            <p class="mb-2">Rp {{ number_format($harga * 0.11, 0, ',', '.') }}</p>
                            <hr class="my-2">
                            <p class="font-bold text-lg text-primary">Rp {{ number_format($harga * 1.11, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Formulir Pembayaran -->
                <form action="/payment/process" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="order_id" value="ORDER-{{ Str::random(8) }}">
                    <input type="hidden" name="nis" value="{{ $nis }}">
                    <input type="hidden" name="nama_siswa" value="{{ $nama_siswa }}">
                    <input type="hidden" name="kelas" value="{{ $kelas }}">
                    <input type="hidden" name="bawa_tamu" value="{{ $bawa_tamu }}">
                    <input type="hidden" name="harga" value="{{ $harga }}">
                    <input type="hidden" name="grandtotal" value="{{ $harga * 1.11 }}">

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Alamat Email</label>
                        <input type="email" name="email" id="email"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary border-gray-300 @error('email') border-red-500 @enderror"
                            value="{{ old('email') }}" required>
                        @error('email')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Nomor HP</label>
                        <input type="tel" name="phone" id="phone"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary border-gray-300 @error('phone') border-red-500 @enderror"
                            value="{{ old('phone') }}" required>
                        @error('phone')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Metode Pembayaran</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="metodebayar" value="bca" checked
                                    class="text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span>BCA Virtual Account</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="metodebayar" value="mandiri"
                                    class="text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span>Mandiri Virtual Account</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mt-6">
                        <button type="submit"
                            class="w-full bg-primary text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-credit-card mr-2"></i>Lanjut ke Pembayaran
                        </button>
                        <a href="{{ url()->previous() }}"
                            class="w-full text-center py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </form>

                <div class="mt-10 text-center">
                    <h3 class="text-gray-700 mb-2">❓ Butuh Bantuan?</h3>
                    <a href="https://wa.me/6281234567890" target="_blank"
                        class="inline-flex items-center gap-2 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                        <i class="fab fa-whatsapp"></i>
                        Hubungi via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
