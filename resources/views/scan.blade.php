<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner - TB</title>
    <script src="https://raw.githack.com/mebjas/html5-qrcode/master/minified/html5-qrcode.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen font-sans">
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <header class="text-center mb-8">
            <h1 class="text-3xl font-medium text-gray-800">QR Code Scanner</h1>
            <p class="text-gray-600 mt-2">Scan your ticket's QR code to check in</p>
        </header>

        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="space-y-4 mb-6">
                <select id="cameraSelect" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent transition-all">
                    <option value="">Loading cameras...</option>
                </select>

                <button id="scanButton" class="w-full py-3.5 px-4 bg-gray-800 text-white rounded-xl font-medium flex items-center justify-center gap-2 hover:bg-gray-700 transform hover:-translate-y-0.5 transition-all focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                    <i class="fas fa-camera"></i>
                    <span>Start Scanning</span>
                </button>
            </div>

            <div id="reader" class="w-full max-w-2xl mx-auto aspect-[4/3] hidden rounded-2xl overflow-hidden shadow-md relative">
                <!-- Scanner will be inserted here -->
            </div>
        </div>

        <div id="ticketDetails" class="fixed bottom-0 left-0 right-0 w-full bg-white rounded-t-3xl shadow-[0_-4px_24px_rgba(0,0,0,0.06)] transform translate-y-full transition-transform duration-300 ease-in-out z-50">
            <div class="max-w-lg mx-auto p-6">
                <h3 class="text-xl font-semibold text-gray-800 text-center mb-6 pb-4 border-b border-gray-100"></h3>
                <div class="space-y-4">
                    <!-- Ticket details will be inserted here -->
                </div>
                <button id="continueButton" class="w-full mt-6 py-3.5 px-4 bg-gray-800 text-white rounded-xl font-medium flex items-center justify-center gap-2 hover:bg-gray-700 transform hover:-translate-y-0.5 transition-all focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                    <i class="fas fa-check"></i>
                    <span>Continue Scanning</span>
                </button>
            </div>
        </div>

        <div id="notification" class="fixed top-5 right-5 transform translate-x-full transition-transform duration-300 ease-in-out">
            <div class="bg-white rounded-lg shadow-lg p-4 flex items-center gap-3 min-w-[300px]">
                <i class="notification-icon text-2xl"></i>
                <div class="flex-1">
                    <h4 class="font-medium text-gray-800 mb-1 notification-title"></h4>
                    <p class="text-sm text-gray-600 notification-message"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let html5QrcodeScanner = null;
        let isScanning = false;
        let audioContext = null;
        let audioBuffer = null;

        // Initialize audio context
        async function initAudio() {
            try {
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const response = await fetch('data:audio/mpeg;base64,SUQzBAAAAAABEVRYWFgAAAAtAAADY29tbWVudABCaWdTb3VuZEJhbmsuY29tIC8gTGFTb25vdGhlcXVlLm9yZwBURU5DAAAAHQAAA1N3aXRjaCBQbHVzIMKpIE5DSCBTb2Z0d2FyZQBUSVQyAAAABgAAAzIyMzUAVFNTRQAAAA8AAANMYXZmNTcuODMuMTAwAAAAAAAAAAAAAAD/80DEAAAAA0gAAAAATEFNRTMuMTAwVVVVVVVVVVVVVUxBTUUzLjEwMFVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVf/zQsRbAAADSAAAAABVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVf/zQMSkAAADSAAAAABVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV');
                const arrayBuffer = await response.arrayBuffer();
                audioBuffer = await audioContext.decodeAudioData(arrayBuffer);
            } catch (error) {
                console.error('Error initializing audio:', error);
            }
        }

        // Play beep sound
        function playBeep() {
            if (audioContext && audioBuffer) {
                const source = audioContext.createBufferSource();
                source.buffer = audioBuffer;
                source.connect(audioContext.destination);
                source.start(0);
            }
        }

        // Show notification
        function showNotification(type, title, message) {
            const notification = document.getElementById('notification');
            const notificationIcon = notification.querySelector('.notification-icon');
            const notificationTitle = notification.querySelector('.notification-title');
            const notificationMessage = notification.querySelector('.notification-message');

            // Set icon and colors based on type
            if (type === 'success') {
                notificationIcon.className = 'fas fa-check-circle text-green-500 notification-icon';
                notification.querySelector('div').classList.add('border-l-4', 'border-green-500');
            } else if (type === 'error') {
                notificationIcon.className = 'fas fa-exclamation-circle text-red-500 notification-icon';
                notification.querySelector('div').classList.add('border-l-4', 'border-red-500');
            }

            notificationTitle.textContent = title;
            notificationMessage.textContent = message;

            // Show notification
            notification.classList.remove('translate-x-full');
            
            // Hide after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
            }, 3000);
        }

        // Show ticket details
        function showTicketDetails(ticket) {
            const ticketDetails = document.getElementById('ticketDetails');
            const content = ticketDetails.querySelector('.space-y-4');
            const title = ticketDetails.querySelector('h3');

            // Set title based on status
            title.textContent = 'Check-In Berhasil';

            // Create ticket information HTML
            const ticketInfo = `
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <strong class="text-gray-700">NIS</strong>
                    <span class="text-gray-600">${ticket.nis}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <strong class="text-gray-700">Nama</strong>
                    <span class="text-gray-600">${ticket.nama_siswa}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <strong class="text-gray-700">Kelas</strong>
                    <span class="text-gray-600">${ticket.kelas}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <strong class="text-gray-700">Email</strong>
                    <span class="text-gray-600">${ticket.email}</span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <strong class="text-gray-700">No. HP</strong>
                    <span class="text-gray-600">${ticket.no_hp}</span>
                </div>
            `;

            content.innerHTML = ticketInfo;
            ticketDetails.classList.remove('translate-y-full');
        }

        // Handle scan result
        function onScanSuccess(decodedText) {
            if (!isScanning) return;
            
            isScanning = false;
            playBeep();

            // Send the scanned data to the server
            fetch('/scan/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ qr: decodedText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    showNotification('success', 'Success', data.message);
                    showTicketDetails(data.ticket);
                } else {
                    let errorMessage = 'Invalid QR Code';
                    if (data.message === 'ticket_not_found') {
                        errorMessage = 'Tiket tidak ditemukan';
                    } else if (data.message === 'ticket_pending') {
                        errorMessage = 'Pembayaran tiket belum diverifikasi';
                    } else if (data.message === 'ticket_already_used') {
                        errorMessage = 'Tiket sudah digunakan';
                    }
                    showNotification('error', 'Error', errorMessage);
                    isScanning = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Error', 'Terjadi kesalahan saat memvalidasi tiket');
                isScanning = true;
            });
        }

        // Initialize scanner
        function initializeScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
            }

            const config = {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                aspectRatio: 4/3,
                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true
                }
            };

            html5QrcodeScanner = new Html5Qrcode("reader");
            const cameraId = document.getElementById('cameraSelect').value;

            html5QrcodeScanner.start(
                cameraId, 
                config,
                onScanSuccess,
                () => {}
            )
            .then(() => {
                document.getElementById('reader').style.display = 'block';
                isScanning = true;
                document.getElementById('scanButton').innerHTML = '<i class="fas fa-stop"></i><span>Stop Scanning</span>';
                document.getElementById('scanButton').classList.remove('bg-gray-800', 'hover:bg-gray-700');
                document.getElementById('scanButton').classList.add('bg-red-600', 'hover:bg-red-700');
            })
            .catch(err => {
                console.error('Error starting scanner:', err);
                showNotification('error', 'Error', 'Failed to start scanner');
            });
        }

        // Stop scanner
        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop()
                .then(() => {
                    document.getElementById('reader').style.display = 'none';
                    isScanning = false;
                    document.getElementById('scanButton').innerHTML = '<i class="fas fa-camera"></i><span>Start Scanning</span>';
                    document.getElementById('scanButton').classList.remove('bg-red-600', 'hover:bg-red-700');
                    document.getElementById('scanButton').classList.add('bg-gray-800', 'hover:bg-gray-700');
                });
            }
        }

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize audio
            initAudio();

            // Get available cameras
            Html5Qrcode.getCameras()
            .then(devices => {
                if (devices && devices.length) {
                    const cameraSelect = document.getElementById('cameraSelect');
                    cameraSelect.innerHTML = devices.map(device =>
                        `<option value="${device.id}">${device.label}</option>`
                    ).join('');
                }
            })
            .catch(err => {
                console.error('Error getting cameras:', err);
                showNotification('error', 'Error', 'Failed to get cameras');
            });

            // Handle scan button click
            document.getElementById('scanButton').addEventListener('click', function() {
                if (isScanning) {
                    stopScanner();
                } else {
                    initializeScanner();
                }
            });

            // Handle continue button click
            document.getElementById('continueButton').addEventListener('click', function() {
                document.getElementById('ticketDetails').classList.add('translate-y-full');
                isScanning = true;
            });
        });
    </script>
</body>
</html> 