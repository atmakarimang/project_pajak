@section('title','Kasi')
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @include('layouts.dashboard.styleSheet')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    @include('layouts.dashboard.navbar')
    @include('layouts.dashboard.sidebar')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Form Kasi</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Kasi</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
    <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Pelaksana Bidang</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="no_agenda">No Agenda</label>
                                        <input type="text" id="no_agenda" name="no_agenda" class="form-control" value ="{{$dataPB->no_agenda}}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label>Tanggal Agenda</label>
                                    <div class="input-group date" id="tgl_agenda" data-target-input="nearest">
                                        @php
                                            $tgl = date('d/m/Y', strtotime($dataPB->tgl_agenda));
                                        @endphp
                                        <input type="text" name="tgl_agenda" class="form-control datetimepicker-input" data-target="#tgl_agenda" value="{{$tgl}}" readonly/>
                                        <div class="input-group-append" data-target="#tgl_agenda">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="no_naskahdinas">No Naskah Dinas</label>
                                        <input type="text" id="no_naskahdinas" name="no_naskahdinas" class="form-control" value ="{{$dataPB->no_naskah_dinas}}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tanggal Naskah Dinas</label>
                                        <div class="input-group date" id="tgl_agenda" data-target-input="nearest">
                                            @php
                                                $tgl = date('d/m/Y', strtotime($dataPB->tgl_naskah_dinas));
                                            @endphp
                                            <input type="text" name="tgl_naskahdinas" class="form-control datetimepicker-input" data-target="#tgl_naskahdinas" value="{{$tgl}}" readonly/>
                                            <div class="input-group-append" data-target="#tgl_naskahdinas">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="asal_permohonan">Asal Permohonan</label>
                                        <select class="form-control select2" name="asal_permohonan" disabled>
                                            <option selected disabled>Pilih Asal Permohonan</option>
                                            @foreach($dtAsalPermohonan as $dt)
                                                <option value="{{$dt->pemohon}}" {{ ($dt->pemohon==$dataPB->pemohon) ? "selected" : "" }}>{{$dt->id}} {{$dt->pemohon}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="no_arusdok">No Lembar Pengawasan Arus Dokumen</label>
                                        <input type="text" id="no_arusdok" name="no_arusdok" class="form-control" value="{{$dataPB->no_lbr_pengawas_dok}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tanggal diterima KPP</label>
                                        <div class="input-group date" id="tgl_in_kpp" data-target-input="nearest">
                                            @php
                                                $tgl = date('d/m/Y', strtotime($dataPB->tgl_diterima_kpp));
                                            @endphp
                                            <input type="text" name="tgl_in_kpp" class="form-control datetimepicker-input" data-target="#tgl_in_kpp" value="{{$tgl}}" readonly/>
                                            <div class="input-group-append" data-target="#tgl_in_kpp">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tanggal diterima Kanwil</label>
                                        <div class="input-group date" id="tgl_in_kanwil" data-target-input="nearest">
                                            @php
                                                $tgl = date('d/m/Y', strtotime($dataPB->tgl_diterima_kanwil));
                                            @endphp
                                            <input type="text" name="tgl_in_kanwil" class="form-control datetimepicker-input" data-target="#tgl_in_kanwil" value="{{$tgl}}" readonly/>
                                            <div class="input-group-append" data-target="#tgl_in_kanwil">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="no_npwp">No Pokok Wajib Pajak</label>
                                        <input type="text" id="no_npwp" name="no_npwp" class="form-control" placeholder="XX.XXX.XXX.X-XXX.XXX" value="{{$dataPB->npwp}}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="nama_npwp">Nama Wajib Pajak</label>
                                        <input type="text" id="nama_npwp" name="nama_npwp" class="form-control" value="{{$dataPB->nama_wajib_pajak}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="jenis_permohonan">Jenis Permohonan</label>
                                        <select class="form-control select2" name="jenis_permohonan" disabled>
                                            <option selected disabled>Pilih Jenis Permohonan</option>
                                            @foreach($dtJnsPermohonan as $dt)
                                                <option value="{{$dt->jenis_permohonan}}" {{ ($dt->jenis_permohonan==$dataPB->jenis_permohonan) ? "selected" : "" }}>{{$dt->jenis_permohonan}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="jenis_pajak">Jenis Pajak</label>
                                        <select class="form-control select2" name="jenis_pajak" disabled>
                                            <option selected disabled>Pilih Jenis Pajak</option>
                                            @foreach($dtPajak as $dt)
                                                <option value="{{$dt->pajak}}" {{ ($dt->pajak==$dataPB->pajak) ? "selected" : "" }}>{{$dt->pajak}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="jenis_ketetapan">Jenis Ketetapan</label>
                                        <select class="form-control select2" name="jenis_ketetapan" disabled>
                                            <option selected disabled>Pilih Jenis Ketetapan</option>
                                            @foreach($dtKetetapan as $dt)
                                                <option value="{{$dt->jenis_ketetapan}}" {{ ($dt->jenis_ketetapan==$dataPB->jenis_ketetapan) ? "selected" : "" }}>{{$dt->jenis_ketetapan}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="no_ketetapan">No Ketetapan</label>
                                        <input type="text" id="no_ketetapan" name="no_ketetapan" class="form-control" placeholder="XXXXX/XXX/XX/XXX/XX" value="{{$dataPB->no_ketetapan}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tanggal Ketetapan</label>
                                        <div class="input-group date" id="tgl_ketetapan" data-target-input="nearest">
                                            <input type="text" name="tgl_ketetapan" class="form-control datetimepicker-input" data-target="#tgl_ketetapan" value="{{$dataPB->tgl_ketetapan}}" readonly/>
                                            <div class="input-group-append" data-target="#tgl_ketetapan">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="jenis_ketetapan">Masa Pajak</label>
                                        <select class="form-control select2" name="jenis_ketetapan" disabled>
                                            <option selected disabled>Pilih Masa Pajak</option>
                                            <option value="Januari" {{ ($dataPB->masa_pajak == 'Januari') ? "selected" : "" }}>Januari</option>
                                            <option value="Februari" {{ ($dataPB->masa_pajak == 'Februari') ? "selected" : "" }}>Februari</option>
                                            <option value="Maret" {{ ($dataPB->masa_pajak == 'Maret') ? "selected" : "" }}>Maret</option>
                                            <option value="April" {{ ($dataPB->masa_pajak == 'April') ? "selected" : "" }}>April</option>
                                            <option value="Mei" {{ ($dataPB->masa_pajak == 'Mei') ? "selected" : "" }}>Mei</option>
                                            <option value="Juni" {{ ($dataPB->masa_pajak == 'Juni') ? "selected" : "" }}>Juni</option>
                                            <option value="Juli" {{ ($dataPB->masa_pajak == 'Juli') ? "selected" : "" }}>Juli</option>
                                            <option value="Agustus" {{ ($dataPB->masa_pajak == 'Agustus') ? "selected" : "" }}>Agustus</option>
                                            <option value="September" {{ ($dataPB->masa_pajak == 'September') ? "selected" : "" }}>September</option>
                                            <option value="Oktober" {{ ($dataPB->masa_pajak == 'Oktober') ? "selected" : "" }}>Oktober</option>
                                            <option value="November" {{ ($dataPB->masa_pajak == 'November') ? "selected" : "" }}>November</option>
                                            <option value="Desember" {{ ($dataPB->masa_pajak == 'Desember') ? "selected" : "" }}>Desember</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                <div class="form-group">
                                    <label for="tahun_pajak">Tahun Pajak</label>
                                    <input type="text" id="tahun_pajak" name="tahun_pajak" class="form-control" value="{{$dataPB->tahun_pajak}}" readonly>
                                </div>
                                </div>
                                <div class="col-6">
                                <div class="form-group">
                                    <label for="kat_permohonan">Kategori Permohonan</label>
                                    <select class="form-control select2" name="kat_permohonan" disabled>
                                        <option selected disabled>Pilih Kategori Permohonan</option>
                                        @foreach($dtKatPermohonan as $dt)
                                            <option value="{{$dt->kat_permohonan}}" {{ ($dataPB->kat_permohonan == $dt->kat_permohonan) ? "selected" : "" }}>{{$dt->kat_permohonan}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                <div class="form-group">
                                    <label for="no_srt_per">No Surat Permohonan</label>
                                    <input type="text" id="no_srt_per" name="no_srt_per" class="form-control" value="{{$dataPB->no_srt_permohonan}}" readonly>
                                </div>
                                </div>
                                <div class="col-6">
                                <div class="form-group">
                                    <label>Tanggal Surat Permohonan</label>
                                    <div class="input-group date" id="tgl_srtper" data-target-input="nearest">
                                        @php
                                            $tgl = date('d/m/Y', strtotime($dataPB->tgl_srt_permohonan));
                                        @endphp
                                        <input type="text" name="tgl_srtper" class="form-control datetimepicker-input" data-target="#tgl_srtper" value="{{$tgl}}" readonly/>
                                        <div class="input-group-append" data-target="#tgl_srtper">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <label for="seksi_konseptor">Seksi Konseptor</label>
                                    <div class="form-group row">
                                        @php
                                           $ex = explode(",",$dataPB->seksi_konseptor);
                                        @endphp
                                        @foreach($dtSeksiKonsep as $dt)
                                            &nbsp;&nbsp;&nbsp;
                                            <div class="form-check">
                                                <input class="form-check-input" name="seksi_konseptor[]" value="{{$dt->seksi_konseptor}}" type="checkbox" {{ (in_array($dt->seksi_konseptor, $ex)) ? "checked" : "" }} disabled>
                                                <label class="form-check-label">{{$dt->seksi_konseptor}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control select2" name="status" disabled>
                                            <@foreach($dtStatus as $dt)
                                                <option value="{{$dt->status}}" {{ ($dataPB->status == $dt->status) ? "selected" : " " }}>{{$dt->status}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                <div class="form-group">
                                    <label for="progress">Progress</label>
                                    <select class="form-control select2" name="progress" disabled>
                                        @foreach($dtProgress as $dt)
                                            <option value="{{$dt->progress}}" {{ ($dataPB->progress == $dt->progress) ? "selected" : " " }}>{{$dt->progress}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                                <div class="col-6">
                                <div class="form-group">
                                    <label for="jumlah_bayar">Jumlah Pembayaran a/ PMK-29 & PMK-91</label>
                                    <input type="text" id="jumlah_bayar" name="jumlah_bayar" class="form-control" value="{{$dataPB->jumlah_byr_pmk}}" readonly>
                                </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tanggal Pembayaran</label>
                                        <div class="input-group date" id="tgl_bayar" data-target-input="nearest">
                                            @php
                                                $tgl = date('d/m/Y', strtotime($dataPB->tgl_byr_pmk));
                                            @endphp
                                            <input type="text" name="tgl_bayar" class="form-control datetimepicker-input" data-target="#tgl_bayar" value="{{$tgl}}" readonly/>
                                            <div class="input-group-append" data-target="#tgl_bayar">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <form id="form-kasi" data-toggle="validator" action="{{route('kasi.store')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">General</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="no_agenda" name="no_agenda" class="form-control" value ="{{$dataPB->no_agenda}}">
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="kepala_seksi">Kepala Seksi</label>
                                        <select class="form-control select2" name="kepala_seksi" required>
                                            <option selected disabled>Pilih Kepala Seksi</option>
                                            @foreach($dtKepsek as $dt)
                                                <option value="{{$dt->nama_anggota}}" {{ ($dt->nama_anggota==$dataPB->kepala_seksi) ? "selected" : "" }}>{{$dt->nama_anggota}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                <div class="form-group">
                                    <label for="pk_konseptor">PK Konseptor</label>
                                    <select class="form-control select2" name="pk_konseptor" required>
                                        <option selected disabled>Pilih PK Konseptor</option>
                                        @foreach($dtKepsek as $dt)
                                            <option value="{{$dt->nama_anggota}}" {{ ($dt->nama_anggota==$dataPB->pk_konseptor) ? "selected" : "" }}>{{$dt->nama_anggota}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="no_prodhukum">No Produk Hukum</label>
                                        @if($dataPB->no_produk_hukum)
                                            <input type="text" id="no_prodhukum" name="no_prodhukum" class="form-control" value="{{$dataPB->no_produk_hukum}}" required>
                                        @else
                                            <input type="text" id="no_prodhukum" name="no_prodhukum" class="form-control" required>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tanggal Produk Hukum</label>
                                        <div class="input-group date" id="tgl_srtper" data-target-input="nearest">
                                            <input type="text" name="tgl_prodhukum" class="form-control datetimepicker-input" data-target="#tgl_prodhukum" value="{{$dataPB->tgl_produk_hukum}}" required/>
                                            <div class="input-group-append" data-target="#tgl_prodhukum" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="jumlah_bayar_awl">Jumlah yang Masih Harus Dibayar Semula</label>
                                            @if($dataPB->jml_byr_semula)
                                                <input type="text" id="jumlah_bayar_awl" name="jumlah_bayar_awl" class="form-control" value="{{$dataPB->jml_byr_semula}}" required>
                                            @else
                                                <input type="text" id="jumlah_bayar_awl" name="jumlah_bayar_awl" class="form-control" required>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="jumlah_tbh">Tambah / (Kurang)</label>
                                        @if($dataPB->tambah_kurang)
                                            <input type="text" id="jumlah_tbh" name="jumlah_tbh" class="form-control" value="{{$dataPB->tambah_kurang}}" required>
                                        @else
                                            <input type="text" id="jumlah_tbh" name="jumlah_tbh" class="form-control" required>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="jumlah_bayarprod">Jumlah yang Masih Harus Dibayar sesuai Produk</label>
                                            @if($dataPB->jml_byr_produk)
                                                <input type="text" id="jumlah_bayarprod" name="jumlah_bayarprod" class="form-control" value="{{$dataPB->jml_byr_produk}}" required>
                                            @else
                                                <input type="text" id="jumlah_bayarprod" name="jumlah_bayarprod" class="form-control" required>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="jumlah_tbh">Hasil Keputusan</label>
                                        <select class="form-control select2" name="hsl_kep" required>
                                            <option selected disabled>Pilih Hasil Keputusan</option>
                                            @foreach($dtKeputusan as $dt)
                                                <option value="{{$dt->keputusan}}" {{ ($dt->keputusan==$dataPB->hasil_keputusan) ? "selected" : "" }}>{{$dt->keputusan}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>No Bukti Terima Kiriman Resi (Pos)</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" accept=".jpeg,.jpg,.png,.pdf" class="custom-file-input" id="noresi" name="noresi" >
                                                <label class="custom-file-label" for="noresi">Choose file</label>
                                            </div>
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="">Upload</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tanggal Bukti Terima Kiriman Resi (Pos)</label>
                                        <div class="input-group date" id="tgl_resi" data-target-input="nearest">
                                            <!-- datetimepicker-input -->
                                            <input type="text" name="tgl_resi" class="form-control " data-target="#tgl_resi" value="{{$dataPB->tgl_resi}}" required/>
                                            <div class="input-group-append" data-target="#tgl_resi" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>No Surat Pengantar</label>
                                        @if($dataPB->no_srt_pengantar)
                                            <input type="text" id="no_srtpengantar" name="no_srtpengantar" class="form-control" value="{{$dataPB->no_srt_pengantar}}" required>
                                        @else
                                            <input type="text" id="no_srtpengantar" name="no_srtpengantar" class="form-control" required>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tanggal Surat Pengantar</label>
                                        <div class="input-group date" id="tgl_srtpengantar" data-target-input="nearest">
                                            <!-- datetimepicker-input -->
                                            <input type="text" name="tgl_srtpengantar" class="form-control " data-target="#tgl_srtpengantar" value="{{$dataPB->tgl_srt_pengantar}}" required/>
                                            <div class="input-group-append" data-target="#tgl_srtpengantar" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <div class="col-12 row">
                        <div class="col-3">
                            <div class="card-footer">
                                @if($dataPB->no_produk_hukum)
                                    <button type="submit" name="mode" class="btn btn-success float-left" value="edit">Update</button>
                                @else
                                    <button type="submit" name="mode" class="btn btn-success float-left" value="add">Add</button>
                                    <button type="submit" class="btn btn-default float-right">Cancel</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    </form>
                <!-- /.card -->
                </div>
            </div>
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    @include('layouts.dashboard.footer')

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->
  @include('layouts.dashboard.javascript')
</body>

</html>
@section("script")
<!-- Select2 -->
<script src="../../plugins/select2/js/select2.full.min.js"></script>
<script>
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
})
<script>
@endsection