<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tiket;
use App\Models\Nis;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;



class PayController extends Controller
{
    public function validateNis($nis)
    {
        $siswa = Nis::where('nis', $nis)->first();
        
        if ($siswa) {
            return response()->json([
                'valid' => true,
                'siswa' => $siswa
            ]);
        }
        
        return response()->json([
            'valid' => false,
            'message' => 'NIS tidak ditemukan'
        ]);
    }

    public function initPayment(Request $request)
    {
        $request->validate([
            'nis' => 'required|exists:nis,nis',
            'nama_siswa' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'bawa_tamu' => 'required|boolean',
            'harga' => 'required|numeric|min:0'
        ]);

        // Store the initial data in session
        Session::put('payment_data', $request->all());
        return view('payment.payment', $request->all());
    }

    

    public function processPayment(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'metodebayar' => 'required|in:bca,mandiri'
        ]);

        if (!session()->has('payment_data')) {
            return redirect()->route('pesan');
        }

        $paymentData = session('payment_data');
        $order_id = 'ORDER-' . Str::random(8);

        $tiket = Tiket::create([
            'order_id' => $order_id,
            'nis' => $paymentData['nis'],
            'nama' => $paymentData['nama_siswa'],
            'email' => $request->email,
            'phone' => $request->phone,
            'kelas' => $paymentData['kelas'],
            'jumlah_tiket' => $paymentData['bawa_tamu'] ? 2 : 1,
            'harga' => $paymentData['harga'] * 1.11, // Include tax
            'metodebayar' => $request->metodebayar,
            'status' => 'pending'
        ]);

       

        return view('payment.instructions', compact('tiket'));
    }

    

   
    
    public function uploadbukti(Request $request)
{
    $request->validate([
        'bukti' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        'order_id' => 'required|exists:tikets,order_id'
    ]);

    $file = $request->file('bukti');
    $tiket = Tiket::where('order_id', $request->order_id)->first();

    try {
        $newFileName = 'bukti_' . Str::slug($tiket->nama, '_') . '.' . $file->getClientOriginalExtension();
        $response = Http::asMultipart()
            ->attach(
            'image',
            fopen($file->getRealPath(), 'r'),
            $newFileName
            )
            ->post('https://api.imgbb.com/1/upload?key=' . env('IMGBB_API_KEY'));

        $result = $response->json();

        if (!$response->successful() || !isset($result['data']['url'])) {
            Log::error('Gagal upload gambar ke imgbb.', [
                'response_status' => $response->status(),
                'response_body' => $response->body(),
                'file_name' => $file->getClientOriginalName(),
                'order_id' => $request->order_id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal upload gambar ke imgbb.'
            ]);
        }

        $imageUrl = $result['data']['url'];
        $tiket->bukti = $imageUrl;
        $tiket->save();

        return response()->json([
            'success' => true,
            'image_url' => $imageUrl,
            'order_id' => $tiket->order_id,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
}

    



    

    

    

}
