<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usaha extends Model
{
    protected $table = 'usaha';

    protected $fillable = [
        'nama_usaha',
        'alamat',
        'nama_provinsi',
        'nama_kabupaten',
        'nama_kecamatan',
        'nama_desa',
        'status_perusahaan',
        'skala_usaha',
    ];
}
