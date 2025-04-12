<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Check In</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Notifikasi -->
            <div id="notification" class="fixed bottom-4 inset-x-0 flex justify-center z-50 translate-y-full transition-all duration-300">
                <div class="bg-white rounded-lg shadow-lg p-4 flex items-center gap-3 max-w-md w-full">
                    <i class="notification-icon text-2xl flex-shrink-0"></i>
                    <div>
                        <h4 class="notification-title font-semibold text-gray-800"></h4>
                        <p class="notification-message text-sm text-gray-600"></p>
                    </div>
                </div>
            </div>

            <!-- Tombol -->
            <div class="flex gap-3 mb-6 justify-center">
                <button id="startScan" class="px-5 py-2 bg-blue-600 text-white rounded-full shadow">Mulai Scan</button>
                <button id="stopScan" class="px-5 py-2 bg-red-500 text-white rounded-full shadow">Berhenti</button>
            </div>

            <!-- Pilihan Kamera -->
            <div id="cameraSelection" class="w-full max-w-xs sm:max-w-md mx-auto mb-4 hidden">
                <select id="cameraList" class="w-full p-2 border border-gray-300 rounded-lg">
                    <option value="">Pilih Kamera</option>
                </select>
            </div>

            <!-- Indikator Loading -->
            <div id="loadingIndicator" class="w-full max-w-xs sm:max-w-md mx-auto text-center py-4 hidden">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-600">Menginisialisasi kamera...</p>
            </div>

            <!-- Area QR Scanner -->
            <div id="reader" class="w-full max-w-xs sm:max-w-md mx-auto rounded-xl overflow-hidden shadow-md"></div>

            <!-- Entry Manual -->
            <div class="bg-white p-4 rounded-2xl shadow-lg mt-6 w-full max-w-md mx-auto">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Entry Manual (Order ID)</h3>
                <form id="manualEntryForm" class="flex gap-2">
                    <input type="text" id="manualOrderId" placeholder="Masukkan Order ID" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" required>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Check</button>
                </form>
            </div>


            <!-- Detail Tiket -->
            <div id="ticketDetails" class="hidden bg-white p-4 rounded-2xl shadow-lg mt-6 w-full max-w-md mx-auto">
                <h3 class="text-lg font-semibold text-green-600 mb-2">Check-In Berhasil</h3>
                <div class="space-y-4"></div>
                <button id="closeDetails" class="mt-4 w-full py-2 bg-blue-600 text-white rounded-full">Lanjut Scan</button>
            </div>
        </div>
    </div>

    <!-- SCRIPT -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let isScanning = false;
        let html5QrCode;
        let selectedCameraId = null;

        function showNotification(type, title, message) {
            const notification = document.getElementById('notification');
            const icon = notification.querySelector('.notification-icon');
            const titleEl = notification.querySelector('.notification-title');
            const messageEl = notification.querySelector('.notification-message');
            const box = notification.querySelector('div');

            icon.className = 'notification-icon text-2xl flex-shrink-0';
            box.className = 'bg-white rounded-lg shadow-lg p-4 flex items-center gap-3 max-w-md w-full';

            if (type === 'success') {
                icon.classList.add('fas', 'fa-check-circle', 'text-green-500');
                box.classList.add('border-l-4', 'border-green-500');
            } else if (type === 'error') {
                icon.classList.add('fas', 'fa-exclamation-circle', 'text-red-500');
                box.classList.add('border-l-4', 'border-red-500');
            }

            titleEl.textContent = title;
            messageEl.textContent = message;

            notification.classList.remove('translate-y-full');
            notification.classList.add('translate-y-0');

            setTimeout(() => {
                notification.classList.remove('translate-y-0');
                notification.classList.add('translate-y-full');
            }, 3000);
        }

        function showTicketDetails(ticket) {
            const ticketDetails = document.getElementById('ticketDetails');
            const content = ticketDetails.querySelector('.space-y-4');

            content.innerHTML = `
                <div class="flex justify-between py-2 border-b border-gray-100"><strong>NIS</strong><span>${ticket.nis}</span></div>
                <div class="flex justify-between py-2 border-b border-gray-100"><strong>Nama</strong><span>${ticket.nama_siswa}</span></div>
                <div class="flex justify-between py-2 border-b border-gray-100"><strong>Kelas</strong><span>${ticket.kelas}</span></div>
                <div class="flex justify-between py-2 border-b border-gray-100"><strong>Email</strong><span>${ticket.email}</span></div>
                <div class="flex justify-between py-2"><strong>No. HP</strong><span>${ticket.no_hp}</span></div>
            `;

            ticketDetails.classList.remove('hidden');
        }

        function hideTicketDetails() {
            document.getElementById('ticketDetails').classList.add('hidden');
            startScanner();
        }

        async function checkCameraPermission() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                stream.getTracks().forEach(track => track.stop());
                return true;
            } catch {
                return false;
            }
        }

        function populateCameraList() {
            const cameraList = document.getElementById('cameraList');
            const cameraSelection = document.getElementById('cameraSelection');
            cameraList.innerHTML = '<option value="">Pilih Kamera</option>';
            document.getElementById('loadingIndicator').classList.remove('hidden');

            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    cameraSelection.classList.remove('hidden');
                    devices.forEach(device => {
                        const option = document.createElement('option');
                        option.value = device.id;
                        option.text = device.label || `Camera ${cameraList.options.length}`;
                        cameraList.appendChild(option);
                    });
                    cameraList.value = devices[0].id;
                    selectedCameraId = devices[0].id;
                }
                document.getElementById('loadingIndicator').classList.add('hidden');
            });
        }

        async function startScanner() {
            const hasPermission = await checkCameraPermission();
            if (!hasPermission) return showNotification('error', 'Akses Kamera', 'Izinkan akses kamera');

            const scannerDiv = document.getElementById('reader');
            scannerDiv.innerHTML = '';
            document.getElementById('loadingIndicator').classList.remove('hidden');

            if (html5QrCode && html5QrCode.isScanning) await stopScanner();
            html5QrCode = new Html5Qrcode("reader");

            try {
                await html5QrCode.start(
                    selectedCameraId || { facingMode: "environment" },
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    onScanSuccess
                );
                isScanning = true;
            } catch (err) {
                showNotification('error', 'Gagal Memulai Kamera', err.message);
            } finally {
                document.getElementById('loadingIndicator').classList.add('hidden');
            }
        }

        async function stopScanner() {
            if (html5QrCode && html5QrCode.isScanning) {
                await html5QrCode.stop();
                isScanning = false;
            }
        }

        function onScanSuccess(decodedText) {
            if (!isScanning) return;
            isScanning = false;

            fetch('/api/scan/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ qr: decodedText })
            })
            .then(res => res.json())
            .then(data => {
                if (data.valid) {
                    showNotification('success', 'Sukses', data.message);
                    showTicketDetails(data.ticket);
                    stopScanner();
                } else {
                    const messages = {
                        ticket_not_found: 'Tiket tidak ditemukan',
                        ticket_pending: 'Tiket belum diverifikasi',
                        ticket_already_used: 'Tiket sudah digunakan'
                    };
                    showNotification('error', 'Gagal', messages[data.message] || 'QR tidak valid');
                    isScanning = true;
                }
            })
            .catch(() => {
                showNotification('error', 'Error', 'Terjadi kesalahan');
                isScanning = true;
            });
        }

        document.getElementById('manualEntryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const orderId = document.getElementById('manualOrderId').value.trim();

        fetch('/api/scan/manual-checkin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ order_id: orderId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.valid) {
                showNotification('success', 'Sukses', data.message);
                showTicketDetails(data.ticket);
            } else {
                const messages = {
                    ticket_not_found: 'Tiket tidak ditemukan',
                    ticket_pending: 'Tiket belum diverifikasi',
                    ticket_already_used: 'Tiket sudah digunakan'
                };
                showNotification('error', 'Gagal', messages[data.message] || 'Tiket tidak valid');
            }
        })
        .catch(() => {
            showNotification('error', 'Error', 'Terjadi kesalahan saat validasi manual');
        });
    });


        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('startScan').addEventListener('click', startScanner);
            document.getElementById('stopScan').addEventListener('click', stopScanner);
            document.getElementById('closeDetails').addEventListener('click', hideTicketDetails);
            document.getElementById('cameraList').addEventListener('change', (e) => {
                selectedCameraId = e.target.value;
                if (isScanning) stopScanner().then(startScanner);
            });
            populateCameraList();
        });
    </script>
</x-app-layout>
