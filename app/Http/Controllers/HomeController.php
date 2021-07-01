<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Barang;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->email !== 'admin@gmail.com'){
            $barangs = Barang::paginate(4);
            return view('home', compact('barangs'));
        }else{
            $barangs = Barang::paginate();
            return view('admin', compact('barangs'));
        }
    }

}
