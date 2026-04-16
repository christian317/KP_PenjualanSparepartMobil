<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pesanan;

class PengajuanRefund extends Model
{
    protected $table = 'pengajuan_refund';

    public $timestamps = false;

    protected $fillable = [
        'nomor_pesanan',
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'alasan_pembatalan',
        'status_refund',
        'bukti_transfer'
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pesanan::class, 'nomor_pesanan');
    }
}
