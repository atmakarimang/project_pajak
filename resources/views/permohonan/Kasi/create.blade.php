@section('title','Eksekutor')
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- daterange picker -->
  <script src="{{asset('assets/AdminLTE/plugins/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('assets/AdminLTE/plugins/jquery/jquery.js')}}"></script>

  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('assets/AdminLTE/plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
 
  <link href="{{asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css">
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
              <h1 class="m-0 text-dark">Eksekutor</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active">Eksekutor</li>
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
                    <form id="form-kasi" data-toggle="validator" action="{{route('kasi.store')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Forecaster</h3>
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
                                    <div class="input-group" id="tgl_agenda">
                                        <input type="text" name="tgl_agenda" class="form-control datetimepicker-input" value="{{$dataPB->tgl_agenda}}" readonly/>
                                        <div class="input-group-append">
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
                                        <div class="input-group" id="tgl_agenda">
                                            @php
                                                $tgl = date('d-m-Y', strtotime($dataPB->tgl_naskah_dinas));
                                            @endphp
                                            <input type="text" name="tgl_naskahdinas" class="form-control datetimepicker-input" value="{{$tgl}}" readonly/>
                                            <div class="input-group-append">
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
                                        <select class="form-control select2bs4" name="asal_permohonan" disabled>
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
                                        <div class="input-group" id="tgl_in_kpp">
                                            @php
                                                $tgl = date('d-m-Y', strtotime($dataPB->tgl_diterima_kpp));
                                            @endphp
                                            <input type="text" name="tgl_in_kpp" class="form-control datetimepicker-input" value="{{$tgl}}" readonly/>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tanggal diterima Kanwil</label>
                                        <div class="input-group" id="tgl_in_kanwil">
                                            @php
                                                $tgl = date('d-m-Y', strtotime($dataPB->tgl_diterima_kanwil));
                                            @endphp
                                            <input type="text" name="tgl_in_kanwil" class="form-control datetimepicker-input" value="{{$tgl}}" readonly/>
                                            <div class="input-group-append">
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
                                        <select class="form-control select2bs4" name="jenis_permohonan" disabled>
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
                                        <select class="form-control select2bs4" name="jenis_pajak" disabled>
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
                                        <select class="form-control select2bs4" name="jenis_ketetapan" disabled>
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
                                        <div class="input-group" id="tgl_ketetapan">
                                            @php
                                                $tgl = date('d-m-Y', strtotime($dataPB->tgl_ketetapan));
                                            @endphp
                                            <input type="text" name="tgl_ketetapan" class="form-control datetimepicker-input" value="{{$tgl}}" readonly/>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="jenis_ketetapan">Masa Pajak</label>
                                        <select class="form-control select2bs4" name="jenis_ketetapan" disabled>
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
                                    <select class="form-control select2bs4" name="kat_permohonan" disabled>
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
                                    <div class="input-group" id="tgl_srtper">
                                        @php
                                            $tgl = date('d-m-Y', strtotime($dataPB->tgl_srt_permohonan));
                                        @endphp
                                        <input type="text" name="tgl_srtper" class="form-control datetimepicker-input" value="{{$tgl}}" readonly/>
                                        <div class="input-group-append">
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
                                                <input class="form-check-input" name="seksi_konseptor[]" value="{{$dt->seksi_konseptor}}" type="checkbox" {{ (in_array($dt->seksi_konseptor, $ex)) ? "checked" : "" }}>
                                                <label class="form-check-label">{{$dt->seksi_konseptor}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status">
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
                                    <select class="form-control" name="progress">
                                        @foreach($dtProgress as $dt)
                                            <option value="{{$dt->progress}}" {{ ($dataPB->progress == $dt->progress) ? "selected" : " " }}>{{$dt->progress}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="jumlah_bayar">Jumlah Pembayaran a/ PMK-29 & PMK-91</label>
                                        @php
                                            $jpmk = number_format($dataPB->jumlah_byr_pmk,2,',','.');
                                        @endphp
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><b>Rp.</b></span>
                                            </div>
                                            <input type="text" id="jumlah_bayar" name="jumlah_bayar" class="form-control" value="{{$jpmk}}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tanggal Pembayaran</label>
                                        <div class="input-group" id="tgl_bayar">
                                            @php
                                                $tgl = date('d-m-Y', strtotime($dataPB->tgl_byr_pmk));
                                            @endphp
                                            <input type="text" name="tgl_bayar" class="form-control datetimepicker-input" value="{{$tgl}}" readonly/>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Eksekutor</h3>
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
                                        <select class="form-control select2bs4" multiple="multiple" name="kepala_seksi[]" required>
                                            @php
                                                $ex = explode(",",$dataPB->kepala_seksi);
                                            @endphp
                                            @foreach($dtKepsek as $dt)
                                                <option value="{{$dt->nama_anggota}}" {{ (in_array($dt->nama_anggota, $ex)) ? "selected" : "" }}>{{$dt->nama_anggota}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                <div class="form-group">
                                    <label for="pk_konseptor">PK Konseptor</label>
                                    <select class="form-control select2bs4" multiple="multiple" name="pk_konseptor[]" required>
                                        @php
                                            $ex = explode(",",$dataPB->pk_konseptor);
                                        @endphp
                                        @foreach($dtKepsek as $dt)
                                            <option value="{{$dt->nama_anggota}}" {{ (in_array($dt->nama_anggota, $ex)) ? "selected" : "" }}>{{$dt->nama_anggota}}</option>
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
                                            <input type="text" id="no_prodhukum" name="no_prodhukum" class="form-control" value="{{$dataPB->no_produk_hukum}}">
                                        @else
                                            <input type="text" id="no_prodhukum" name="no_prodhukum" class="form-control">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tanggal Produk Hukum</label>
                                        <div class="input-group" id="tgl_srtper">
                                            @php
                                                $tgl = date('d-m-Y', strtotime($dataPB->tgl_produk_hukum));
                                            @endphp
                                            <input type="text" name="tgl_prodhukum" class="form-control datetimepicker-input datenya" value="{{!empty($dataPB->tgl_produk_hukum) ? $tgl :''}}"/>
                                            <div class="input-group-append" data-toggle="datetimepicker">
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
                                            @php
                                                $jbs = number_format($dataPB->jml_byr_semula,2,',','.');
                                            @endphp
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><b>Rp.</b></span>
                                                </div>
                                                <input type="text" id="jumlah_bayar_awl" name="jumlah_bayar_awl" class="form-control uang" data-affixes-stay="true" data-thousands="." data-decimal="," value="{{$jbs}}">
                                            </div>
                                            @else
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><b>Rp.</b></span>
                                                </div>
                                                <input type="text" id="jumlah_bayar_awl" name="jumlah_bayar_awl" class="form-control uang" data-affixes-stay="true" data-thousands="." data-decimal=",">
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="jumlah_tbh">Tambah</label>
                                        @if($dataPB->tambah)
                                        @php
                                            $tbh = number_format($dataPB->tambah,2,',','.');
                                        @endphp
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><b>Rp.</b></span>
                                            </div>
                                            <input type="text" id="jumlah_tbh" name="jumlah_tbh" class="form-control uang" data-affixes-stay="true" data-thousands="." data-decimal="," value="{{$tbh}}">
                                        </div>
                                        @else
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><b>Rp.</b></span>
                                            </div>
                                            <input type="text" id="jumlah_tbh" name="jumlah_tbh" class="form-control uang" data-affixes-stay="true" data-thousands="." data-decimal=",">
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="jumlah_krg">(Kurang)</label>
                                        @if($dataPB->kurang)
                                        @php
                                            $krg = number_format($dataPB->kurang,2,',','.');
                                        @endphp
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><b>Rp.</b></span>
                                            </div>
                                            <input type="text" id="jumlah_krg" name="jumlah_krg" class="form-control uang" data-affixes-stay="true" data-thousands="." data-decimal="," value="{{$krg}}">
                                        </div>
                                        @else
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><b>Rp.</b></span>
                                            </div>
                                            <input type="text" id="jumlah_krg" name="jumlah_krg" class="form-control uang" data-affixes-stay="true" data-thousands="." data-decimal=",">
                                        </div>
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
                                            @php
                                                //dd($dataPB);
                                                $jbpr = number_format($dataPB->jml_byr_produk,2,',','.');
                                            @endphp
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><b>Rp.</b></span>
                                                </div>
                                                <input type="text" id="jumlah_bayarprod" name="jumlah_bayarprod" class="form-control uang" data-affixes-stay="true" data-thousands="." data-decimal="," value="{{$jbpr}}">
                                            </div>
                                            @else
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><b>Rp.</b></span>
                                                </div>
                                                <input type="text" id="jumlah_bayarprod" name="jumlah_bayarprod" class="form-control uang" data-affixes-stay="true" data-thousands="." data-decimal=",">
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="jumlah_tbh">Hasil Keputusan</label>
                                        <select class="form-control select2bs4" name="hsl_kep" required>
                                            <option selected disabled>Pilih Hasil Keputusan</option>
                                            @foreach($dtKeputusan as $dt)
                                                <option value="{{$dt->keputusan}}" {{ ($dt->keputusan==$dataPB->hasil_keputusan) ? "selected" : "" }}>{{$dt->keputusan}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>No Bukti Terima Kiriman Resi (Pos)</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" accept=".jpeg,.jpg,.png,.pdf" class="custom-file-input" id="noresi" name="noresi" value="{{ !empty($dataPB->no_resi) ? $dataPB->no_resi : '' }}">
                                                <label class="custom-file-label" for="noresi">{{ !empty($dataPB->no_resi) ? $dataPB->no_resi : 'Pilih File' }}</label>
                                            </div>
                                            @if(empty($dataPB->no_resi))
                                            <div class="input-group-append">
                                                <span class="input-group-text" type="button" id="preview-image">Preview</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    @if(!empty($dataPB->no_resi))
                                        <br><br>
                                        lihat file : <a href="{{ asset('public/buktiResi/'.$dataPB->no_resi) }}" target="_blank">{{$dataPB->no_resi}}</a>
                                    @endif
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tanggal Bukti Terima Kiriman Resi (Pos)</label>
                                        <div class="input-group" id="tgl_resi">
                                            @php
                                                $tgl = date('d-m-Y', strtotime($dataPB->tgl_resi));
                                            @endphp
                                            <input type="text" name="tgl_resi" class="form-control datetimepicker-input datenya" value="{{!empty($dataPB->tgl_resi) ? $tgl :''}}"/>
                                            <div class="input-group-append" data-toggle="datetimepicker">
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
                                            <input type="text" id="no_srtpengantar" name="no_srtpengantar" class="form-control" value="{{$dataPB->no_srt_pengantar}}">
                                        @else
                                            <input type="text" id="no_srtpengantar" name="no_srtpengantar" class="form-control">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tanggal Surat Pengantar</label>
                                        <div class="input-group" id="tgl_srtpengantar" >
                                            <!-- datetimepicker-input -->
                                            @php
                                                $tgl = date('d-m-Y', strtotime($dataPB->tgl_srt_pengantar));
                                            @endphp
                                            <input type="text" name="tgl_srtpengantar" class="form-control datetimepicker-input datenya" value="{{!empty($dataPB->tgl_srt_pengantar) ? $tgl :''}}"/>
                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                            <div class="card-footer">
                                @if($dataPB->no_produk_hukum)
                                    <button type="submit" name="mode" class="btn btn-success float-left" value="edit">Update</button>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="{{route("kasi.print", base64_encode($dataPB->no_agenda))}}"  id="print" class="btn btn-info">
                                        <i class="fas fa-download"></i> Unduh Excel
                                    </a>
                                @else
                                    <button type="submit" name="mode" class="btn btn-success float-left" value="add">Add</button>
                                    &nbsp &nbsp<button type="reset" class="btn btn-default">Reset</button>
                                @endif
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
  </div>
  <!-- ./wrapper -->
  @include('layouts.dashboard.javascript')
</body>

</html>
<script src="{{asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('assets/AdminLTE/plugins/moment/moment-with-locales.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('assets/AdminLTE/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/AdminLTE/plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<!-- bs-custom-file-input -->
<script src="{{asset('assets/AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('assets/AdminLTE/plugins/select2/js/select2.full.min.js')}}"></script>

<script src="{{asset('assets/AdminLTE/plugins/jquery-maskmoney/jquery.maskMoney.js')}}"></script>
<script src="{{asset('assets/AdminLTE/plugins/jquery-maskmoney/jquery.maskMoney.min.js')}}"></script>
<script>
    $(document).ready(function () {
        var cekuser = "{{$user->peran}}";
        if (cekuser == 'Eksekutor'){
            $('.form-control, .btn, .form-check-input, .custom-file-input').prop('disabled',false);
            
        }else if(cekuser == 'Forecaster'){
            $('.form-control, .btn, .form-check-input, .custom-file-input').prop('disabled',true);
            $('#print').css('pointer-events','none');
        }
        bsCustomFileInput.init();
        // Format mata uang.
        $('.uang').maskMoney();
    });
    $(function () {
        //Initialize Select2 Elements
        $('.select2bs4').select2({
        theme: 'bootstrap4'
        });
        $('.datenya').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
        });
        // $('#jumlah_bayar, #jumlah_bayar_awl, #jumlah_tbh, #jumlah_bayarprod').inputmask("decimal", {
        //     radixPoint: ".",
        //     groupSeparator: ",",
        //     // digits: 2,
        //     autoGroup: true,
        //     prefix: '', //Space after $, this will not truncate the first character.
        //     rightAlign: false,
        //     autoUnmask: true
        // });
    })
    //Preview image
    var dataTarget = '';
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                dataTarget = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    function debugBase64(base64URL){
        var win = window.open('');
        var html = "<html><head><style type='text/css'>body, html{margin: 0; padding: 0; height: 100%; overflow: hidden;}.h_iframe {height: 100%; width:100%;}</style></head>";
        html += "<div class='h_iframe'><iframe src='" + base64URL  + "' frameborder='0' style='border:0; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:100%;' allowfullscreen></iframe></div></Html>";
        win.document.write(html);
    }
    $("#noresi").change(function(){
        readURL(this);
    });
    $('#preview-image').click(function(){
        if(dataTarget !== ''){
            debugBase64(dataTarget);
        }
    });
</script>