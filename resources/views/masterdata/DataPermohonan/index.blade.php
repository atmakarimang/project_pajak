@section('title','Data Permohonan')
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
              <h1 class="m-0 text-dark">Data Permohonan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active">Data Permohonan</li>
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
                        <h3 class="card-title">Asal Permohonan</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <!-- <i class="fas fa-minus"></i></button> -->
                        </div>
                    </div>
                    <form id="form-permohonan" data-toggle="validator" action="{{route('permohonan.storePemohon')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="kode_pmh">Kode Permohonan</label>
                                <input type="hidden" id="id_pmh" name="id_pmh" value=""/>
                                <input type="text" id="kode_pmh" name="kode_pmh" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="pemohon">Pemohon</label>
                                <input type="text" id="pemohon" name="pemohon" class="form-control">
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
                        <h3 class="card-title">Jenis Permohonan</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <!-- <i class="fas fa-minus"></i></button> -->
                        </div>
                    </div>
                    <form id="form-jp" data-toggle="validator" action="{{route('permohonan.storeJP')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="jenis_permohonan">Jenis Permohonan</label>
                                <input type="hidden" id="id_jenis" name="id_jenis" class="form-control">
                                <input type="text" id="jenis_permohonan" name="jenis_permohonan" class="form-control">
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
                        <h3 class="card-title">Browse Asal Permohonan</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-0" id="asl_pemohon_datatable">
                        <div class="card-body">
                            <table class="table table-bordered table-hover" id="table-asl_pemohon">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Pemohon</th>
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
                            <h3 class="card-title">Browse Jenis Permohonan</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0" id="jp_datatable">
                            <div class="card-body">
                                <table class="table table-bordered table-hover" id="table-jp">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis Permohonan</th>
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
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Kategori Permohonan</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <!-- <i class="fas fa-minus"></i></button> -->
                        </div>
                    </div>
                    <form id="form-kat-permohonan" data-toggle="validator" action="{{route('permohonan.storeKP')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="kat_pemohon">Kategori Pemohon</label>
                                <input type="hidden" id="id_kp" name="id_kp" class="form-control">
                                <input type="text" id="kat_pemohon" name="kat_pemohon" class="form-control">
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" id="button-submit-add3" name="mode" class="btn btn-secondary" value="add">Add</button>
                            <button type="submit" id="button-submit-edit3" class="btn btn-secondary" name="mode" value="edit" style="display: none">Update</button>
                        </div>  
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-secondary collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">Browse Kategori Permohonan</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-0" id="kp_datatable">
                        <div class="card-body">
                            <table class="table table-bordered table-hover" id="table-kp">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori Permohonan</th>
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
    $('#table-asl_pemohon').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "responsive": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "ajax": "{{route('permohonan.ajaxDataPemohon')}}",
        columnDefs: [
        {"targets": 0, "orderable": false},
        {"targets": 1, "name": 'id'},
        {"targets": 2, "name": 'pemohon'},
        {"targets": 3, "orderable": false},
        ],
        order: [[ 0, "DESC" ]],
    });
    function editPemohon(id) {
        $.ajax({
            method: 'GET',
            url: "{{route('permohonan.editPemohon')}}",
            data : {
                'id' : id
            },
            dataType: 'JSON',
            success: function(data) {
                $('#kode_pmh').attr('readonly','readonly');
                $('#id_pmh').val(data.id);
                $('#kode_pmh').val(data.id);
                $('#pemohon').val(data.pemohon);
                $('#button-submit-add1').css('display','none');
                $('#button-submit-edit1').css('display','block');
            },
            fail: function(notifHTML){
                alert("loh");
            }
        }); 
    }
    function buttonDeletePmh(data){
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
    $('#table-jp').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "responsive": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "ajax": "{{route('permohonan.ajaxDataJP')}}",
        columnDefs: [
            {"targets": 0, "orderable": false},
            {"targets": 1, "name": 'jenis_permohonan'},
            {"targets": 2, "orderable": false},
        ],
        order: [[ 0, "DESC" ]],
    });
    function editJP(id) {
        $.ajax({
            method: 'GET',
            url: "{{route('permohonan.editJP')}}",
            data : {
                'id' : id
            },
            dataType: 'JSON',
            success: function(data) {
                $('#id_jenis').val(data.id);
                $('#jenis_permohonan').val(data.jenis_permohonan);
                $('#button-submit-add2').css('display','none');
                $('#button-submit-edit2').css('display','block');
            },
            fail: function(notifHTML){
                alert("loh");
            }
        }); 
    }
    function buttonDeleteJP(data){
        window.location.href = data.getAttribute('data-link');
    }
    $('#table-kp').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "responsive": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "ajax": "{{route('permohonan.ajaxDataKP')}}",
        columnDefs: [
            {"targets": 0, "orderable": false},
            {"targets": 1, "name": 'kat_permohonan'},
            {"targets": 2, "orderable": false},
        ],
        order: [[ 0, "DESC" ]],
    });
    function editKP(id) {
        $.ajax({
            method: 'GET',
            url: "{{route('permohonan.editKP')}}",
            data : {
                'id' : id
            },
            dataType: 'JSON',
            success: function(data) {
                $('#id_kp').val(data.id);
                $('#kat_pemohon').val(data.kat_permohonan);
                $('#button-submit-add3').css('display','none');
                $('#button-submit-edit3').css('display','block');
            },
            fail: function(notifHTML){
                alert("loh");
            }
        }); 
    }
    function buttonDeleteKP(data){
        window.location.href = data.getAttribute('data-link');
    }
</script>