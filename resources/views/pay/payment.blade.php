<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-semibold text-center mb-8">Payment Details</h3>

                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h5 class="text-lg font-medium mb-4">Informasi Siswa</h5>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="mb-2">NIS:</p>
                                <p class="mb-2">Nama:</p>
                                <p class="mb-2">Kelas:</p>
                            </div>
                            <div>
                                <p class="mb-2">{{ $nis }}</p>
                                <p class="mb-2">{{ $nama_siswa }}</p>
                                <p class="mb-2">{{ $kelas }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h5 class="text-lg font-medium mb-4">Order Summary</h5>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="mb-2">Tiket Siswa:</p>
                                @if($bawa_tamu)
                                <p class="mb-2">Tiket Tamu:</p>
                                @endif
                                <p class="mb-2">Tax (11%):</p>
                                <hr class="my-2">
                                <p class="font-bold">Total Amount:</p>
                            </div>
                            <div class="text-right">
                                <p class="mb-2">1 x Rp 405.000</p>
                                @if($bawa_tamu)
                                <p class="mb-2">1 x Rp 405.000</p>
                                @endif
                                <p class="mb-2">Rp {{ number_format($harga * 0.11, 0, ',', '.') }}</p>
                                <hr class="my-2">
                                <p class="font-bold">Rp {{ number_format($harga * 1.11, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="/payment/process" method="POST" class="max-w-xl mx-auto">
                        @csrf
                        <input type="hidden" name="order_id" value="ORDER-{{ Str::random(8) }}">
                        <input type="hidden" name="nis" value="{{ $nis }}">
                        <input type="hidden" name="nama_siswa" value="{{ $nama_siswa }}">
                        <input type="hidden" name="kelas" value="{{ $kelas }}">
                        <input type="hidden" name="bawa_tamu" value="{{ $bawa_tamu }}">
                        <input type="hidden" name="harga" value="{{ $harga }}">
                        <input type="hidden" name="grandtotal" value="{{ $harga * 1.11 }}">

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   required>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="radio" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" 
                                           name="metodebayar" 
                                           id="bca" 
                                           value="bca" 
                                           checked>
                                    <label class="ml-2 text-gray-700" for="bca">
                                        BCA Virtual Account
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" 
                                           name="metodebayar" 
                                           id="mandiri" 
                                           value="mandiri">
                                    <label class="ml-2 text-gray-700" for="mandiri">
                                        Mandiri Virtual Account
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <button type="submit" 
                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-credit-card mr-2"></i>Proceed to Payment
                            </button>
                            <a href="{{ url()->previous() }}" 
                               class="block w-full text-center py-2 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Back
                            </a>
                        </div>
                    </form>

                    <div class="text-center mt-8">
                        <h3 class="text-gray-700 text-lg mb-3">Butuh Bantuan?</h3>
                        <a href="https://wa.me/6281234567890" 
                           target="_blank"
                           class="inline-flex items-center gap-2 bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition-colors">
                            <i class="fab fa-whatsapp"></i>
                            <span>Hubungi Kami di WhatsApp</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>