<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tiket;
use App\Models\Nis;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;


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
            'bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'order_id' => 'required|exists:tikets,order_id'
        ]);

        $bukti = $request->file('bukti');
        
        // Generate a unique filename with original extension
        $extension = $bukti->getClientOriginalExtension();
        $filename = 'bukti_' . $request->order_id . '_' . time() . '.' . $extension;
        
        // Store the file and get the full path
        $path = Storage::putFileAs('public/bukti', $bukti, $filename);

        // Get the full URL for the file
        $fileUrl = Storage::url($path);

        $tiket = Tiket::where('order_id', $request->order_id)->first();
        $tiket->bukti = $fileUrl; // Store the full URL
        $tiket->save();

        Session::forget('payment_data');

        return view('payment.success', compact('tiket'));
    }

    public function validateScan(Request $request)
    {
        try {
            $request->validate([
                'qr' => 'required|string|max:255'
            ]);

            $tiket = Tiket::where('order_id', $request->qr)->first();
            
            if (!$tiket) {
                return response()->json([
                    'valid' => false,
                    'message' => 'ticket_not_found'
                ]);
            }

            if ($tiket->status == 'completed' && $tiket->entry == 'no') {
                // Update entry status to yes
                $tiket->entry = 'yes';
                $tiket->save();

                return response()->json([
                    'valid' => true,
                    'message' => 'Check-In Berhasil',
                    'ticket' => [
                        'nis' => $tiket->nis,
                        'nama_siswa' => $tiket->nama,
                        'kelas' => $tiket->kelas,
                        'status' => $tiket->status,
                        'order_id' => $tiket->order_id,
                        'email' => $tiket->email,
                        'no_hp' => $tiket->phone
                    ]
                ]);
            } else if ($tiket->status == 'pending') {
                return response()->json([
                    'valid' => false,
                    'message' => 'ticket_pending'
                ]);
            } else if ($tiket->status == 'completed' && $tiket->entry == 'yes') {
                return response()->json([
                    'valid' => false,
                    'message' => 'ticket_already_used'
                ]);
            } else {
                return response()->json([
                    'valid' => false,
                    'message' => 'ticket_invalid'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Terjadi kesalahan saat memvalidasi tiket'
            ], 500);
        }
    }

    public function show($id, Request $request)
    {
        $nis = $request->query('nis');

        $tiket = Tiket::where('order_id', $id)->where('nis', $nis)->first();

        if (!$tiket) {
            return abort(404, 'Tiket tidak ditemukan.');
        }

        if ($tiket->status === 'pending') {
            return abort(404, 'Tiket belum dibayar.');
        }

        
        return view('eticket.show', compact('tiket'));
    }

}
