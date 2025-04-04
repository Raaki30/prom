<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petunjuk Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .whatsapp-section {
            text-align: center;
            margin-top: 20px;
        }
        .whatsapp-section h3 {
            color: #333;
            font-size: 1rem;
            margin-bottom: 10px;
        }
        .whatsapp-section a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 8px;
            background-color: #25D366;
            color: white;
            text-decoration: none;
            font-size: 0.875rem;
            transition: background-color 0.2s;
        }
        .whatsapp-section a:hover {
            background-color: #128C7E;
            color: white;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h3 class="mb-3">Petunjuk Pembayaran</h3>
                            <div class="alert alert-info">
                                <h5>Order ID: {{ $tiket->order_id }}</h5>
                                <p class="mb-0">Total Harga: Rp {{ number_format($tiket->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="payment-instructions mb-4">
                            <h5>Silahkan ikuti langkah-langkah berikut:</h5>
                            <ol>
                                <li>Transfer ke nomor rekening dibawah ini</li>
                                
                                @if($tiket->metodebayar === 'bca')
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6>Bank BCA Virtual Account:</h6>
                                        <p class="mb-1">Bank: BCA</p>
                                        <p class="mb-1">Nomor Rekening: 1234567890</p>
                                        <p class="mb-0">Atas Nama: Your Company Name</p>
                                        <div class="mt-3">
                                            <h6>How to pay via BCA Virtual Account:</h6>
                                            <ol class="text-start">
                                                <li>Login to your BCA Mobile Banking</li>
                                                <li>Select "Transfer"</li>
                                                <li>Choose "BCA Virtual Account"</li>
                                                <li>Enter the Virtual Account number</li>
                                                <li>Confirm the amount and complete the transfer</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                @elseif($tiket->metodebayar === 'mandiri')
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6>Bank Mandiri Virtual Account:</h6>
                                        <p class="mb-1">Bank: Mandiri</p>
                                        <p class="mb-1">Nomor Rekening: 0987654321</p>
                                        <p class="mb-0">Atas Nama: Your Company Name</p>
                                        <div class="mt-3">
                                            <h6>How to pay via Mandiri Virtual Account:</h6>
                                            <ol class="text-start">
                                                <li>Login to your Mandiri Mobile Banking</li>
                                                <li>Select "Transfer"</li>
                                                <li>Choose "Virtual Account"</li>
                                                <li>Enter the Virtual Account number</li>
                                                <li>Confirm the amount and complete the transfer</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <li>Simpan bukti pembayaran anda</li>
                                <li>Upload bukti pembayaran ke form dibawah</li>
                            </ol>
                        </div>

                        <form action="/payment/upload" method="POST" enctype="multipart/form-data" class="mb-4">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $tiket->order_id }}">
                            <div class="mb-3">
                                <label for="bukti" class="form-label">Upload Bukti Bayar</label>
                                <input type="file" class="form-control" id="bukti" name="bukti" accept="image/*" required>
                                <div class="form-text">Format yang diterima: JPG, JPEG, PNG (Max 2MB)</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Upload Bukti</button>
                        </form>

                        <div class="whatsapp-section">
                            <h3>Butuh Bantuan?</h3>
                            <a href="https://wa.me/6281234567890" target="_blank">
                                <i class="fab fa-whatsapp"></i>
                                <span>Hubungi Kami di WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 