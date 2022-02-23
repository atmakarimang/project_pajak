@section('title','Data Pajak')
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
              <h1 class="m-0 text-dark">Data Pajak</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active">Data Pajak</li>
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
                        <h3 class="card-title">Pajak</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <!-- <i class="fas fa-minus"></i></button> -->
                        </div>
                    </div>
                    <form id="form-permohonan" data-toggle="validator" action="{{route('pajak.storePajak')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="pajak">Jenis Pajak</label>
                                <input type="hidden" id="id_pj" name="id_pj" class="form-control">
                                <input type="text" id="pajak" name="pajak" class="form-control">
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
                        <h3 class="card-title">Ketetapan</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <!-- <i class="fas fa-minus"></i></button> -->
                        </div>
                    </div>
                    <form id="form-jp" data-toggle="validator" action="{{route('pajak.storeJK')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="jenis_ketetapan">Jenis Ketetapan</label>
                                <input type="hidden" id="id_jk" name="id_jk" class="form-control">
                                <input type="text" id="jenis_ketetapan" name="jenis_ketetapan" class="form-control">
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
                        <h3 class="card-title">Browse Pajak</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-0" id="ap_datatable">
                        <div class="card-body">
                            <table class="table table-bordered table-hover" id="table-ap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Pajak</th>
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
                            <h3 class="card-title">Browse Ketetapan</h3>
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
                                            <th>Ketetapan</th>
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
    $('#table-ap').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "responsive": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "ajax": "{{route('pajak.ajaxDataPajak')}}",
        columnDefs: [
            {"targets": 0, "orderable": false},
            {"targets": 1, "name": 'pajak'},
            {"targets": 2, "orderable": false},
        ],
        order: [[ 0, "DESC" ]],
    });
    function editPj(id) {
        $.ajax({
            method: 'GET',
            url: "{{route('pajak.editPj')}}",
            data : {
                'id' : id
            },
            dataType: 'JSON',
            success: function(data) {
                $('#id_pj').val(data.id);
                $('#pajak').val(data.pajak);
                $('#button-submit-add1').css('display','none');
                $('#button-submit-edit1').css('display','block');
            },
            fail: function(notifHTML){
                alert("loh");
            }
        }); 
    }
    function buttonDeletePj(data){
        window.location.href = data.getAttribute('data-link');
    }

    $('#table-jp').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "responsive": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "ajax": "{{route('pajak.ajaxDataJK')}}",
        columnDefs: [
            {"targets": 0, "orderable": false},
            {"targets": 1, "name": 'jenis_ketetapan'},
            {"targets": 2, "orderable": false},
        ],
        order: [[ 0, "DESC" ]],
    });
    function editJk(id) {
        $.ajax({
            method: 'GET',
            url: "{{route('pajak.editJk')}}",
            data : {
                'id' : id
            },
            dataType: 'JSON',
            success: function(data) {
                $('#id_jk').val(data.id);
                $('#jenis_ketetapan').val(data.jenis_ketetapan);
                $('#button-submit-add2').css('display','none');
                $('#button-submit-edit2').css('display','block');
            },
            fail: function(notifHTML){
                alert("loh");
            }
        }); 
    }
    function buttonDeleteJk(data){
        window.location.href = data.getAttribute('data-link');
    }
</script>