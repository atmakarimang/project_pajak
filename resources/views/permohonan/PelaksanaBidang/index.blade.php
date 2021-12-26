@section('title','Pelaksana Bidang')
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{asset('assets/AdminLTE/plugins/daterangepicker/daterangepicker.css')}}">
  <script src="{{asset('assets/AdminLTE/plugins/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('assets/AdminLTE/plugins/jquery/jquery.js')}}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{asset('assets/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="{{asset('assets/AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- InputMask -->
  <script src="{{asset('assets/AdminLTE/plugins/moment/moment.min.js')}}"></script>
  <!-- date-range-picker -->
  <script src="{{asset('assets/AdminLTE/plugins/daterangepicker/daterangepicker.js')}}"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="{{asset('assets/AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
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
              <h1 class="m-0 text-dark">Form Pelaksana Bidang</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Pelaksana Bidang</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      
    <div class="col-md-12">
      <a href="{{route('pelaksanabidang.browse')}}">
        <button class="btn btn-success float-left"><i class="fa fa-search"></i> Browse Pelaksana Bidang</button>
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
                      <h3 class="card-title">General</h3>
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
                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                              @if(!empty($dtPB->tgl_agenda))
                                @php
                                  $tgl = date('d/m/Y', strtotime($dtPB->tgl_agenda));
                                @endphp
                                <input type="text" class="form-control" data-target="#tgl_agenda" name="tgl_agenda" value="{{$tgl}}" required readonly/>
                              @else
                                <input type="text" name="tgl_agenda" class="form-control datetimepicker-input" data-target="#reservationdate" required/>
                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                              @endif
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="no_naskahdinas">No Naskah Dinas</label>
                            @if(!empty($dtPB->no_naskah_dinas))
                              <input type="text" id="no_naskahdinas" name="no_naskahdinas" class="form-control" value="{{$dtPB->no_naskah_dinas}}" required>
                            @else
                              <input type="text" id="no_naskahdinas" name="no_naskahdinas" class="form-control" required>
                            @endif
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label>Tanggal Naskah Dinas</label>
                              <div class="input-group date" id="agendadate" data-target-input="nearest">
                                @if(!empty($dtPB->tgl_naskah_dinas))
                                  @php
                                    $tgl = date('d/m/Y', strtotime($dtPB->tgl_naskah_dinas));
                                  @endphp
                                  <input type="text" name="tgl_naskahdinas" class="form-control date" data-target="#tgl_naskahdinas" value="{{$tgl}}" required/>
                                  <div class="input-group-append" data-target="#tgl_naskahdinas" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                                @else
                                  <input type="text" name="tgl_naskahdinas" class="form-control date" data-target="#tgl_naskahdinas" required/>
                                  <div class="input-group-append" data-target="#tgl_naskahdinas" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                                @endif
                              </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="asal_permohonan">Asal Permohonan</label>
                            <select class="form-control select2" name="asal_permohonan" required>
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
                                <input type="text" id="no_arusdok" name="no_arusdok" class="form-control" value="{{$dtPB->no_lbr_pengawas_dok}}" required>
                              @else
                                <input type="text" id="no_arusdok" name="no_arusdok" class="form-control" required>
                              @endif
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label>Tanggal diterima KPP</label>
                              <div class="input-group date" id="tgl_in_kpp" data-target-input="nearest">
                                @if(!empty($dtPB->tgl_diterima_kpp))
                                  @php
                                    $tgl = date('d/m/Y', strtotime($dtPB->tgl_diterima_kpp));
                                  @endphp
                                  <input type="text" name="tgl_in_kpp" class="form-control datetimepicker-input" data-target="#tgl_in_kpp" value="{{$tgl}}" required/>
                                @else
                                  <input type="text" name="tgl_in_kpp" class="form-control datetimepicker-input" data-target="#tgl_in_kpp" required/>
                                  <div class="input-group-append" data-target="#tgl_in_kpp" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                                @endif
                              </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label>Tanggal diterima Kanwil</label>
                              <div class="input-group date" id="tgl_in_kanwil" data-target-input="nearest">
                                @if(!empty($dtPB->tgl_diterima_kanwil))
                                  @php
                                    $tgl = date('d/m/Y', strtotime($dtPB->tgl_diterima_kanwil));
                                  @endphp
                                  <input type="text" name="tgl_in_kanwil" class="form-control datetimepicker-input" data-target="#tgl_in_kanwil" value="{{$tgl}}" required/>
                                @else
                                  <input type="text" name="tgl_in_kanwil" class="form-control datetimepicker-input" data-target="#tgl_in_kanwil" required/>
                                  <div class="input-group-append" data-target="#tgl_in_kanwil" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                                @endif
                              </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="no_npwp">No Pokok Wajib Pajak</label>
                            @if(!empty($dtPB->npwp))
                              <input type="text" id="no_npwp" name="no_npwp" class="form-control" placeholder="XX.XXX.XXX.X-XXX.XXX" value="{{$dtPB->npwp}}" required>
                            @else
                              <input type="text" id="no_npwp" name="no_npwp" class="form-control" placeholder="XX.XXX.XXX.X-XXX.XXX" required>
                            @endif
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="nama_npwp">Nama Wajib Pajak</label>
                            @if(!empty($dtPB->nama_wajib_pajak))
                              <input type="text" id="nama_npwp" name="nama_npwp" class="form-control" value="{{$dtPB->nama_wajib_pajak}}" required>
                            @else
                              <input type="text" id="nama_npwp" name="nama_npwp" class="form-control" required>
                            @endif
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="jenis_permohonan">Jenis Permohonan</label>
                            <select class="form-control select2" name="jenis_permohonan" required>
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
                            <select class="form-control select2" name="jenis_pajak" required>
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
                            <select class="form-control select2" name="jenis_ketetapan" required>
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
                              <input type="text" id="no_ketetapan" name="no_ketetapan" class="form-control" placeholder="XXXXX/XXX/XX/XXX/XX" value="{{$dtPB->no_ketetapan}}" required>
                            @else
                              <input type="text" id="no_ketetapan" name="no_ketetapan" class="form-control" placeholder="XXXXX/XXX/XX/XXX/XX" required>
                            @endif
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label>Tanggal Ketetapan</label>
                            <div class="input-group date" id="tgl_ketetapan" data-target-input="nearest">
                              @if(!empty($dtPB->tgl_ketetapan))
                                  @php
                                    $tgl = date('d/m/Y', strtotime($dtPB->tgl_ketetapan));
                                  @endphp
                                  <input type="text" name="tgl_ketetapan" class="form-control datetimepicker-input" data-target="#tgl_ketetapan" value="{{$tgl}}" required/>
                              @else
                                <input type="text" name="tgl_ketetapan" class="form-control datetimepicker-input" data-target="#tgl_ketetapan" required/>
                                <div class="input-group-append" data-target="#tgl_ketetapan" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                              @endif
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="jenis_ketetapan">Masa Pajak</label>
                            <select class="form-control select2" name="masa_pajak" required>
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
                              <input type="text" id="tahun_pajak" name="tahun_pajak" class="form-control" value="{{$dtPB->tahun_pajak}}" required>
                            @else
                              <input type="text" id="tahun_pajak" name="tahun_pajak" class="form-control" required>
                            @endif
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="kat_permohonan">Kategori Permohonan</label>
                            <select class="form-control select2" name="kat_permohonan" required>
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
                              <input type="text" id="no_srt_per" name="no_srt_per" class="form-control" value="{{$dtPB->no_srt_permohonan}}" required>
                            @else
                              <input type="text" id="no_srt_per" name="no_srt_per" class="form-control" required>
                            @endif
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label>Tanggal Surat Permohonan</label>
                            <div class="input-group date" id="tgl_srtper" data-target-input="nearest">
                              @if(!empty($dtPB->tgl_srt_permohonan))
                                  @php
                                    $tgl = date('d/m/Y', strtotime($dtPB->tgl_srt_permohonan));
                                  @endphp
                                  <input type="text" name="tgl_srtper" class="form-control datetimepicker-input" data-target="#tgl_srtper" value="{{$dtPB->tgl_srt_permohonan}}" required/>
                              @else
                                <input type="text" name="tgl_srtper" class="form-control datetimepicker-input" data-target="#tgl_srtper" required/>
                                <div class="input-group-append" data-target="#tgl_srtper" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                              @endif
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
                            <select class="form-control select2" name="status" required>
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
                            <select class="form-control select2" name="progress" required>
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
                              <input type="text" id="jumlah_bayar" name="jumlah_bayar" class="form-control" value="{{$dtPB->jumlah_byr_pmk}}" required>
                            @else
                              <input type="text" id="jumlah_bayar" name="jumlah_bayar" class="form-control" required>
                            @endif
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label>Tanggal Pembayaran</label>
                            <div class="input-group date" id="tgl_bayar" data-target-input="nearest">
                              @if(!empty($dtPB->tgl_byr_pmk))
                                  @php
                                    $tgl = date('d/m/Y', strtotime($dtPB->tgl_byr_pmk));
                                  @endphp
                                  <input type="text" name="tgl_bayar" class="form-control datetimepicker-input" data-target="#tgl_bayar" value="{{$tgl}}" required/>
                              @else
                                <input type="text" name="tgl_bayar" class="form-control datetimepicker-input" data-target="#tgl_bayar" required/>
                                <div class="input-group-append" data-target="#tgl_bayar" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                              @endif
                            </div>
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="card-footer">
                            @if($mode=='edit')
                              <button type="submit" name="mode" class="btn btn-success float-left" value="edit">Update</button>
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              <div class="btn-group">
                                <button type="button" class="btn btn-info">Print</button>
                                <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                  <span class="sr-only">Toggle Dropdown</span>
                                  <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item" href='{{route("pelaksanabidang.print", [base64_encode($dtPB->no_agenda), "xls"])}}'>.xls</a>
                                    <a class="dropdown-item" href='{{route("pelaksanabidang.print", [base64_encode($dtPB->no_agenda), "xlsx"])}}'>.xlsx</a>
                                  </div>
                                </button>
                            </div>
                            @else
                              <button type="submit" name="mode" class="btn btn-success float-left" value="add">Add</button>
                              <button type="submit" class="btn btn-default float-right">Cancel</button>
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
        <script>
          console.log("AS");
          //Date range picker
            $('#reservationdate').datetimepicker({
                format: 'L'
            });
            //Date range picker
            $('#reservation').daterangepicker()
            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
              timePicker: true,
              timePickerIncrement: 30,
              locale: {
                format: 'MM/DD/YYYY hh:mm A'
              }
            })
            
        </script>
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
<script>
  alert('AH')
</script>
@endsection
