@extends('layouts.app2')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mt-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('admin') }}">List BirthdayCake</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Laporan</li>
                    <li class="breadcrumb-item"><a href="{{ url('admin-user') }}">Data User</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h3><i class="fa fa-history"></i> Laporan Pendapatan ADMIN</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pendapatan (Rp.)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach($pesanans as $pesanan)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $pesanan->tanggal }}</td>
                                <td>Rp. {{ number_format($pesanan->jumlah_harga) }}</td>
                                <td>
                                <a href="/hapus/{{ $pesanan->id }}">Hapus</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tbody>
                            <tr>
                                <td></td>
                                <td><strong>Total pendapatan</strong></td>
                                <td><strong>Rp. {{ number_format($pesanan->sum('jumlah_harga')) }}</strong></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
