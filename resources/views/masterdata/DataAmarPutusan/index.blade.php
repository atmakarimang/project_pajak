@section('title','Data Amar Putusan')
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
              <h1 class="m-0 text-dark">Data Amar Putusan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active">Data Amar Putusan</li>
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
                        <h3 class="card-title">Data Amar Putusan</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <!-- <i class="fas fa-minus"></i></button> -->
                        </div>
                    </div>
                    <form id="form-status" data-toggle="validator" action="{{route('amarputusan.storeAP')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="amar_putusan">Amar Putusan</label>
                                <input type="hidden" id="id_amar_putusan" name="id_amar_putusan" class="form-control">
                                <input type="text" id="amar_putusan" name="amar_putusan" class="form-control">
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
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">Browse Amar Keputusan</h3>
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
                                        <th>Amar Putusan</th>
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
    $('#table-ap').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "responsive": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "ajax": "{{route('amarputusan.ajaxDataAP')}}",
        columnDefs: [
            {"targets": 0, "orderable": false},
            {"targets": 1, "name": 'amar_putusan'},
            {"targets": 2, "orderable": false},
        ],
        order: [[ 0, "DESC" ]],
    });
    function editAP(id) {
        $.ajax({
            method: 'GET',
            url: "{{route('amarputusan.editAP')}}",
            data : {
                'id' : id
            },
            dataType: 'JSON',
            success: function(data) {
                $('#id_amar_putusan').val(data.id);
                $('#amar_putusan').val(data.amar_putusan);
                $('#button-submit-add1').css('display','none');
                $('#button-submit-edit1').css('display','block');
            },
            fail: function(notifHTML){
                alert("loh");
            }
        }); 
    }
    function buttonDeleteAP(data){
        window.location.href = data.getAttribute('data-link');
    }
</script>