@section('title','Form Data Seksi')
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
              <h1 class="m-0 text-dark">Form Data Seksi</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Data Seksi</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Kepala Seksi</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <form id="form-anggota-seksi" data-toggle="validator" action="{{route('seksi.storeKepSeksi')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nama_kepala">Nama</label>
                                <input type="text" id="nama_kepala" name="nama_kepala" class="form-control">
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            @if($mode=='edit')
                                <button type="submit" name="mode" class="btn btn-info" value="edit">Update</button>
                            @else
                                <button type="submit" name="mode" class="btn btn-info" value="add">Add</button>
                            @endif
                        </div>   
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Seksi Konseptor</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <form id="form-seksi-konseptor" data-toggle="validator" action="{{route('seksi.storeKonseptor')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="seksi_konseptor">Seksi Konseptor</label>
                                <input type="text" id="seksi_konseptor" name="seksi_konseptor" class="form-control">
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            @if($mode=='edit')
                                <button type="submit" name="mode" class="btn btn-info" value="edit">Update</button>
                            @else
                                <button type="submit" name="mode" class="btn btn-info" value="add">Add</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Browse Kepala Seksi</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-0" id="kepsek_datatable">
                        <table class="table" id="table-kepsek">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <form id="form-seksi-konseptor" data-toggle="validator" action="" method="POST" enctype="multipart/form-data">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Browse Seksi Konseptor</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0" id="konseptor_datatable">
                            <table class="table" id="table-konseptor">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Seksi Konseptor</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
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
<script>
    jQuery(document).ready(function(){
        console.log("AA");
        if($('#table-kepsek tbody .dataTables_empty').length){
            $('#table-kepsek, #kepsek_datatable').hide();
        }
        $('#table-kepsek').DataTable({
            "processing": true,
            "responsive" : true,
            "serverSide": true,
            "bDestroy": true,
            "orderable":false,
            ajax:{
                url : "{{route('seksi.ajaxDataKepsek')}}",
                type : "POST",
                data : function(d){
                    console.log(d);
                    d._token = "{{ csrf_token() }}";
                }
            },
            columnDefs: [
                {"targets": 0, "orderable": false},
                {"targets": 1, "name": 'nama_anggota'},
                {"targets": 2, "orderable": false},            
            ],
            order: [[ 0, "DESC" ]],
        });
    })
<script>
@endsection