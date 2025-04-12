<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Petunjuk Pembayaran</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
              <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h6 class="font-medium mb-2">Bank BCA Virtual Account:</h6>
                <p>Bank: BCA</p>
                <p>Nomor Rekening: 1234567890</p>
                <p class="mb-4">Atas Nama: Your Company Name</p>
              </div>
              @elseif($tiket->metodebayar === 'mandiri')
              <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h6 class="font-medium mb-2">Bank Mandiri Virtual Account:</h6>
                <p>Bank: Mandiri</p>
                <p>Nomor Rekening: 0987654321</p>
                <p class="mb-4">Atas Nama: Your Company Name</p>
              </div>
              @else
              <div class="text-red-600 font-semibold">
                Metode pembayaran tidak dikenali.
              </div>
              @endif
            </li>
            <li>Simpan bukti pembayaran Anda.</li>
            <li>Upload bukti pembayaran di bawah ini.</li>
          </ol>
        </div>

        <!-- Upload Form -->
        <form id="uploadForm" enctype="multipart/form-data" class="mb-8">
          @csrf
          <input type="hidden" name="order_id" value="{{ $tiket->order_id }}">
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Bayar</label>
            <div class="flex items-center justify-center w-full">
              <label for="bukti" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                  <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                  <p class="text-sm text-gray-500">Click to upload</p>
                  <p class="text-xs text-gray-500">JPG, JPEG, PNG (Max: 2MB)</p>
                  <span id="filename" class="mt-2 text-sm text-gray-600"></span>
                </div>
                <input id="bukti" name="bukti" type="file" class="hidden" accept="image/*" required>
              </label>
            </div>
          </div>

          <div id="previewContainer" class="hidden mb-4">
            <img id="previewImage" class="h-40 mx-auto object-contain mb-2" />
          </div>

          <button id="confirmButton" type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition">
            Konfirmasi
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  const fileInput = document.getElementById('bukti');
  const previewImage = document.getElementById('previewImage');
  const previewContainer = document.getElementById('previewContainer');
  const filenameDisplay = document.getElementById('filename');
  const confirmButton = document.getElementById('confirmButton');
  const form = document.getElementById('uploadForm');

  // Ensure the confirm button is disabled initially
  confirmButton.disabled = true;
  confirmButton.classList.add('bg-gray-400', 'cursor-not-allowed');
  confirmButton.classList.remove('bg-green-600', 'hover:bg-green-700');

  fileInput.addEventListener('change', function () {
    const file = fileInput.files[0];
    if (file && file.size <= 2 * 1024 * 1024) {
      const reader = new FileReader();
      reader.onload = function (e) {
        previewImage.src = e.target.result;
        previewContainer.classList.remove('hidden');
        confirmButton.disabled = false;
        confirmButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
        confirmButton.classList.add('bg-green-600', 'hover:bg-green-700');
        filenameDisplay.textContent = file.name;
      };
      reader.readAsDataURL(file);
    } else {
      Swal.fire({
        icon: 'error',
        title: 'File terlalu besar',
        text: 'Ukuran maksimum 2MB',
      });
      fileInput.value = '';
      previewContainer.classList.add('hidden');
      confirmButton.disabled = true;
      confirmButton.classList.add('bg-gray-400', 'cursor-not-allowed');
      confirmButton.classList.remove('bg-green-600', 'hover:bg-green-700');
      filenameDisplay.textContent = '';
    }
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(form);
    confirmButton.disabled = true;
    confirmButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengupload...';

    fetch("/payment/upload", {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": formData.get('_token')
      },
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          html: 'Bukti pembayaran berhasil diupload.<br><b>Redirecting...</b>',
          timer: 2000,
          timerProgressBar: true,
          showConfirmButton: false,
        }).then(() => {
          window.location.href = "/payment/afterpay?order_id=" + data.order_id;
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Gagal upload',
          text: data.message || 'Unknown error',
        });
      }
    })
    .catch(err => {
      console.error(err);
      Swal.fire({
        icon: 'error',
        title: 'Terjadi kesalahan',
        text: 'Gagal mengupload bukti bayar.',
      });
    })
    .finally(() => {
      confirmButton.disabled = false;
      confirmButton.textContent = "Konfirmasi";
    });
  });
</script>

@include('components.footer')
@include('components.whatsapp')
</body>
</html>
