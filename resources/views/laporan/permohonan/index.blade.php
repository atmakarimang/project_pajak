@section('title','E-Reporting Permohonan')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="{{asset('assets/AdminLTE/plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/AdminLTE/plugins/jquery/jquery.js')}}"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets/AdminLTE/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- Bootstrap 4 -->
    <script src="{{asset('assets/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('assets/AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <script src="{{asset('assets/AdminLTE/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/AdminLTE/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
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
              <h1 class="m-0 text-dark">E-Reporting Permohonan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active">E-Reporting Permohonan</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                          <center>
                            <h5><b>E-REPORTING PERMOHONAN KEBERATAN DAN NON KEBERATAN </b></h5>
                            <h5><b>BIDANG KEBERATAN BANDING DAN PENGURANGAN</b></h5>
                            <h5><b>KANWIL DJP JAWA TIMUR I</b></h5>
                          </center>
                            <div class="row">
                              <div class="col-md-3">
                                <div class="card-body">
                                  <div class="form-group">
                                    <label for="jenis_permohonan">Jenis Permohonan</label>
                                    <select class="form-control select2bs4" id="jenis_permohonan" name="jenis_permohonan">
                                      <option selected disabled value='-'>Pilih Jenis Permohonan</option>
                                      @foreach($dtJnsPermohonan as $dt)
                                        <option value="{{$dt->jenis_permohonan}}">{{$dt->jenis_permohonan}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label for="seksi_konseptor">Seksi Konseptor</label>
                                    <select class="form-control select2bs4" id="seksi_konseptor" name="seksi_konseptor">
                                      <option selected disabled value='-'>Pilih Seksi Konseptor</option>
                                      @foreach($dtSK as $dt)
                                        <option value="{{$dt->seksi_konseptor}}">{{$dt->seksi_konseptor}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label for="pk_konseptor">PK Konseptor</label>
                                    <select class="form-control select2bs4" id="pk_konseptor" name="pk_konseptor">
                                        <option selected disabled value='-'>Pilih PK Konseptor</option>
                                        @foreach($dtKepsek as $dt)
                                            <option value="{{$dt->nama_penelaah}}">{{$dt->nama_penelaah}}</option>
                                        @endforeach
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <br>
                                    <button id="generate" class="btn btn-primary float-left" value="add">Lihat Laporan</button>
                                    &nbsp;&nbsp;&nbsp;
                                    <a href="javascript:void(0)"  id="print" class="btn btn-success " style="margin-right: 5px;">
                                        <i class="fas fa-download"></i> Unduh Excel
                                    </a>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="card-body">
                                  <div class="form-group">
                                    <label for="jatuh_tempo">Jatuh Tempo</label>
                                    <select class="form-control select2bs4" id="jatuh_tempo" name="jatuh_tempo">
                                        <option selected disabled value='-'>Pilih Jatuh Tempo</option>
                                        <option value="MR">MR</option>
                                        <option value="IKU">IKU</option>
                                        <option value="KUP">KUP</option>
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label for="tgl_jt">Tanggal Jatuh Tempo :</label>
                                    <div class="input-group">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text">
                                          <i class="far fa-calendar-alt"></i>
                                        </span>
                                      </div>
                                      <input type="text" class="form-control float-right" id="tgl_jt">
                                    </div>
                                    <input type="hidden" class="form-control" id="tgl_awal" value="">
                                    <input type="hidden" class="form-control" id="tgl_akhir" value="">
                                  </div>
                                  <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control select2bs4" id="status" name="status">
                                      <option selected disabled value='-'>Pilih Status</option>
                                      @foreach($dtStatus as $dt)
                                        <option value="{{$dt->status}}">{{$dt->status}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="table-responsive" id="report" style="display: none">
                              <table class="table table-bordered table-hover dt-responsive" id="tabel-pb">
                                  <thead style="text-align:center">
                                      <tr>
                                        <th rowspan="2" style="width: 10px">No</th>
                                        <th rowspan="2">No Agenda</th>
                                        <th rowspan="2">Tanggal Diterima KPP</th>
                                        <th rowspan="2">NPWP</th>
                                        <th rowspan="2">Nama Wajib Pajak</th>
                                        <th rowspan="2">Jenis Permohonan</th>
                                        <th rowspan="2">No Ketetapan</th>
                                        <th rowspan="2">Seksi Konseptor</th>
                                        <th rowspan="2">Kepala Seksi</th>
                                        <th rowspan="2">PK Konseptor</th>
                                        <th rowspan="2">Status</th>
                                        <th colspan="3">Jatuh Tempo</th>
                                        <th rowspan="2" style="width: 5%"></th>
                                      </tr>
                                      <tr>
                                        <th>MR</th>
                                        <th>IKU</th>
                                        <th>KUP</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                  </tbody>
                              </table>
                            </div>
                        </div>
                    </div>
                </div>
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
<!-- Select2 -->
<script src="{{asset('assets/AdminLTE/plugins/select2/js/select2.full.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('assets/AdminLTE/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script>
    $(function() {
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });
        $('#tgl_jt').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Reset',
                applyLabel: 'Pilih'
            }
        }, function(start, end, label) {
            $start = start.format('DD/MM/YYYY');
            $end = end.format('DD/MM/YYYY');
            $('#tgl_awal').val($start);
            $('#tgl_akhir').val($end);
        });

        $('#tgl_jt').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });

        $('#tgl_jt').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });

    $('#generate').click(function(){
        $('#report').css('display','block');
        $('#tabel-pb').DataTable().destroy();
        var report_table = $('#tabel-pb').DataTable({
            "processing": true,
            "responsive" : true,
            "serverSide": true,
            "ajax": {
                "url" : "{{route('laporan_permohonan.ajaxDataPB')}}",
                "type" : "GET",
                "data" : function(d){
                    d._token = "{{ csrf_token() }}";
                    d.jenis_pmh = $('#jenis_permohonan option:selected').val();
                    d.seksi_konseptor = $('#seksi_konseptor option:selected').val();
                    d.pk_konseptor = $('#pk_konseptor option:selected').val();
                    d.jatuh_tempo = $('#jatuh_tempo option:selected').val();
                    d.status = $('#status option:selected').val();
                    d.tgl_awal = $('#tgl_awal').val();
                    d.tgl_akhir = $('#tgl_akhir').val();
                }
            },
            columnDefs: [
              {"targets": 0, "orderable": false},
              {"targets": 1, "name": 'no_agenda'},
              {"targets": 2, "name": 'tgl_diterima_kpp'},
              {"targets": 3, "name": 'npwp'},
              {"targets": 4, "name": 'nama_wajib_pajak'},
              {"targets": 5, "name": 'jenis_permohonan'},
              {"targets": 6, "name": 'no_ketetapan'},
              {"targets": 7, "name": 'seksi_konseptor'},
              {"targets": 8, "name": 'kepala_seksi'},
              {"targets": 9, "name": 'pk_konseptor'},
              {"targets": 10, "name": 'status'},
              {"targets": 11, "orderable": false},
              {"targets": 12, "orderable": false},
              {"targets": 13, "orderable": false},
              {"targets": 14, "orderable": false},
            ],
            order: [[ 0, "DESC" ]],
        })
    });

    $("#print").click(function(e){
        var jenis_pmh = $('#jenis_permohonan option:selected').val();
        var seksi_konseptor = $('#seksi_konseptor option:selected').val();
        var pk_konseptor = $('#pk_konseptor option:selected').val();
        var jatuh_tempo = $('#jatuh_tempo option:selected').val();
        var status = $('#status option:selected').val();
        var tgl_awal = $('#tgl_awal').val();
        var tgl_akhir = $('#tgl_akhir').val();
        var _token = "{{ csrf_token() }}";
        
        $('#print').prop("href","{{url('laporan/permohonan/print')}}?_token="+_token+"&jenis_pmh="+jenis_pmh+"&seksi_konseptor="+seksi_konseptor+
        "&pk_konseptor="+pk_konseptor+"&jatuh_tempo="+jatuh_tempo+"&"+"&status="+status+"&"+"&tgl_awal="+tgl_awal+"&"+"&tgl_akhir="+tgl_akhir);
    });
            
  function buttonDelete(data){
    window.location.href = data.getAttribute('data-link');
    // swal({   
    //     title: "Are you sure?",   
    //     text: "You will not be able to recover this data!",   
    //     type: "warning",   
    //     showCancelButton: true,   
    //     confirmButtonColor: "#DD6B55",   
    //     confirmButtonText: "Yes",   
    //     closeOnConfirm: true 
    // }, function(){
    //     // window.location.href = data.getAttribute('data-link');
    // });
  }
</script>