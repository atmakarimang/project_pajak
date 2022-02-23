@section('title','Eksekutor & Forecaster')
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/Eonasdan/bootstrap-datetimepicker@a549aa8780dbda16f6cff545aeabc3d71073911e/build/css/bootstrap-datetimepicker.css">
  <link href="{{asset('public/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css">
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
              <h1 class="m-0 text-dark">Eksekutor & Forecaster</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active">Eksekutor & Forecaster</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      
    <div class="col-md-12">
      <a href="{{route('nonpelaksanabidang.browse')}}">
        <button class="btn btn-success float-left"><i class="fa fa-search"></i> Database Non Permohonan</button>
      </a>
    </div>  
    <br><br>
    <section class="content">
        <form id="form-nonpelaksanabidang" data-toggle="validator" action="{{route('nonpelaksanabidang.store')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                  <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title">Eksekutor & Forecaster</h3>
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
                                <input type="text" class="form-control" id="tgl_agenda" name="tgl_agenda" value="{{$tgl}}" required readonly/>
                              @else
                                <input type="text" id="tgl_agenda" name="tgl_agenda" class="form-control datetimepicker-input datenya" required/>
                                <div class="input-group-append" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                              @endif
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="no_naskahdinas">Nomor Surat</label>
                            @if(!empty($dtPB->no_surat))
                              <input type="text" id="no_surat" name="no_surat" class="form-control" value="{{$dtPB->no_surat}}" required>
                            @else
                              <input type="text" id="no_surat" name="no_surat" class="form-control" required>
                            @endif
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label>Tanggal Surat</label>
                              <div class="input-group" id="tgl_surat">
                                @if(!empty($dtPB->tgl_surat))
                                  @php
                                    $tgl = date('d-m-Y', strtotime($dtPB->tgl_surat));
                                  @endphp
                                  <input type="text" name="tgl_surat" class="form-control datenya" value="{{$tgl}}" required/>
                                  <div class="input-group-append" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                                @else
                                  <input type="text" name="tgl_surat" class="form-control datenya" required/>
                                  <div class="input-group-append" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                                @endif
                              </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <label>Tanggal diterima Kanwil</label>
                          <div class="input-group" id="tgl_diterima_kanwil">
                            @if(!empty($dtPB->tgl_diterima_kanwil))
                              @php
                                $tgl = date('d-m-Y', strtotime($dtPB->tgl_diterima_kanwil));
                              @endphp
                              <input type="text" name="tgl_diterima_kanwil" class="form-control datetimepicker-input datenya" value="{{$tgl}}" required/>
                            @else
                              <input type="text" name="tgl_diterima_kanwil" class="form-control datetimepicker-input datenya" required/>
                              <div class="input-group-append" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                            @endif
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                              <label for="asal_surat">Asal Surat</label>
                              @if(!empty($dtPB->asal_surat))
                                <input type="text" id="asal_surat" name="asal_surat" class="form-control" value="{{$dtPB->asal_surat}}" required>
                              @else
                                <input type="text" id="asal_surat" name="asal_surat" class="form-control" required>
                              @endif
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="hal">Hal</label>
                            @if(!empty($dtPB->hal))
                              <input type="text" id="hal" name="hal" class="form-control" value="{{$dtPB->hal}}" required>
                            @else
                              <input type="text" id="hal" name="hal" class="form-control" required>
                            @endif
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="no_npwp">No Pokok Wajib Pajak</label>
                            @if(!empty($dtPB->npwp))
                              <input type="text" id="no_npwp" name="no_npwp" class="form-control" value="{{$dtPB->npwp}}" required>
                            @else
                              <input type="text" id="no_npwp" name="no_npwp" class="form-control" required>
                            @endif
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
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
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="kepala_seksi">Kepala Seksi</label>
                            <select class="form-control select2bs4" multiple="multiple" name="kepala_seksi[]" required>
                              @php
                                $ex = explode(",",$dtPB->kepala_seksi);
                              @endphp
                              @foreach($dtKepsek as $dt)
                                <option value="{{$dt->nama_anggota}}" {{ (in_array($dt->nama_anggota, $ex)) ? "selected" : "" }}>{{$dt->nama_anggota}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="penerima_disposisi">Penerima Disposisi</label>
                            @if(!empty($dtPB->penerima_disposisi))
                              <input type="text" id="penerima_disposisi" name="penerima_disposisi" class="form-control" value="{{$dtPB->penerima_disposisi}}" required>
                            @else
                              <input type="text" id="penerima_disposisi" name="penerima_disposisi" class="form-control" required>
                            @endif
                          </div>
                        </div>
                      </div>
                      <div class="col-12 row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" name="status" required>
                              <option selected disabled>Pilih Status</option>
                              @foreach($dtStatus as $dt)
                                <option value="{{$dt->status}}" {{ ($dtPB->status == $dt->status) ? "selected" : " " }}>{{$dt->status}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="card-footer">
                            @if($mode=='edit')
                              <button type="submit" name="mode" class="btn btn-success float-left" value="edit">Update</button>
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              <a href="{{route("nonpelaksanabidang.print", base64_encode($dtPB->no_agenda))}}"  id="print" class="btn btn-info">
                                <i class="fas fa-download"></i> Unduh Excel
                              </a>
                            @else
                              <button type="submit" name="mode" class="btn btn-success float-left" value="add">Add</button>
                              &nbsp &nbsp<button type="reset" class="btn btn-default ">Reset</button>
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
  </div>
  <!-- ./wrapper -->
  @include('layouts.dashboard.javascript')
</body>
</html>

<script src="{{asset('public/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('assets/AdminLTE/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/AdminLTE/plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

<script src="https://cdn.jsdelivr.net/gh/Eonasdan/bootstrap-datetimepicker@a549aa8780dbda16f6cff545aeabc3d71073911e/src/js/bootstrap-datetimepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('assets/AdminLTE/plugins/select2/js/select2.full.min.js')}}"></script>

<script>
  $(function () {
    // XX.XXX.XXX.X-XXX.XXX
    $('#no_npwp').mask("00.000.000.0-000.000", {placeholder: "__.__.___.___._-___.___"});
    //Disabled form jk open dari Laporan
    var params = new window.URLSearchParams(window.location.search);
    var isreadonly = params.get('readonly');
    if(isreadonly == 1){
      $("input:not(#top_bar_search)").prop('disabled', true);
      $(".btn, .form-control").attr("disabled", true);
      $('.hideread').hide();
    }
    $('#datetimepicker1').datetimepicker();
    $('.select2').select2();
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });
    
    $('.datenya').datepicker({
      autoclose: true,
      todayHighlight: true,
      format: 'dd-mm-yyyy'
    });
    // if ($('input:checkbox').filter(':checked').length < 1){
    //     alert("Pilih seksi konseptor!");
    //   return false;
    // }
  });
</script>