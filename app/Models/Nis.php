<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nis extends Model
{
    protected $table = 'nis';
    
    protected $fillable = [
        'nis',
        'nama_siswa',
        'kelas'
    ];
} 