@section('title','Data Seksi')
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
              <h1 class="m-0 text-dark">Data Seksi</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
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
                            <!-- <i class="fas fa-minus"></i></button> -->
                        </div>
                    </div>
                    <form id="form-anggota-seksi" data-toggle="validator" action="{{route('seksi.storeKepSeksi')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nama_kepala">Nama</label>
                                <input type="hidden" id="id_ks" name="id_ks" class="form-control">
                                <input type="text" id="nama_kepala" name="nama_kepala" class="form-control">
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" id="button-submit-add1" name="mode" class="btn btn-primary" value="add">Add</button>
                            <button type="submit" id="button-submit-edit1" class="btn btn-primary" name="mode" value="edit" style="display: none">Update</button>
                        </div>   
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-info">
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
                                <input type="hidden" id="id_konseptor" name="id_konseptor" class="form-control">
                                <input type="text" id="seksi_konseptor" name="seksi_konseptor" class="form-control">
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" id="button-submit-add2" name="mode" class="btn btn-info" value="add">Add</button>
                            <button type="submit" id="button-submit-edit2" class="btn btn-info" name="mode" value="edit" style="display: none">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">Browse Kepala Seksi</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-0" id="kepsek_datatable">
                        <div class="card-body">
                            <table class="table table-bordered table-hover" id="table-kepsek">
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
            </div>
            <div class="col-md-6">
                <form id="form-seksi-konseptor" data-toggle="validator" action="" method="POST" enctype="multipart/form-data">
                    <div class="card card-info collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">Browse Seksi Konseptor</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0" id="konseptor_datatable">
                            <div class="card-body">
                                <table class="table table-bordered table-hover" id="table-konseptor">
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
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Penelaah Keberatan</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <!-- <i class="fas fa-minus"></i></button> -->
                        </div>
                    </div>
                    <form id="form-pk" data-toggle="validator" action="{{route('seksi.storePK')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nama_kepala">Nama</label>
                                <input type="hidden" id="id_pk" name="id_pk" class="form-control">
                                <input type="text" id="nama_pk" name="nama_pk" class="form-control">
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" id="button-submit-add3" name="mode" class="btn btn-success" value="add">Add</button>
                            <button type="submit" id="button-submit-edit3" class="btn btn-success" name="mode" value="edit" style="display: none">Update</button>
                        </div>   
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <form id="form-seksi-konseptor" data-toggle="validator" action="" method="POST" enctype="multipart/form-data">
                    <div class="card card-success collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">Browse Penelaah Keberatan</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0" id="konseptor_datatable">
                            <div class="card-body">
                                <table class="table table-bordered table-hover" id="table-pk">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Penelaah Keberatan</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
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
<script>
    $('#table-kepsek').DataTable({
        "destroy" : true,
        "paging": true,
        "ordering": true,
        "searching": true,
        "responsive": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "ajax": "{{route('seksi.ajaxDataKepsek')}}",
        columnDefs: [
            {"targets": 0, "orderable": false},
            {"targets": 1, "name": 'nama_anggota'},
            {"targets": 2, "orderable": false},
        ],
        order: [[ 0, "DESC" ]],
    });
    function editKS(id) {
        $.ajax({
            method: 'GET',
            url: "{{route('seksi.editKS')}}",
            data : {
                'id' : id
            },
            dataType: 'JSON',
            success: function(data) {
                $('#id_ks').val(data.id);
                $('#nama_kepala').val(data.nama_anggota);
                $('#button-submit-add1').css('display','none');
                $('#button-submit-edit1').css('display','block');
            },
            fail: function(notifHTML){
                alert("loh");
            }
        }); 
    }
    function buttonDeleteKS(data){
        window.location.href = data.getAttribute('data-link');
    }
    $('#table-konseptor').DataTable({
        "destroy" : true,
        "paging": true,
        "ordering": true,
        "searching": true,
        "responsive": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "ajax": "{{route('seksi.ajaxDataKS')}}",
        columnDefs: [
            {"targets": 0, "orderable": false},
            {"targets": 1, "name": 'seksi_konseptor'},
            {"targets": 2, "orderable": false},
        ],
        order: [[ 0, "DESC" ]],
    });
    function editKonseptor(id) {
        $.ajax({
            method: 'GET',
            url: "{{route('seksi.editKonseptor')}}",
            data : {
                'id' : id
            },
            dataType: 'JSON',
            success: function(data) {
                $('#id_konseptor').val(data.id);
                $('#seksi_konseptor').val(data.seksi_konseptor);
                $('#button-submit-add2').css('display','none');
                $('#button-submit-edit2').css('display','block');
            },
            fail: function(notifHTML){
                alert("loh");
            }
        }); 
    }
    function buttonDeleteKonseptor(data){
        window.location.href = data.getAttribute('data-link');
    }
    $('#table-pk').DataTable({
        "destroy" : true,
        "paging": true,
        "ordering": true,
        "searching": true,
        "responsive": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "ajax": "{{route('seksi.ajaxDataPK')}}",
        columnDefs: [
            {"targets": 0, "orderable": false},
            {"targets": 1, "name": 'nama_penelaah'},
            {"targets": 2, "orderable": false},
        ],
        order: [[ 0, "DESC" ]],
    });
    function editPK(id) {
        $.ajax({
            method: 'GET',
            url: "{{route('seksi.editPK')}}",
            data : {
                'id' : id
            },
            dataType: 'JSON',
            success: function(data) {
                $('#id_pk').val(data.id);
                $('#nama_pk').val(data.nama_penelaah);
                $('#button-submit-add3').css('display','none');
                $('#button-submit-edit3').css('display','block');
            },
            fail: function(notifHTML){
                alert("loh");
            }
        }); 
    }
    function buttonDeletePK(data){
        window.location.href = data.getAttribute('data-link');
    }
</script>