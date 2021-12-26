@section('title','Form Banding Gugatan')
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
              <h1 class="m-0 text-dark">Form Banding Gugatan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Banding Gugatan</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Banding Gugatan</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <form id="form-banding" data-toggle="validator" action="{{route('permohonan.storePemohon')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="majelis">Majelis</label>
                                    <input type="text" id="majelis" name="majelis" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="no_sengketa">Nomor Sengketa</label>
                                    <input type="text" id="no_sengketa" name="no_sengketa" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="ob_bg">Objek Banding/Gugatan</label>
                                    <input type="text" id="ob_bg" name="ob_bg" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="jenis_pajak">Jenis Pajak</label>
                                    <select class="form-control select2" name="jenis_pajak" required>
                                        <option selected disabled>Pilih Jenis Pajak</option>
                                        @foreach($dtPajak as $dt)
                                        <option value="{{$dt->pajak}}" >{{$dt->pajak}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tahun_pajak">Tahun Pajak</label>
                                    <input type="text" id="tahun_pajak" name="tahun_pajak" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <br><button type="submit" name="mode" class="btn btn-success float-left" value="add">Simpan</button>
                                </div>
                            </div>
                        </div> 
                    </form>
                </div>
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Proses Sidang</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <form id="form-sidang" data-toggle="validator" action="{{route('permohonan.storePemohon')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <label>Proses Persidangan</label>
                            <div class ="row">
                                <div class="form-group col-md-2">
                                    <label for="urt_sidang">Sidang ke</label>
                                    <input type="number" id="urt_sidang" name="urt_sidang" class="form-control" min="1" value="1">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="urt_sidang">Tanggal</label>
                                    <div class="input-group date" id="tgl_sidang" data-target-input="nearest">
                                        <input type="text" name="tgl_sidang" class="form-control datetimepicker-input" data-target="#tgl_sidang" required/>
                                        <div class="input-group-append" data-target="#tgl_sidang" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="status_sidang">Status</label>
                                    <br>
                                    <label class="form-check-label">Tunda</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input class="form-check-input" id="stts_tunda" class="status_sidang" name="status_sidang[]" value="Tunda" type="checkbox">
                                    <label class="form-check-label">Cukup</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input class="form-check-input" id="stts_cukup" class="status_sidang" name="status_sidang[]" value="Cukup" type="checkbox">
                                </div>
                            </div>
                            <div class ="row">
                                <div class="form-group col-md-2"></div>
                                <div class="form-group col-md-2">
                                    <label for="tgl_ucp_pts">Tanggal ucap putusan</label>
                                    <div class="input-group date" id="tgl_ucp_pts" data-target-input="nearest">
                                        <input type="text" name="tgl_ucp_pts" class="form-control datetimepicker-input" data-target="#tgl_ucp_pts" required/>
                                        <div class="input-group-append" data-target="#tgl_ucp_pts" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="amar_putusan">Amar Putusan</label>
                                    <select class="form-control select2" name="amar_putusan" required>
                                        <option selected disabled>Pilih Amar Putusan</option>
                                        @foreach($dtAmarPutusan as $dt)
                                        <option value="{{$dt->amar_putusan}}" >{{$dt->amar_putusan}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="nilai">Nilai</label>
                                    <input type="number" id="nilai" name="nilai" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="keterangan">Keterangan</label>
                                    <input type="text" id="keterangan" name="keterangan" class="form-control">
                                </div>
                            </div>
                            <div class ="row">
                                <div class="form-group col-md-2">
                                    <label for="pet_sidang">Petugas Sidang</label>
                                    <select class="form-control select2" name="pet_sidang" required>
                                        <option selected disabled>Pilih Petugas Sidang</option>
                                        @foreach($dtPtgSidang as $dt)
                                        <option value="{{$dt->nama_petugas}}" >{{$dt->nama_petugas}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="pel_eksekutor">Pelaksana Eksekutor</label>
                                    <select class="form-control select2" name="pel_eksekutor" required>
                                        <option selected disabled>Pilih Petugas Sidang</option>
                                        @foreach($dtPkEksekutor as $dt)
                                        <option value="{{$dt->pelaksana_eksekutor}}" >{{$dt->pelaksana_eksekutor}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <br><button type="submit" name="mode" class="btn btn-success float-left" value="add">Simpan</button>
                                </div>
                            </div>
                        </div> 
                    </form>
                </div>
            </div>
        </div>
        <script>
            console.log("AS");
            // stts_tunda
            $('#stts_tunda').change(function(){
                if($(this).prop('checked') === true){
                console.log("checked");
                }else{
                    console.log("unchecked");
                }
            });
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
    console.log("AA");
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
</script>
@endsection