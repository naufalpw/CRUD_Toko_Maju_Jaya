<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    protected $fillable = ['action', 'description', 'transaction_id'];

    // Relasi ke Transaksi (agar bisa ambil detail barang saat di view)
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
