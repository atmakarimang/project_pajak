@section('title','Database Banding & Gugatan')
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
              <h1 class="m-0 text-dark">Database Banding & Gugatan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active">Database Banding & Gugatan</li>
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
                          <a href="{{route("bandinggugatan.print")}}"  id="print" class="btn btn-info">
                            <i class="fas fa-download"></i> Unduh Excel
                          </a>
                          <br><br>
                            <table class="table table-bordered table-hover" id="tabel-bg">
                                <thead style="text-align:center">                
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Majelis</th>
                                        <th>No Sengketa</th>
                                        <th>Nama Wajib Pajak</th>
                                        <th>Objek Banding Gugatan</th>
                                        <th>Petugas Sidang</th>
                                        <th>Pelaksana Eksekutor</th>
                                        <th>Status</th>
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
  $('#tabel-bg').DataTable({
    "bDestroy": true,
    "paging": true,
    "ordering": true,
    "searching": true,
    "responsive": true,
    "autoWidth": false,
    "processing": true,
    "serverSide": true,
    "ajax": "{{route('bandinggugatan.datatable')}}",
    columnDefs: [
      {"targets": 0, "orderable": false},
      {"targets": 1, "name": 'majelis'},
      {"targets": 2, "name": 'no_sengketa'},
      {"targets": 3, "name": 'nama_wajib_pajak'},
      {"targets": 4, "name": 'objek_bg'},
      {"targets": 5, "name": 'petugas_sidang'},
      {"targets": 6, "name": 'petugas_eksekutor'},
      {"targets": 7, "name": 'status'},
      {"targets": 8, "orderable": false},
    ],
    order: [[ 0, "DESC" ]],
  });
            
  function buttonDelete(data){
    window.location.href = data.getAttribute('data-link');
  }
</script>