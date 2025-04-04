<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .modal {
            display: block;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .success-icon {
            color: #28a745;
            font-size: 4rem;
        }
    </style>
</head>
<body>
    <div class="modal fade show" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <div class="success-icon mb-3">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h4 class="mb-3">Bukti Pembayaran Terkirim!</h4>
                    <p class="mb-4">Terima kasih telah melakukan pembayaran. Kami akan memverifikasi pembayaran anda dalam waktu maksimal 2x24 jam dan detail tiket akan dikirim ke email anda.</p>
                    <p class="text-muted mb-4">Order ID: {{ $tiket->order_id }}</p>
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Mengalihkan...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        setTimeout(function() {
            window.location.href = '/pesan'; // Redirect to home page after 3 seconds
        }, 3000);
    </script>
</body>
</html> 