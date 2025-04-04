<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Tiket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                },
            },
        }
    </script>
</head>

<body class="min-h-screen font-sans bg-gradient-to-br from-[#1a5f7a] to-[#2c3e50] flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-2xl mx-auto relative">
        <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-lg p-6 sm:p-8 mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 text-center">Pesan Tiket</h2>
            
            <form id="nisForm" class="space-y-4 sm:space-y-6">
                @csrf
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Nomor Induk Siswa (NIS) / Nama Siswa</label>
                    <div class="relative">
                        <div class="relative">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="nis" name="nis" 
                                   class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl bg-white text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-100 focus:outline-none transition-all" 
                                   placeholder="Cari berdasarkan NIS atau nama siswa..." 
                                   autocomplete="off"
                                   required>
                        </div>
                        <div id="searchResults" class="hidden absolute w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-lg max-h-[300px] overflow-y-auto z-50">
                            <!-- Search results will be populated here -->
                        </div>
                    </div>
                </div>

                <div id="siswaInfo" class="hidden transform transition-all duration-200">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-user-circle text-green-500 text-xl"></i>
                            <div>
                                <p class="text-sm font-medium text-green-800" id="siswaNama"></p>
                                <p class="text-sm text-green-700" id="siswaKelas"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" 
                        id="submitButton"
                        class="w-full py-3 px-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-medium flex items-center justify-center gap-2 hover:translate-y-[-1px] hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-none transition-all duration-200"
                        disabled>
                    <span>Lanjutkan ke Pembayaran</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
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
                const response = await fetch(`/validate-nis/${nis}`);
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
                console.error('Error validating NIS:', error);
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
                const response = await fetch(`/search-siswa?query=${encodeURIComponent(query)}`);
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
</body>

</html>

