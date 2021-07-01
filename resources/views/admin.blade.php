@extends('layouts.app2')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mt-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">kotapudak</li>
                    <li class="breadcrumb-item"><a href="{{ url('admin-laporan') }}">Laporan</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('admin-user') }}">Data User</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h3><i class="fa fa-history"></i>ADMIN TOKO</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach($barangs as $bg)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>
                                    <img src="{{ url('assets/baju') }}/{{ $bg->gambar }}" width="80" alt="...">
                                </td>
                                <td>{{ $bg->nama_barang }}</td>
                                <td>{{ $bg->stok }} pcs</td>
                                <td>Rp. {{ number_format($bg->harga) }}</td>
                                <td>
                                <a href="/delete/{{ $bg->id }}">Hapus</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
