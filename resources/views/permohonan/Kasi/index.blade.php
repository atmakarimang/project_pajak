@section('title','Eksekutor')
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

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
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                          @php
                              $search_key = $data['search'];
                              $searchval = "";
                              if(!empty($search_key)){
                                $searchval = $search_key;
                              }
                          @endphp
                          <form class="form" method="get">
                            <div class="col-md-6 input-group input-group-m">
                              <input type="text" name="searchks" class="form-control" id="searchks" value="{{ $searchval; }}" placeholder="Masukkan kata kunci pencarian">
                              <span class="input-group-append">
                                <button type="button" id="btn-search" class="btn btn-primary btn-flat"><i class="fas fa-search"></i></button>
                              </span>
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              <a id="print" class="btn btn-info" style="color: white;">
                                <i class="fas fa-download"></i> Unduh Excel
                              </a>
                            </div>
                          </form>
                          <br>
                          <div id="div_table_ks" style="display:none">
                            <table class="table table-bordered table-hover" id="tabel-kasi">
                                <thead style="text-align:center">                
                                    <tr>
                                      <th style="width: 10px">No</th>
                                      <th>No Agenda</th>
                                      <th>NPWP</th>
                                      <th>Nama Wajib Pajak</th>
                                      <th>Jenis Permohonan</th>
                                      <th>No Ketetapan</th>
                                      <th>PK Konseptor</th>
                                      <th>No Produk Hukum</th>
                                      <th>Tanggal Produk Hukum</th>
                                      <th>Status</th>
                                      <th>Keputusan</th>
                                      <th style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                          </div>
                          <div id="div_table_ks_all">
                            <table class="table table-bordered table-hover" id="tabel-kasi-all">
                                <thead style="text-align:center">                
                                    <tr>
                                      <th style="width: 10px">No</th>
                                      <th>No Agenda</th>
                                      <th>NPWP</th>
                                      <th>Nama Wajib Pajak</th>
                                      <th>Jenis Permohonan</th>
                                      <th>No Ketetapan</th>
                                      <th>PK Konseptor</th>
                                      <th>No Produk Hukum</th>
                                      <th>Tanggal Produk Hukum</th>
                                      <th>Status</th>
                                      <th>Keputusan</th>
                                      <th style="width: 5%"></th>
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
<script>
  $(document).ready(function () {
    $('#tabel-kasi-all').DataTable({
        "destroy" : true,
        "paging": true,
        "ordering": true,
        "searching": false,
        "responsive": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "ajax": "{{route('kasi.datatableKasi')}}",
        columnDefs: [
            {"targets": 0, "orderable": false},
            {"targets": 1, "name": 'no_agenda'},
            {"targets": 2, "name": 'npwp'},
            {"targets": 3, "name": 'nama_wajib_pajak'},
            {"targets": 4, "name": 'jenis_permohonan'},
            {"targets": 5, "name": 'no_ketetapan'},
            {"targets": 6, "name": 'pk_konseptor'},
            {"targets": 7, "name": 'no_produk_hukum'},
            {"targets": 8, "name": 'tgl_produk_hukum'},
            {"targets": 9, "name": 'status'},
            {"targets": 10, "name": 'hasil_keputusan'},
            {"targets": 11, "orderable": false},
        ],
        order: [[ 0, "DESC" ]],
    });
  });

  $(document).on('click',"#btn-search", function(){ 
    $("#div_table_ks_all").hide();                                  
    $("#tabel-kasi-all").css("display", "block"); 
    $("#div_table_ks").show();                                  
    $("#tabel-kasi").css("display", "block"); 
    var search = $('#searchks').val();
    $('#tabel-kasi').DataTable({
        "destroy" : true,
        "paging": true,
        "ordering": true,
        "searching": false,
        "responsive": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "ajax": "{{url('permohonan/kasi/searchKS?')}}searchks="+search,
        columnDefs: [
            {"targets": 0, "orderable": false},
            {"targets": 1, "name": 'no_agenda'},
            {"targets": 2, "name": 'npwp'},
            {"targets": 3, "name": 'nama_wajib_pajak'},
            {"targets": 4, "name": 'jenis_permohonan'},
            {"targets": 5, "name": 'no_ketetapan'},
            {"targets": 6, "name": 'pk_konseptor'},
            {"targets": 7, "name": 'no_produk_hukum'},
            {"targets": 8, "name": 'tgl_produk_hukum'},
            {"targets": 9, "name": 'status'},
            {"targets": 10, "name": 'hasil_keputusan'},
            {"targets": 11, "orderable": false},
        ],
        order: [[ 0, "DESC" ]],
    });
  });

  $(document).on('click',"#print", function(){
    var search = $('#searchks').val();
    window.location.href = "{{url('permohonan/kasi/printAll?')}}searchks="+search;
  });

  var search = $('#searchks').val();

  if(search != ''){
    $(document).ready(function () {
    //$(document).not('click',"#btn-search", function(){ 
      $("#div_table_ks_all").hide();                                  
      $("#tabel-kasi-all").css("display", "block"); 
      $("#div_table_ks").show();                                  
      $("#tabel-kasi").css("display", "block"); 
      var search = $('#searchks').val();
      $('#tabel-kasi').DataTable({
          "destroy" : true,
          "paging": true,
          "ordering": true,
          "searching": false,
          "responsive": true,
          "autoWidth": false,
          "processing": true,
          "serverSide": true,
          "ajax": "{{url('permohonan/kasi/searchKS?')}}searchks="+search,
          columnDefs: [
              {"targets": 0, "orderable": false},
              {"targets": 1, "name": 'no_agenda'},
              {"targets": 2, "name": 'npwp'},
              {"targets": 3, "name": 'nama_wajib_pajak'},
              {"targets": 4, "name": 'jenis_permohonan'},
              {"targets": 5, "name": 'no_ketetapan'},
              {"targets": 6, "name": 'pk_konseptor'},
              {"targets": 7, "name": 'no_produk_hukum'},
              {"targets": 8, "name": 'tgl_produk_hukum'},
              {"targets": 9, "name": 'status'},
              {"targets": 10, "name": 'hasil_keputusan'},
              {"targets": 11, "orderable": false},
          ],
          order: [[ 0, "DESC" ]],
      });
    });
  }

</script>