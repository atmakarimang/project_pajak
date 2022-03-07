@section('title','Forecaster')
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
  <!-- datetime picker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/css/bootstrap-datetimepicker.min.css"/>
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
              <h1 class="m-0 text-dark">Forecaster</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active">Forecaster</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      
    <div class="col-md-12">
      <a href="{{route('pelaksanabidang.browse')}}">
        <button class="btn btn-success float-left"><i class="fa fa-search"></i> Database Permohonan</button>
      </a>
    </div>  
    <br><br>
    <section class="content">
        <form id="form-pelaksanabidang" data-toggle="validator" action="{{route('pelaksanabidang.store')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
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
                              @if(!empty($dtPB->no_agenda))
                                <input type="text" id="no_agenda" name="no_agenda" class="form-control"value="{{$dtPB->no_agenda}}" required readonly>
                              @else
                                <input type="text" id="no_agenda" name="no_agenda"  value="{{$no_agenda}}" class="form-control" required readonly>
                              @endif
                          </div>
                        </div>
                        <div class="col-6">
                          <label>Tanggal Agenda</label>
                            <div class="input-group">
                              @if(!empty($dtPB->tgl_agenda))
                                @php
                                  $tgl = date('d-m-Y', strtotime($dtPB->tgl_agenda));
                                @endphp
                                <input type="text" class="form-control" name="tgl_agenda" value="{{$tgl}}" required readonly/>
                              @else
                                <input type="text" name="tgl_agenda" class="form-control datetimepicker-input datenya" name="tgl_agenda[]" required/>
                              @endif
                              <div class="input-group-append" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="no_naskahdinas">No Naskah Dinas</label>
                            @if(!empty($dtPB->no_naskah_dinas))
                              <input type="text" id="no_naskahdinas" name="no_naskahdinas" class="form-control" value="{{$dtPB->no_naskah_dinas}}">
                            @else
                              <input type="text" id="no_naskahdinas" name="no_naskahdinas" class="form-control">
                            @endif
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label>Tanggal Naskah Dinas</label>
                              <div class="input-group">
                                @if(!empty($dtPB->tgl_naskah_dinas))
                                  @php
                                    $tgl = date('d-m-Y', strtotime($dtPB->tgl_naskah_dinas));
                                  @endphp
                                  <input type="text" name="tgl_naskahdinas" class="form-control datetimepicker-input datenya" value="{{$tgl}}"/> 
                                @else
                                  <input type="text" name="tgl_naskahdinas" class="form-control datetimepicker-input datenya"/>
                                @endif
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
                            <select class="form-control select2bs4" name="asal_permohonan" required>
                                <option selected disabled>Pilih Asal Permohonan</option>
                                @foreach($dtAsalPermohonan as $dt)
                                  <option value="{{$dt->pemohon}}" {{ ($dt->pemohon==$dtPB->pemohon) ? "selected" : "" }}>{{$dt->id}} {{$dt->pemohon}}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                              <label for="no_arusdok">No Lembar Pengawasan Arus Dokumen</label>
                              @if(!empty($dtPB->no_lbr_pengawas_dok))
                                <input type="text" id="no_arusdok" name="no_arusdok" class="form-control" value="{{$dtPB->no_lbr_pengawas_dok}}">
                              @else
                                <input type="text" id="no_arusdok" name="no_arusdok" class="form-control">
                              @endif
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label>Tanggal diterima KPP</label>
                              <div class="input-group">
                                @if(!empty($dtPB->tgl_diterima_kpp))
                                  @php
                                    $tgl = date('d-m-Y', strtotime($dtPB->tgl_diterima_kpp));
                                  @endphp
                                  <input type="text" name="tgl_in_kpp" class="form-control datetimepicker-input datenya" value="{{$tgl}}"/>
                                @else
                                  <input type="text" name="tgl_in_kpp" class="form-control datetimepicker-input datenya"/>
                                @endif
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                              </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label>Tanggal diterima Kanwil</label>
                              <div class="input-group">
                                @if(!empty($dtPB->tgl_diterima_kanwil))
                                  @php
                                    $tgl = date('d-m-Y', strtotime($dtPB->tgl_diterima_kanwil));
                                  @endphp
                                  <input type="text" name="tgl_in_kanwil" class="form-control datetimepicker-input datenya" value="{{$tgl}}"/>
                                @else
                                  <input type="text" name="tgl_in_kanwil" class="form-control datetimepicker-input datenya"/>
                                @endif
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
                            @if(!empty($dtPB->npwp))
                              <input type="text" id="no_npwp" name="no_npwp" class="form-control" placeholder="XX.XXX.XXX.X-XXX.XXX" value="{{$dtPB->npwp}}">
                            @else
                              <input type="text" id="no_npwp" name="no_npwp" class="form-control">
                            @endif
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="nama_npwp">Nama Wajib Pajak</label>
                            @if(!empty($dtPB->nama_wajib_pajak))
                              <input type="text" id="nama_npwp" name="nama_npwp" class="form-control" value="{{$dtPB->nama_wajib_pajak}}">
                            @else
                              <input type="text" id="nama_npwp" name="nama_npwp" class="form-control">
                            @endif
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="jenis_permohonan">Jenis Permohonan</label>
                            <select class="form-control select2bs4" name="jenis_permohonan" required>
                                <option selected disabled>Pilih Jenis Permohonan</option>
                                @foreach($dtJnsPermohonan as $dt)
                                  <option value="{{$dt->jenis_permohonan}}" {{ ($dt->jenis_permohonan==$dtPB->jenis_permohonan) ? "selected" : "" }}>{{$dt->jenis_permohonan}}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="jenis_pajak">Jenis Pajak</label>
                            <select class="form-control select2bs4" name="jenis_pajak" required>
                                <option selected disabled>Pilih Jenis Pajak</option>
                                @foreach($dtPajak as $dt)
                                  <option value="{{$dt->pajak}}" {{ ($dt->pajak==$dtPB->pajak) ? "selected" : "" }}>{{$dt->pajak}}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="jenis_ketetapan">Jenis Ketetapan</label>
                            <select class="form-control select2bs4" name="jenis_ketetapan" required>
                                <option selected disabled>Pilih Jenis Ketetapan</option>
                                @foreach($dtKetetapan as $dt)
                                  <option value="{{$dt->jenis_ketetapan}}" {{ ($dt->jenis_ketetapan==$dtPB->jenis_ketetapan) ? "selected" : "" }}>{{$dt->jenis_ketetapan}}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="no_ketetapan">No Ketetapan</label>
                            @if(!empty($dtPB->no_ketetapan))
                              <input type="text" id="no_ketetapan" name="no_ketetapan" class="form-control" placeholder="XXXXX/XXX/XX/XXX/XX" value="{{$dtPB->no_ketetapan}}">
                            @else
                              <input type="text" id="no_ketetapan" name="no_ketetapan" class="form-control">
                            @endif
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label>Tanggal Ketetapan</label>
                            <div class="input-group">
                              @if(!empty($dtPB->tgl_ketetapan))
                                @php
                                  $tgl = date('d-m-Y', strtotime($dtPB->tgl_ketetapan));
                                @endphp
                                <input type="text" name="tgl_ketetapan" class="form-control datetimepicker-input datenya" value="{{$tgl}}"/>
                              @else
                                <input type="text" name="tgl_ketetapan" class="form-control datetimepicker-input datenya"/>
                              @endif
                              <div class="input-group-append">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="jenis_ketetapan">Masa Pajak</label>
                            <select class="form-control select2bs4" name="masa_pajak" required>
                                <option selected disabled>Pilih Masa Pajak</option>
                                <option value="Januari" {{ ($dtPB->masa_pajak == 'Januari') ? "selected" : "" }}>Januari</option>
                                <option value="Februari" {{ ($dtPB->masa_pajak == 'Februari') ? "selected" : "" }}>Februari</option>
                                <option value="Maret" {{ ($dtPB->masa_pajak == 'Maret') ? "selected" : "" }}>Maret</option>
                                <option value="April" {{ ($dtPB->masa_pajak == 'April') ? "selected" : "" }}>April</option>
                                <option value="Mei" {{ ($dtPB->masa_pajak == 'Mei') ? "selected" : "" }}>Mei</option>
                                <option value="Juni" {{ ($dtPB->masa_pajak == 'Juni') ? "selected" : "" }}>Juni</option>
                                <option value="Juli" {{ ($dtPB->masa_pajak == 'Juli') ? "selected" : "" }}>Juli</option>
                                <option value="Agustus" {{ ($dtPB->masa_pajak == 'Agustus') ? "selected" : "" }}>Agustus</option>
                                <option value="September" {{ ($dtPB->masa_pajak == 'September') ? "selected" : "" }}>September</option>
                                <option value="Oktober" {{ ($dtPB->masa_pajak == 'Oktober') ? "selected" : "" }}>Oktober</option>
                                <option value="November" {{ ($dtPB->masa_pajak == 'November') ? "selected" : "" }}>November</option>
                                <option value="Desember" {{ ($dtPB->masa_pajak == 'Desember') ? "selected" : "" }}>Desember</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="tahun_pajak">Tahun Pajak</label>
                            @if(!empty($dtPB->tahun_pajak))
                              <input type="text" id="tahun_pajak" name="tahun_pajak" class="form-control year" value="{{$dtPB->tahun_pajak}}">
                            @else
                              <input type="text" id="tahun_pajak" name="tahun_pajak" class="form-control year">
                            @endif
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="kat_permohonan">Kategori Permohonan</label>
                            <select class="form-control" name="kat_permohonan" required>
                                <option selected disabled>Pilih Kategori Permohonan</option>
                                @foreach($dtKatPermohonan as $dt)
                                  <option value="{{$dt->kat_permohonan}}" {{ ($dtPB->kat_permohonan == $dt->kat_permohonan) ? "selected" : "" }}>{{$dt->kat_permohonan}}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="no_srt_per">No Surat Permohonan</label>
                            @if(!empty($dtPB->no_srt_permohonan))
                              <input type="text" id="no_srt_per" name="no_srt_per" class="form-control" value="{{$dtPB->no_srt_permohonan}}">
                            @else
                              <input type="text" id="no_srt_per" name="no_srt_per" class="form-control"> 
                            @endif
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label>Tanggal Surat Permohonan</label>
                            <div class="input-group">
                              @if(!empty($dtPB->tgl_srt_permohonan))
                                @php
                                  $tgl = date('d-m-Y', strtotime($dtPB->tgl_srt_permohonan));
                                @endphp
                                <input type="text" name="tgl_srtper" class="form-control datetimepicker-input datenya" value="{{$tgl}}"/>
                              @else
                                <input type="text" name="tgl_srtper" class="form-control datetimepicker-input datenya"/>
                              @endif
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
                              @if(!empty($dtPB->seksi_konseptor))
                                @php
                                  $ex = explode(",",$dtPB->seksi_konseptor);
                                @endphp
                              @endif
                              @foreach($dtSeksiKonsep as $dt)
                                &nbsp;&nbsp;&nbsp;
                                <div class="form-check">
                                  @if(!empty($dtPB->seksi_konseptor))
                                    <input class="form-check-input" name="seksi_konseptor[]" value="{{$dt->seksi_konseptor}}" type="checkbox" {{ (in_array($dt->seksi_konseptor, $ex)) ? "checked" : "" }}>
                                  @else
                                    <input class="form-check-input" name="seksi_konseptor[]" value="{{$dt->seksi_konseptor}}" type="checkbox">
                                  @endif
                                  <label class="form-check-label">{{$dt->seksi_konseptor}}</label>
                                </div>
                              @endforeach
                            </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" name="status" required>
                              @foreach($dtStatus as $dt)
                                <option value="{{$dt->status}}" {{ ($dtPB->status == $dt->status) ? "selected" : " " }}>{{$dt->status}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="progress">Progress</label>
                            <select class="form-control" name="progress" required>
                              @foreach($dtProgress as $dt)
                                <option value="{{$dt->progress}}" {{ ($dtPB->progress == $dt->progress) ? "selected" : " " }}>{{$dt->progress}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="jumlah_bayar">Jumlah Pembayaran a/ PMK-29 & PMK-91</label>
                            @if(!empty($dtPB->jumlah_byr_pmk))
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><b>Rp.</b></span>
                                </div>
                                <input type="text" id="jumlah_bayar" name="jumlah_bayar" class="form-control uang" value="{{$dtPB->jumlah_byr_pmk}}">
                              </div>
                              <!-- <input type="text" id="jumlah_bayar" name="jumlah_bayar" class="form-control" value="{{$dtPB->jumlah_byr_pmk}}"> -->
                            @else
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><b>Rp.</b></span>
                                </div>
                                <input type="text" id="jumlah_bayar" name="jumlah_bayar" class="form-control uang" data-affixes-stay="true" data-thousands="." data-decimal="," value="">
                              </div>
                            @endif
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label>Tanggal Pembayaran</label>
                            <div class="input-group">
                              @if(!empty($dtPB->tgl_byr_pmk))
                                @php
                                  $tgl = date('d-m-Y', strtotime($dtPB->tgl_byr_pmk));
                                @endphp
                                <input type="text" name="tgl_bayar" class="form-control datetimepicker-input datenya" value="{{$tgl}}"/>
                              @else
                                <input type="text" name="tgl_bayar" class="form-control datetimepicker-input datenya"/>
                              @endif
                              <div class="input-group-append">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="card-footer hideread">
                            @if($mode=='edit')
                              <button type="submit" name="mode" class="btn btn-success float-left submit" value="edit">Update</button>
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              <a href="{{route("pelaksanabidang.print", base64_encode($dtPB->no_agenda))}}"  id="print" class="btn btn-info">
                                <i class="fas fa-download"></i> Unduh Excel
                              </a>
                            @else
                              <button type="submit" name="mode" class="btn btn-success float-left submit" value="add">Add</button>
                              &nbsp &nbsp<button type="reset" class="btn btn-default reset">Reset</button>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.card-body -->
                  </div>
                <!-- /.card -->
                </div>
            </div>
        </form>
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
<script src="{{asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('assets/AdminLTE/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/AdminLTE/plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css"></script>

<!-- Bootstrap 4 -->
<script src="{{asset('assets/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('assets/AdminLTE/plugins/select2/js/select2.full.min.js')}}"></script>
<!-- <script src="{{asset('assets/AdminLTE/plugins/jquery-maskmoney/jquery.maskMoney.js')}}"></script>
<script src="{{asset('assets/AdminLTE/plugins/jquery-maskmoney/jquery.maskMoney.min.js')}}"></script> -->
<script>
  $(function () {
    // XX.XXX.XXX.X-XXX.XXX
    $('#no_npwp').mask("00.000.000.0-000.000", {placeholder: "__.__.___.___._-___.___"});
    // XXXXX/XXX/XX/XXX/XX
    $('#no_ketetapan').mask("00000/000/00/000/00", {placeholder: "_____/___/__/___/__"});
    // Format mata uang.
    // $('.uang').maskMoney();
    $('.uang').inputmask("decimal", {
      radixPoint: ".",
      groupSeparator: ",",
      autoGroup: true,
      prefix: '', //Space after $, this will not truncate the first character.
      rightAlign: false,
      autoUnmask: true
    });
    var cekuser = "{{$user->peran}}";
    if (cekuser == 'Forecaster'){
      $('.form-control, .submit, .reset, .form-check-input').prop('disabled',false);
    }else if(cekuser == 'Eksekutor'){
      $('.form-control, .submit, .reset, .form-check-input').prop('disabled',true);
      $('#print').css('pointer-events','none');
    }

    //Disabled form jk open dari Laporan
    var params = new window.URLSearchParams(window.location.search);
    var isreadonly = params.get('readonly');
    if(isreadonly == 1){
      $("input:not(#top_bar_search)").prop('disabled', true);
      $(".btn, .form-control").attr("disabled", true);
      $('.hideread').hide();
    }

    $('.select2').select2();
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });
    $('.datenya').datepicker({
      autoclose: true,
      todayHighlight: true,
      format: 'dd-mm-yyyy'
    });
    $('.year').datepicker({
      minViewMode: 2,
      format: 'yyyy'
    });
    $('#jumlah_bayar').inputmask("decimal", {
      radixPoint: ".",
      groupSeparator: ".",
      // digits: 2,
      autoGroup: true,
      prefix: '', //Space after $, this will not truncate the first character.
      rightAlign: false,
      autoUnmask: true
    });
    // if ($('input:checkbox').filter(':checked').length < 1){
    //     alert("Pilih seksi konseptor!");
    //   return false;
    // }
  });
</script>