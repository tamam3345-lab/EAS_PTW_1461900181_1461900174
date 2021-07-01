<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Pesanan;
use App\User;
use App\PesananDetail;
use Auth;
use Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function laporan()
    {
    	$pesanans = Pesanan::paginate();
        return view('laporan', compact('pesanans'));
    }
    public function user()
    {
    	$users = User::paginate();
        return view('user', compact('users'));
    }
    public function admin()
    {
        $barangs = Barang::paginate();
        return view('admin', compact('barangs'));
    }
    public function hapus($id)
    {
        DB::table('pesanans')
        ->where('id', $id)->delete();
        return redirect('admin-laporan');
    }
    public function delete($id)
    {
        DB::table('barangs')
        ->where('id', $id)->delete();
        return redirect('admin');
    }
    public function destroy($id)
    {
        DB::table('users')
        ->where('id', $id)->delete();
        return redirect('admin-user');
    }

}
