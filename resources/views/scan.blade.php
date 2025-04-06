<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>QR Code Scanner</title>
  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-start p-4">

  <div id="notification" class="fixed bottom-4 inset-x-0 flex justify-center z-50 translate-y-full transition-all duration-300">
    <div class="bg-white rounded-lg shadow-lg p-4 flex items-center gap-3 max-w-md w-full">
      <i class="notification-icon text-2xl flex-shrink-0"></i>
      <div>
        <h4 class="notification-title font-semibold text-gray-800"></h4>
        <p class="notification-message text-sm text-gray-600"></p>
      </div>
    </div>
  </div>

  <h1 class="text-2xl font-bold mb-4 text-center">QR Code Ticket Scanner</h1>

  <div class="flex gap-3 mb-6">
    <button id="startScan" class="px-5 py-2 bg-blue-600 text-white rounded-full shadow">Mulai Scan</button>
    <button id="stopScan" class="px-5 py-2 bg-red-500 text-white rounded-full shadow">Berhenti</button>
  </div>

  <div id="reader" class="w-full max-w-xs sm:max-w-md mx-auto rounded-xl overflow-hidden shadow-md"></div>

  <div id="ticketDetails" class="hidden bg-white p-4 rounded-2xl shadow-lg mt-6 w-full max-w-md">
    <h3 class="text-lg font-semibold text-green-600 mb-2">Check-In Berhasil</h3>
    <div class="space-y-4"></div>
    <button id="closeDetails" class="mt-4 w-full py-2 bg-blue-600 text-white rounded-full">Lanjut Scan</button>
  </div>

  <script>
    let isScanning = false;
    let html5QrCode;

    

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

      const ticketInfo = `
        <div class="flex justify-between py-2 border-b border-gray-100">
          <strong class="text-gray-700">NIS</strong>
          <span class="text-gray-600">${ticket.nis}</span>
        </div>
        <div class="flex justify-between py-2 border-b border-gray-100">
          <strong class="text-gray-700">Nama</strong>
          <span class="text-gray-600">${ticket.nama_siswa}</span>
        </div>
        <div class="flex justify-between py-2 border-b border-gray-100">
          <strong class="text-gray-700">Kelas</strong>
          <span class="text-gray-600">${ticket.kelas}</span>
        </div>
        <div class="flex justify-between py-2 border-b border-gray-100">
          <strong class="text-gray-700">Email</strong>
          <span class="text-gray-600">${ticket.email}</span>
        </div>
        <div class="flex justify-between py-2">
          <strong class="text-gray-700">No. HP</strong>
          <span class="text-gray-600">${ticket.no_hp}</span>
        </div>
      `;

      content.innerHTML = ticketInfo;
      ticketDetails.classList.remove('hidden');
    }

    function hideTicketDetails() {
      document.getElementById('ticketDetails').classList.add('hidden');
      startScanner();
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
      .then(response => response.json())
      .then(data => {
        if (data.valid) {
          showNotification('success', 'Success', data.message);
          showTicketDetails(data.ticket);
          stopScanner();
        } else {
          let errorMessage = 'QR tidak valid';
          if (data.message === 'ticket_not_found') errorMessage = 'Tiket tidak ditemukan';
          else if (data.message === 'ticket_pending') errorMessage = 'Tiket belum diverifikasi';
          else if (data.message === 'ticket_already_used') errorMessage = 'Tiket sudah digunakan';

          showNotification('error', 'Error', errorMessage);
          setTimeout(() => startScanner(), 3000);
        }
      })
      .catch(() => {
        showNotification('error', 'Error', 'Terjadi kesalahan');
        setTimeout(() => startScanner(), 3000);
      });
    }

    function startScanner() {
      const scannerDiv = document.getElementById('reader');
      html5QrCode = new Html5Qrcode("reader");
      isScanning = true;

      Html5Qrcode.getCameras().then(devices => {
        if (devices && devices.length) {
          html5QrCode.start(
            { facingMode: "environment" },
            {
              fps: 10,
              qrbox: { width: 250, height: 250 }
            },
            onScanSuccess
          );
        }
      }).catch(err => {
        console.error(err);
        showNotification('error', 'Error', 'Tidak bisa mengakses kamera');
      });
    }

    function stopScanner() {
      if (html5QrCode) {
        html5QrCode.stop().then(() => {
          html5QrCode.clear();
        }).catch(err => console.error(err));
      }
    }

    document.addEventListener('DOMContentLoaded', () => {
      document.getElementById('startScan').addEventListener('click', startScanner);
      document.getElementById('stopScan').addEventListener('click', stopScanner);
      document.getElementById('closeDetails').addEventListener('click', hideTicketDetails);
    });
  </script>
</body>
</html>