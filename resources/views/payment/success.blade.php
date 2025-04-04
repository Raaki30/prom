<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-800 bg-opacity-50">
    <div class="fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-8 text-center">
                <div class="text-6xl text-green-500 mb-6">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h4 class="text-2xl font-semibold text-gray-800 mb-4">Bukti Pembayaran Terkirim!</h4>
                <p class="text-gray-600 mb-6">Terima kasih telah melakukan pembayaran. Kami akan memverifikasi pembayaran anda dalam waktu maksimal 2x24 jam dan detail tiket akan dikirim ke email anda.</p>
                <p class="text-gray-500 mb-6">Order ID: {{ $tiket->order_id }}</p>
                <div class="flex justify-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(function() {
            window.location.href = '/pesan'; // Redirect to home page after 3 seconds
        }, 3000);
    </script>
</body>
</html> 