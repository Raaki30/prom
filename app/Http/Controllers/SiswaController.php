<?php

namespace App\Http\Controllers;

use App\Models\Nis;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (strlen($query) < 3) {
            return response()->json([]);
        }

        $siswa = Nis::where('nis', 'like', "%{$query}%")
            ->orWhere('nama_siswa', 'like', "%{$query}%")
            ->select('nis', 'nama_siswa', 'kelas')
            ->get();

        return response()->json($siswa);
    }
} 