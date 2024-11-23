<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan';
    protected $fillable = ['dinas', 'id_hasil', 'id_petugas', 'id_checklist', 'id_kendaraan', 'tanggal', 'kondisi', 'keterangan'];
}
