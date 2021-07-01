<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function barang()
    {
        return $this->belongsTo("App\Barang",'barang_id','id');
    }

    public function pesanan()
    {
        return $this->belongsTo("App\Pesanan",'pesanan_id','id');
    }

    public function pesanan_detail()
    {
        return $this->belongsTo('App\PesananDetail','pesanan_detail', 'id');
    }
}