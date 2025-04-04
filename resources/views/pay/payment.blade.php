

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .order-summary {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .student-info {
            background-color: #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
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
                        <h3 class="text-center mb-4">Payment Details</h3>

                        <div class="student-info mb-4">
                            <h5 class="mb-3">Informasi Siswa</h5>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1">NIS:</p>
                                    <p class="mb-1">Nama:</p>
                                    <p class="mb-1">Kelas:</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1">{{ $nis }}</p>
                                    <p class="mb-1">{{ $nama_siswa }}</p>
                                    <p class="mb-1">{{ $kelas }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="order-summary mb-4">
                            <h5 class="mb-3">Order Summary</h5>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1">Tiket Siswa:</p>
                                    @if($bawa_tamu)
                                    <p class="mb-1">Tiket Tamu:</p>
                                    @endif
                                    <p class="mb-1">Tax (11%):</p>
                                    <hr>
                                    <p class="mb-0 fw-bold">Total Amount:</p>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="mb-1">1 x Rp 405.000</p>
                                    @if($bawa_tamu)
                                    <p class="mb-1">1 x Rp 405.000</p>
                                    @endif
                                    <p class="mb-1">Rp {{ number_format($harga * 0.11, 0, ',', '.') }}</p>
                                    <hr>
                                    <p class="mb-0 fw-bold">Rp {{ number_format($harga * 1.11, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <form action="/payment/process" method="POST" class="form-container">
                            @csrf
                            <input type="hidden" name="order_id" value="ORDER-{{ Str::random(8) }}">
                            <input type="hidden" name="nis" value="{{ $nis }}">
                            <input type="hidden" name="nama_siswa" value="{{ $nama_siswa }}">
                            <input type="hidden" name="kelas" value="{{ $kelas }}">
                            <input type="hidden" name="bawa_tamu" value="{{ $bawa_tamu }}">
                            <input type="hidden" name="harga" value="{{ $harga }}">
                            <input type="hidden" name="grandtotal" value="{{ $harga * 1.11 }}">

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Method</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metodebayar" id="bca" value="bca" checked>
                                    <label class="form-check-label" for="bca">
                                        BCA Virtual Account
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metodebayar" id="mandiri" value="mandiri">
                                    <label class="form-check-label" for="mandiri">
                                        Mandiri Virtual Account
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-credit-card me-2"></i>Proceed to Payment
                                </button>
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Back
                                </a>
                            </div>
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