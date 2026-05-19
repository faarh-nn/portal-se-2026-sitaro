<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsahaGmaps extends Model
{
    protected $table = 'usaha_gmaps';

    protected $fillable = [
        'nama_usaha',
        'kategori',
        'alamat',
        'nomor_telepon',
        'website',
        'jam_operasional',
        'latitude',
        'longitude',
        'is_in_sbr',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_in_sbr' => 'boolean',
    ];
}
