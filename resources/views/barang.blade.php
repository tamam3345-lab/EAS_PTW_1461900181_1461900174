@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="{{ url('/') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="col-md-8 mt-2">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Produk Kami</li>
              </ol>
            </nav>
        </div>
        <div class="col-md-8">
            <div class="card">

                {{-- BEST Product --}}
            <section class="best-product mt-3">
                <h2 style="text-align:center; font-weight: bold">KOTAPUDAK CLOTH</h2>
                <div class="row mt-3">
                    @foreach($barangs as $bg)
                    <div class="col-md-3 mb-3">
                        <div class="card shadow">
                            <div>
                                <img src="{{ url('assets/baju') }}/{{ $bg->gambar }}" class="img-fluid" max-height="100px">
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <h7><strong>{{ $bg->nama_barang }}</strong> </h7></br>
                                        <h7>Rp. {{ number_format($bg->harga) }}</h7>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <a href="{{ url('pesan') }}/{{ $bg->id }}" class="btn btn-dark btn-block"><i class="fas fa-eye"></i> Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </section>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
