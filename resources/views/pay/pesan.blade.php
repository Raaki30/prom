<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Tiket</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">

</head>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #1a5f7a 0%, #2c3e50 100%);
        min-height: 100vh;
    }

    .form-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .input-field {
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .input-field:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .loading-spinner {
        display: none;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .whatsapp-section {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border-radius: 12px;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }

    .whatsapp-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    

    @keyframes popup {
        0% {
            opacity: 0;
            transform: scale(0.9);
        }

        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    #default-modal {
        justify-content: center;
        align-items: center;
        animation: popup 0.1s ease-in-out;
    }

    @media (max-width: 640px) {
        .form-container {
            margin: 1rem;
            padding: 1.5rem;
        }

        .input-group {
            flex-direction: column;
            gap: 0.5rem;
        }

        .btn-primary {
            width: 100%;
        }
    }

    .search-result-item {
        padding: 0.75rem 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .search-result-item:last-child {
        border-bottom: none;
    }
    
    .search-result-item:hover {
        background-color: #f8fafc;
    }
    
    .search-result-item.selected {
        background-color: #e5e7eb;
    }
    
    .search-result-item .nis {
        color: #3b82f6;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .search-result-item .nama {
        color: #111827;
        font-weight: 600;
        font-size: 0.95rem;
    }
    
    .search-result-item .kelas {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 400;
    }

    #searchResults {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        margin-top: 0.5rem;
        max-height: 300px;
        overflow-y: auto;
        position: absolute;
        width: 100%;
        z-index: 9999;
    }

    #searchResults::-webkit-scrollbar {
        width: 8px;
    }

    #searchResults::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    #searchResults::-webkit-scrollbar-thumb {
        background: #c5c5c5;
        border-radius: 4px;
    }

    #searchResults::-webkit-scrollbar-thumb:hover {
        background: #a0a0a0;
    }

    .search-result-empty {
        padding: 1rem;
        text-align: center;
        color: #6b7280;
        font-size: 0.875rem;
    }

    .input-with-icon {
        position: relative;
    }

    .input-with-icon input {
        padding-left: 2.5rem;
    }

    .input-with-icon i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }
</style>

<body class="min-h-screen flex flex-col items-center justify-center p-4">

    <!-- Header with Logo -->
    <header class="flex flex-col items-center mb-8">
        
    </header>

    <div class="w-full max-w-2xl mx-auto relative">
        <div class="form-container p-6 sm:p-8 mb-6 sm:mb-8 relative">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 text-center">Pesan Tiket</h2>
            
            <form id="nisForm" class="space-y-4 sm:space-y-6">
                @csrf
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Nomor Induk Siswa (NIS) / Nama Siswa</label>
                    <div class="relative">
                        <div class="input-with-icon">
                            <i class="fas fa-search"></i>
                            <input type="text" id="nis" name="nis" 
                                   class="input-field w-full rounded-lg px-4 py-2.5 focus:outline-none" 
                                   placeholder="Cari berdasarkan NIS atau nama siswa..." 
                                   autocomplete="off"
                                   required>
                        </div>
                        <div id="searchResults" class="hidden">
                            <!-- Search results will be populated here -->
                        </div>
                    </div>
                </div>

                <div id="siswaInfo" class="hidden">
                    <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
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
                        class="btn-primary w-full text-white py-2.5 sm:py-3 rounded-lg font-medium flex items-center justify-center gap-2 opacity-50 cursor-not-allowed"
                        disabled>
                    <span>Lanjutkan ke Pembayaran</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>

        <!-- <div class="whatsapp-section p-4 sm:p-6 text-center">
            <h3 class="text-white text-base sm:text-lg font-medium mb-2 sm:mb-3">Butuh Bantuan?</h3>
            <a href="https://wa.me/6281234567890" target="_blank" 
               class="inline-flex items-center gap-2 bg-green-500 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg font-medium hover:bg-green-600 transition-colors">
                <i class="fab fa-whatsapp text-lg sm:text-xl"></i>
                <span class="text-sm sm:text-base">Hubungi Kami di WhatsApp</span>
            </a>
        </div>
    </div> -->

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
                    siswaInfo.classList.add('show');
                    document.getElementById('siswaNama').textContent = `Nama: ${data.siswa.nama_siswa}`;
                    document.getElementById('siswaKelas').textContent = `Kelas: ${data.siswa.kelas}`;
                    
                    // Enable submit button
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitButton.disabled = false;
                    
                } else {
                    siswaInfo.classList.add('hidden');
                    siswaInfo.classList.remove('show');
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
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
                        <div class="search-result-empty">
                            <i class="fas fa-search text-gray-400 mb-2 text-lg"></i>
                            <p>Tidak ada hasil yang ditemukan</p>
                        </div>
                    `;
                } else {
                    data.forEach(siswa => {
                        const div = document.createElement('div');
                        div.className = 'search-result-item';
                        div.innerHTML = `
                            <div class="nis">${siswa.nis}</div>
                            <div class="nama">${siswa.nama_siswa}</div>
                            <div class="kelas">${siswa.kelas}</div>
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
            
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            submitButton.disabled = true;
            siswaInfo.classList.remove('show');
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
            
            if (!document.getElementById('siswaInfo').classList.contains('show')) {
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

