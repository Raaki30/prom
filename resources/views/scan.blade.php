<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner - TB</title>
    <script src="https://raw.githack.com/mebjas/html5-qrcode/master/minified/html5-qrcode.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: #f8f9fa;
            color: #2c3e50;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 2rem;
            margin: 0;
            color: #2c3e50;
            font-weight: 500;
        }
        .header p {
            font-size: 1rem;
            color: #6c757d;
            margin: 10px 0 0;
        }
        .scanner-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .menu {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 24px;
        }
        .camera-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            background: white;
            color: #2c3e50;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .camera-select:focus {
            outline: none;
            border-color: #90a4ae;
            box-shadow: 0 0 0 3px rgba(144,164,174,0.1);
        }
        .camera-select option {
            background: white;
            color: #2c3e50;
        }
        .scan-button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: #37474f;
            color: white;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s ease;
        }
        .scan-button:hover {
            background: #455a64;
            transform: translateY(-1px);
        }
        .scan-button:active {
            transform: translateY(0);
        }
        .scanner-container {
            width: 100%;
            max-width: 640px;
            height: auto;
            aspect-ratio: 4 / 3;
            margin: 20px auto;
            display: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .scanner-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 2px solid rgba(55,71,79,0.1);
            border-radius: 16px;
            pointer-events: none;
        }
        .ticket-details {
            display: none;
            position: fixed;
            bottom: -100%;
            left: 0;
            right: 0;
            width: 100%;
            background: white;
            padding: 20px;
            border-radius: 20px 20px 0 0;
            box-shadow: 0 -4px 24px rgba(0,0,0,0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
        }
        .ticket-details.show {
            bottom: 0;
            transform: translateY(0);
        }
        .ticket-details-content {
            max-width: 500px;
            margin: 0 auto;
            padding-bottom: env(safe-area-inset-bottom);
        }
        .ticket-details h3 {
            color: #2c3e50;
            margin: 0 0 20px;
            text-align: center;
            font-size: 1.25rem;
            font-weight: 600;
            padding-bottom: 12px;
            border-bottom: 1px solid #edf2f7;
        }
        .ticket-details p {
            margin: 12px auto;
            color: #2c3e50;
            font-size: 0.95rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f7fafc;
        }
        .ticket-details p:last-of-type {
            border-bottom: none;
            margin-bottom: 20px;
        }
        .ticket-details p strong {
            color: #2d3748;
            font-weight: 500;
            flex: 1;
        }
        .ticket-details p span {
            color: #4a5568;
            text-align: right;
            flex: 2;
        }
        .continue-button {
            width: 100%;
            max-width: 500px;
            margin: 16px auto 0;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: #37474f;
            color: white;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .continue-button:hover {
            background: #455a64;
            transform: translateY(-1px);
        }
        .continue-button:active {
            transform: translateY(0);
        }
        .notification {
            position: fixed;
            top: 20px;
            right: -400px;
            background: white;
            padding: 16px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            max-width: 350px;
        }
        .notification.show {
            right: 20px;
        }
        .notification.success {
            border-left: 4px solid #37474f;
        }
        .notification.error {
            border-left: 4px solid #e74c3c;
        }
        .notification-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .notification-icon {
            font-size: 1.25rem;
        }
        .notification.success .notification-icon {
            color: #37474f;
        }
        .notification.error .notification-icon {
            color: #e74c3c;
        }
        .notification-text {
            flex: 1;
        }
        .notification-title {
            font-weight: 500;
            margin-bottom: 4px;
            color: #2c3e50;
        }
        .notification-message {
            color: #6c757d;
            font-size: 0.9rem;
        }
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            .scanner-container {
                aspect-ratio: 3 / 4;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>QR Code Scanner</h1>
            <p>Scan your ticket QR code to verify</p>
        </div>

        <div class="scanner-card">
            <div class="menu">
                <select id="cameraSelect" class="camera-select">
                    <option value="">Select Camera</option>
                </select>
                <button id="scanButton" class="scan-button">
                    <i class="fas fa-qrcode"></i>
                    Start Scanning
                </button>
            </div>
            <div id="reader" class="scanner-container"></div>
        </div>
    </div>

    <div class="ticket-details">
        <div class="ticket-details-content">
            <h3>Ticket Details</h3>
            <div id="ticketInfo"></div>
            <button class="continue-button" onclick="continueScanning()">
                <i class="fas fa-redo"></i>
                Continue Scanning
            </button>
        </div>
    </div>

    <div class="notification">
        <div class="notification-content">
            <i class="notification-icon fas"></i>
            <div class="notification-text">
                <div class="notification-title"></div>
                <div class="notification-message"></div>
            </div>
        </div>
    </div>

    <script>
        let html5QrcodeScanner = null;
        let audioContext = null;
        let audioBuffer = null;

        // Create audio context on user interaction
        document.addEventListener('click', initAudio, { once: true });

        function initAudio() {
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
            // Create and load the beep sound
            fetch('/audio/beep.mp3')
                .then(response => response.arrayBuffer())
                .then(arrayBuffer => audioContext.decodeAudioData(arrayBuffer))
                .then(decodedBuffer => {
                    audioBuffer = decodedBuffer;
                })
                .catch(error => console.error('Error loading audio:', error));
        }

        function playBeep() {
            if (audioContext && audioBuffer) {
                const source = audioContext.createBufferSource();
                source.buffer = audioBuffer;
                source.connect(audioContext.destination);
                source.start();
            }
        }

        function showNotification(type, title, message) {
            const notification = document.querySelector('.notification');
            const iconElement = notification.querySelector('.notification-icon');
            const titleElement = notification.querySelector('.notification-title');
            const messageElement = notification.querySelector('.notification-message');

            notification.className = 'notification ' + type;
            iconElement.className = 'notification-icon fas ' + 
                (type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle');
            titleElement.textContent = title;
            messageElement.textContent = message;

            notification.classList.add('show');
            setTimeout(() => notification.classList.remove('show'), 3000);
        }

        function showTicketDetails(data) {
            const details = document.querySelector('.ticket-details');
            const infoContainer = document.getElementById('ticketInfo');
            
            // Clear previous content
            infoContainer.innerHTML = '';
            
            // Add ticket information
            const ticketInfo = [
                { label: 'NIS', value: data.nis },
                { label: 'Name', value: data.nama_siswa },
                { label: 'Class', value: data.kelas },
                { label: 'Status', value: data.status }
            ];
            
            ticketInfo.forEach(info => {
                const p = document.createElement('p');
                p.innerHTML = `<strong>${info.label}:</strong> <span>${info.value}</span>`;
                infoContainer.appendChild(p);
            });
            
            // Show the details panel
            details.style.display = 'block';
            details.offsetHeight; // Force reflow
            details.classList.add('show');
        }

        function continueScanning() {
            const details = document.querySelector('.ticket-details');
            details.classList.remove('show');
            setTimeout(() => {
                details.style.display = 'none';
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.resume();
                }
            }, 300);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const cameraSelect = document.getElementById('cameraSelect');
            const scanButton = document.getElementById('scanButton');
            const reader = document.getElementById('reader');

            // Get available cameras
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    devices.forEach(device => {
                        const option = document.createElement('option');
                        option.value = device.id;
                        option.text = device.label || `Camera ${cameraSelect.options.length + 1}`;
                        cameraSelect.add(option);
                    });
                }
            }).catch(err => {
                console.error('Error getting cameras', err);
            });

            scanButton.addEventListener('click', function() {
                const selectedCamera = cameraSelect.value;
                if (!selectedCamera) {
                    showNotification('error', 'Error', 'Please select a camera first');
                    return;
                }

                if (html5QrcodeScanner) {
                    if (reader.style.display === 'none') {
                        reader.style.display = 'block';
                        html5QrcodeScanner.resume();
                        scanButton.innerHTML = '<i class="fas fa-stop"></i> Stop Scanning';
                    } else {
                        reader.style.display = 'none';
                        html5QrcodeScanner.pause();
                        scanButton.innerHTML = '<i class="fas fa-qrcode"></i> Start Scanning';
                    }
                    return;
                }

                reader.style.display = 'block';
                scanButton.innerHTML = '<i class="fas fa-stop"></i> Stop Scanning';

                html5QrcodeScanner = new Html5Qrcode("reader");
                html5QrcodeScanner.start(
                    selectedCamera,
                    {
                        fps: 10,
                        qrbox: { width: document.getElementById('reader').offsetWidth, height: document.getElementById('reader').offsetWidth }
                    },
                    (decodedText) => {
                        playBeep();
                        html5QrcodeScanner.pause();
                        
                        // Validate the scanned QR code
                        fetch('/scan/validate', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ qr_code: decodedText })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showNotification('success', 'Success', 'Ticket validated successfully');
                                showTicketDetails(data.ticket);
                            } else {
                                showNotification('error', 'Error', data.message || 'Invalid ticket');
                                html5QrcodeScanner.resume();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('error', 'Error', 'Failed to validate ticket');
                            html5QrcodeScanner.resume();
                        });
                    },
                    (error) => {
                        // console.error('QR Code scanning error:', error);
                    }
                ).catch((err) => {
                    console.error('Failed to start scanner:', err);
                    showNotification('error', 'Error', 'Failed to start scanner');
                    reader.style.display = 'none';
                    scanButton.innerHTML = '<i class="fas fa-qrcode"></i> Start Scanning';
                });
            });
        });
    </script>
</body>
</html> 