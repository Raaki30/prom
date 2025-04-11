<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Tiket</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#1D4ED8',
                        accent: '#0EA5E9',
                        bg: '#F9FAFB'
                    }
                },
            },
        }
    </script>
</head>
<body class="bg-bg font-sans min-h-screen flex flex-col justify-between">

   
    <div class="mt-4">
        <x-header :step="1" />
    </div>

    <div class="flex-grow flex items-center justify-center px-4">
        <div class="w-full max-w-xl">
            <div class="bg-white rounded-2xl shadow-2xl p-8 sm:p-10">
                <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">
                    Pesan Tiket Sekarang
                </h1>

                <form id="nisForm" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Induk Siswa (NIS) / Nama</label>
                        <div class="relative">
                            <input type="text" id="nis" name="nis"
                                placeholder="Contoh: 222310085 atau Raya Nadira"
                                class="w-full px-5 py-3 rounded-xl border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/30 outline-none transition-all"
                                autocomplete="off" required />
                            <i class="fas fa-search absolute top-1/2 right-4 -translate-y-1/2 text-gray-400"></i>
                            
                            <!-- Changed from absolute to relative positioning and improved width constraints -->
                            <div id="searchResults"
                                class="hidden relative w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto z-50">
                            </div>
                        </div>
                    </div>

                    <div id="siswaInfo" class="hidden bg-gray-50 border border-gray-200 rounded-xl p-4 flex items-center gap-4 transition-all">
                        <i class="fas fa-user text-blue-500 text-xl"></i>
                        <div>
                            <p class="text-base font-semibold text-gray-800" id="siswaNama"></p>
                            <p class="text-sm text-gray-600" id="siswaKelas"></p>
                        </div>
                    </div>

                    <button
                        type="submit"
                        id="submitButton"
                        class="w-full bg-primary text-white py-3 rounded-xl font-semibold flex items-center justify-center gap-2 hover:bg-blue-700 transition-all disabled:opacity-50"
                        disabled>
                        <span>Lanjutkan ke Pemesanan</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Hidden Form for Payment -->
    <form class="hidden" action="/payment/init" method="POST" id="paymentForm">
        @csrf
        <input type="hidden" name="nis" id="nisInput">
        <input type="hidden" name="nama_siswa" id="namaSiswaInput">
        <input type="hidden" name="kelas" id="kelasInput">
        <input type="hidden" name="bawa_tamu" value="0">
        <input type="hidden" name="harga" id="hargaInput">
    </form>
    <script>
        async function validateNis(nis) {
            try {
                const response = await fetch(`/api/validate-nis/${nis}`);
                const data = await response.json();
                
                const submitButton = document.getElementById('submitButton');
                const siswaInfo = document.getElementById('siswaInfo');
                
                if (data.valid) {
                    siswaInfo.classList.remove('hidden');
                    document.getElementById('siswaNama').textContent = `Nama: ${data.siswa.nama_siswa}`;
                    document.getElementById('siswaKelas').textContent = `Kelas: ${data.siswa.kelas}`;
                    
                    // Enable submit button
                    submitButton.disabled = false;
                    
                } else {
                    siswaInfo.classList.add('hidden');
                    submitButton.disabled = true;
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Data Tidak Valid',
                        text: 'Data siswa tidak ditemukan',
                        confirmButtonColor: '#3b82f6'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan. Silahkan coba lagi.',
                    confirmButtonColor: '#3b82f6'
                });
            }
        }

        async function performSearch(query) {
            if (query.length < 3) {
                document.getElementById('searchResults').classList.add('hidden');
                return;
            }

            try {
                const response = await fetch(`/api/search-siswa?query=${encodeURIComponent(query)}`);
                const data = await response.json();
                
                const resultsContainer = document.getElementById('searchResults');
                resultsContainer.innerHTML = '';
                
                if (data.length === 0) {
                    resultsContainer.innerHTML = `
                        <div class="p-4 text-center text-gray-500">
                            <i class="fas fa-search text-gray-400 mb-2 text-lg"></i>
                            <p class="text-sm">Tidak ada hasil yang ditemukan</p>
                        </div>
                    `;
                } else {
                    data.forEach(siswa => {
                        const div = document.createElement('div');
                        div.className = 'p-3 cursor-pointer hover:bg-gray-50 border-b border-gray-100 last:border-b-0 transition-colors';
                        div.innerHTML = `
                            <div class="text-blue-500 text-sm font-medium">${siswa.nis}</div>
                            <div class="text-gray-900 font-semibold text-[0.95rem]">${siswa.nama_siswa}</div>
                            <div class="text-gray-500 text-sm">${siswa.kelas}</div>
                        `;
                        div.addEventListener('click', () => {
                            document.getElementById('nis').value = siswa.nis;
                            resultsContainer.classList.add('hidden');
                            validateNis(siswa.nis);
                        });
                        resultsContainer.appendChild(div);
                    });
                }
                
                resultsContainer.classList.remove('hidden');
            } catch (error) {
                console.error('Error searching:', error);
            }
        }

        // Add input event listener for search
        document.getElementById('nis').addEventListener('input', function(e) {
            const query = e.target.value;
            setTimeout(() => {
                performSearch(query);
            }, 300);
            
            // Reset form state
            const submitButton = document.getElementById('submitButton');
            const siswaInfo = document.getElementById('siswaInfo');
            
            submitButton.disabled = true;
            siswaInfo.classList.add('hidden');
        });

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            const searchResults = document.getElementById('searchResults');
            const nisInput = document.getElementById('nis');
            
            if (!nisInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });

        document.getElementById('nisForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (document.getElementById('siswaInfo').classList.contains('hidden')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Data Belum Valid',
                    text: 'Silakan pilih siswa dari daftar pencarian terlebih dahulu',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }
            
            const nis = document.getElementById('nis').value;
            const namaSiswa = document.getElementById('siswaNama').textContent.replace('Nama: ', '');
            const kelas = document.getElementById('siswaKelas').textContent.replace('Kelas: ', '');
            const totalPrice = 405000; // Fixed price without tamu

            // Set values in hidden form
            document.getElementById('nisInput').value = nis;
            document.getElementById('namaSiswaInput').value = namaSiswa;
            document.getElementById('kelasInput').value = kelas;
            document.getElementById('hargaInput').value = totalPrice;

            // Submit the payment form
            document.getElementById('paymentForm').submit();
        });
    </script>

    @include('components.footer')
    @include('components.whatsapp')
</body>
</html>

