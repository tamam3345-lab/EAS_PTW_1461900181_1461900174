<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Pesanan;
use App\User;
use App\PesananDetail;
use App\Transaksi;
use Auth;
use Alert;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PesanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
    	$barang = Barang::where('id', $id)->first();

    	return view('pesan.index', compact('barang'));
    }

    public function pesan(Request $request, $id)
    {
        $barang = Barang::where('id', $id)->first();
    	$tanggal = Carbon::now();

        //validasi apakah melebihi stok
    	if($request->jumlah_pesan > $barang->stok)
    	{
    		return redirect('pesan/'.$id);
    	}

    	//cek validasi
    	$cek_pesanan = Pesanan::where('user_id', Auth::user()->id)->where('status',0)->first();
    	//simpan ke database pesanan
    	if(empty($cek_pesanan))
    	{
    		$pesanan = new Pesanan;
	    	$pesanan->user_id = Auth::user()->id;
	    	$pesanan->tanggal = $tanggal;
	    	$pesanan->status = 0;
	    	$pesanan->jumlah_harga = 0;
            $pesanan->kode = mt_rand(100, 999);
	    	$pesanan->save();
    	}
    	

    	//simpan ke database pesanan detail
    	$pesanan_baru = Pesanan::where('user_id', Auth::user()->id)->where('status',0)->first();

    	//cek pesanan detail
    	$cek_pesanan_detail = PesananDetail::where('barang_id', $barang->id)->where('pesanan_id', $pesanan_baru->id)->first();
    	if(empty($cek_pesanan_detail))
    	{
    		$pesanan_detail = new PesananDetail;
	    	$pesanan_detail->barang_id = $barang->id;
	    	$pesanan_detail->pesanan_id = $pesanan_baru->id;
	    	$pesanan_detail->jumlah = $request->jumlah_pesan;
	    	$pesanan_detail->jumlah_harga = $barang->harga*$request->jumlah_pesan;
	    	$pesanan_detail->save();
    	}else 
    	{
    		$pesanan_detail = PesananDetail::where('barang_id', $barang->id)->where('pesanan_id', $pesanan_baru->id)->first();

    		$pesanan_detail->jumlah = $pesanan_detail->jumlah+$request->jumlah_pesan;

    		//harga sekarang
    		$harga_pesanan_detail_baru = $barang->harga*$request->jumlah_pesan;
	    	$pesanan_detail->jumlah_harga = $pesanan_detail->jumlah_harga+$harga_pesanan_detail_baru;
	    	$pesanan_detail->update();
    	}

    	//jumlah total
    	$pesanan = Pesanan::where('user_id', Auth::user()->id)->where('status',0)->first();
    	$pesanan->jumlah_harga = $pesanan->jumlah_harga+$barang->harga*$request->jumlah_pesan;
    	$pesanan->update();
    	
		alert()->success('Pesanan Berhasil Masuk Keranjang', 'Success');
    	return redirect('check-out');
    }

	public function check_out()
    {
        $pesanan = Pesanan::where('user_id', Auth::user()->id)->where('status',0)->first();
 		$pesanan_details = [];
        if(!empty($pesanan))
        {
            $pesanan_details = PesananDetail::where('pesanan_id', $pesanan->id)->get();

        }
        
        return view('pesan.check_out', compact('pesanan', 'pesanan_details'));
    }

	public function delete($id)
    {
        $pesanan_detail = PesananDetail::where('id', $id)->first();

        $pesanan = Pesanan::where('id', $pesanan_detail->pesanan_id)->first();
        $pesanan->jumlah_harga = $pesanan->jumlah_harga-$pesanan_detail->jumlah_harga;
        $pesanan->update();


        $pesanan_detail->delete();

		
		alert()->error('Pesanan Sukses Dihapus', 'Hapus');
        return redirect('check-out');
    }

	public function konfirmasi()
    {
		$user = User::where('id', Auth::user()->id)->first();

        if(empty($user->alamat))
        {
            Alert::error('Identitasi Harap dilengkapi', 'Error');
            return redirect('profile');
        }

        if(empty($user->nohp))
        {
            Alert::error('Identitasi Harap dilengkapi', 'Error');
            return redirect('profile');
        }

        $pesanan = Pesanan::where('user_id', Auth::user()->id)->where('status',0)->first();
        $pesanan_id = $pesanan->id;
        $pesanan->status = 1;
        $pesanan->update();

        $pesanan_details = PesananDetail::where('pesanan_id', $pesanan_id)->get();
        foreach ($pesanan_details as $pesanan_detail) {
            $barang = Barang::where('id', $pesanan_detail->barang_id)->first();
            $barang->stok = $barang->stok-$pesanan_detail->jumlah;
            $barang->update();
        }
		alert()->success('Pesanan Sukses Check-Out Lanjutkan Proses Pembayaran', 'Success');
        return redirect('history/'. $pesanan_id);
	}

	public function transaksi(Request $request, $id)
    {
        $pesanan_details = PesananDetail::select('barang_id', 'pesanan_id', 'jumlah')->first();

        $transaksi = new Transaksi();
        $pesanan_user = Pesanan::where('user_id', Auth::user()->id)->where('status', 1)->first();

        // berdasarkan form input
        $this->validate($request, [ 
			'bukti_transaksi' => 'required|file|image|mimes:jpeg,png,jpg|max:2048'
		]);

        // upload file dari input
        $gambar = $request->bukti_transaksi;
        // nama folder tujuan
        $folder_simpan = 'bukti_transfer';
		$gambar->move($folder_simpan,$gambar->getClientOriginalName());
  
        $transaksi->user_id = Auth::user()->id;
        $transaksi->barang_id = $pesanan_details->barang_id;
        $transaksi->pesanan_id = $pesanan_details->pesanan_id;
        $transaksi->jumlah_barang = $pesanan_details->jumlah;
		$transaksi->alamat = Auth::user()->alamat;
        $transaksi->bukti_transaksi = $gambar->getClientOriginalName();
		$transaksi->created_at = date('Y-m-d H:i:s');
        $transaksi->updated_at = date('Y-m-d H:i:s');
        $transaksi->save();

        // status jumlah stok barang ketika sudah dibeli
        //$barang_stok = Barang::where('id', $pesanan_detail->barang_id)->first();
        //$barang_stok->stok -= $pesanan_detail->jumlah;
        //$barang_stok->update();   

        // status ketika sudah kirim bukti pembayaran
        $pesanan_status = Pesanan::where('user_id', Auth::user()->id)->where('status', 1)->first();
        $pesanan_status->status = 2; // status user membayar
        $pesanan_status->update();

        $pesanan = Pesanan::where('id', $id)->first();
        $pesanan_details = PesananDetail::where('pesanan_id', $pesanan->id)->get();
        
        alert()->success('Upload', 'Berhasil!');
        return view('history.detail', compact('pesanan','pesanan_details'));
    }
}