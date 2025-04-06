<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hi, {{ $tiket->nama }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Montserrat', sans-serif; }
    .script-font { font-family: 'Dancing Script', cursive; }
  </style>
</head>
<body class="bg-gradient-to-br from-indigo-900 to-purple-800 text-white min-h-screen flex items-center justify-center p-6">

  <div class="bg-white text-gray-800 rounded-3xl shadow-2xl overflow-hidden max-w-3xl w-full">
    

    <div class="p-8 space-y-6">

      <div class="text-center">
        <p class="text-lg text-gray-700">Dear, {{ $tiket->nama }}</p>
        <h1 class="script-font text-4xl text-purple-800 font-bold">You're Invited</h1>
        <h2 class="text-2xl mt-2">Enchanted Evening: Prom Night 2025</h2>
        <p class="mt-2 text-gray-600">A magical night to remember, under the stars ✨</p>
      </div>

      
   

      <div class="bg-purple-100 p-6 rounded-lg text-center">
        <h3 class="text-lg font-semibold text-purple-800 mb-4">Your Entry Ticket</h3>
        @if($tiket->entry !== 'yes')
          <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $tiket->order_id }}" alt="QR Code" class="mx-auto w-40 h-40">
          <p class="text-sm text-purple-800 mt-2">Show this QR code at the entrance</p>
        @else
          <div class="relative inline-block">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=000000" class="w-40 h-40 blur-sm">
            <div class="absolute inset-0 flex items-center justify-center">
              <span class="text-red-600 font-bold bg-white px-2 py-1 rounded">Used</span>
            </div>
          </div>
        @endif
      </div>

      <div class="bg-purple-50 p-6 rounded-lg text-sm">
        <h3 class="text-center text-lg font-semibold text-purple-800 mb-3">Your Info</h3>
        <div class="grid grid-cols-2 gap-2">
          <p><strong>Name:</strong> {{ $tiket->nama }}</p>
          <p><strong>ID:</strong> {{ $tiket->nis }}</p>
          <p><strong>Class:</strong> {{ $tiket->kelas }}</p>
          <p><strong>Ticket ID:</strong> {{ $tiket->order_id }}</p>
        </div>
      </div>

      <div class="text-center text-sm text-gray-600 mt-6">
        <p>✨ Let’s make this night magical together ✨</p>
        <p class="mt-2">Questions? Whatsapp us at <a href="https://wa.me/6281234567890" class="underline text-purple-800">+6281234567890</a></p>
      </div>

    </div>
  </div>
</body>
</html>
