@section('title','Banding & Gugatan')
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
              <h1 class="m-0 text-dark">Banding & Gugatan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active">Banding & Gugatan</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

    <!-- Main content -->
    @if($user->peran == 'Eksekutor')
    <div class="col-md-12">
        <center>
            <a href="{{route('bandinggugatan.browse')}}">
                <button class="btn btn-success"><i class="fa fa-search"></i> Database Banding & Gugatan</button>
            </a>
        </center> 
    </div>  
    @else
    <div class="col-md-12">
        <a href="{{route('bandinggugatan.browse')}}">
            <button class="btn btn-success"><i class="fa fa-search"></i> Database Banding & Gugatan</button>
        </a>
    </div>  
    @endif
    <br><br>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <form id="form-banding" data-toggle="validator" action="{{route('bandinggugatan.store')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card card-primary card-bg">
                        <div class="card-header">
                            <h3 class="card-title">Banding & Gugatan</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4 form_forecester">
                                        <label for="majelis">Majelis</label>
                                        @if(!empty($dtBG->majelis))
                                            <input type="hidden" id="id_bg" name="id_bg" class="form-control" value="{{$dtBG->id_bg}}">
                                            <input type="text" id="majelis" name="majelis" class="form-control" value="{{$dtBG->majelis}}">
                                        @else
                                            <input type="text" id="majelis" name="majelis" class="form-control">
                                        @endif
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="no_sengketa">Nomor Sengketa</label>
                                        @if(!empty($dtBG->no_sengketa))
                                            <input type="text" id="no_sengketa" name="no_sengketa" class="form-control" value="{{$dtBG->no_sengketa}}">
                                        @else
                                            <input type="text" id="no_sengketa" name="no_sengketa" class="form-control">
                                        @endif
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="nama_wjb_pjk">Nama Wajib Pajak</label>
                                        @if(!empty($dtBG->nama_wajib_pajak))
                                            <input type="text" id="nama_wjb_pjk" name="nama_wjb_pjk" class="form-control" value="{{$dtBG->nama_wajib_pajak}}">
                                        @else
                                            <input type="text" id="nama_wjb_pjk" name="nama_wjb_pjk" class="form-control">
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="ob_bg">Objek Banding/Gugatan</label>
                                        @if(!empty($dtBG->objek_bg))
                                            <input type="text" id="ob_bg" name="ob_bg" class="form-control" value="{{$dtBG->objek_bg}}">
                                        @else
                                            <input type="text" id="ob_bg" name="ob_bg" class="form-control">
                                        @endif
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="jenis_pajak">Jenis Pajak</label>
                                        <select class="form-control select2bs4" id="jenis_pajak" name="jenis_pajak" required>
                                            <option selected disabled>Pilih Jenis Pajak</option>
                                            @foreach($dtPajak as $dt)
                                            <option value="{{$dt->pajak}}" {{$dtBG->jenis_pajak == $dt->pajak ? "selected" : ''}}>{{$dt->pajak}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="tahun_pajak">Tahun Pajak</label>
                                        @if(!empty($dtBG->tahun_pajak))
                                            <input type="text" id="tahun_pajak" name="tahun_pajak" class="form-control year" value="{{$dtBG->tahun_pajak}}">
                                        @else
                                            <input type="text" id="tahun_pajak" name="tahun_pajak" class="form-control year">
                                        @endif
                                    </div>
                                    <!-- <div class="form-group col-md-4">
                                        <br><button type="submit" name="mode" class="btn btn-success float-left" value="add">Simpan</button>
                                    </div> -->
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="no_bidang">Nomor Objek Banding/Gugatan</label>
                                        @if(!empty($dtBG->no_bidang))
                                            <input type="text" id="no_bidang" name="no_bidang" class="form-control" value="{{$dtBG->objek_bg}}">
                                        @else
                                            <input type="text" id="no_bidang" name="no_bidang" class="form-control">
                                        @endif
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="tgl_objek">Tanggal Objek</label>
                                        <div class='input-group'>
                                            @php
                                                $tgl = date('d-m-Y', strtotime($dtBG->tgl_objek));
                                            @endphp
                                            <input type="text" class="form-control datetimepicker-input tgl_objek datenya" name="tgl_objek" placeholder="Tanggal Objek" value="{{!empty($dtBG->tgl_objek) ? $tgl : ''}}"/>
                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="card card-success card-proses-sidang">
                        <div class="card-header">
                            <h3 class="card-title">Proses Sidang</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                            <div class="card-body">
                                <label>Proses Persidangan</label>
                                <div class ="row rowpetugas">
                                    @if($user->peran == 'Eksekutor')
                                    <div class="form-group col-md-2">
                                        <label for="pet_sidang">Petugas Sidang</label>
                                        @php
                                            $ex = explode(",",$dtBG->petugas_sidang);
                                        @endphp
                                        <select class="form-control select2bs4" multiple="multiple" name="pet_sidang[]" required>
                                            @foreach($dtPtgSidang as $dt)
                                            <option value="{{$dt->nama_petugas}}" {{ (in_array($dt->nama_petugas, $ex)) ? "selected" : "" }}>{{$dt->nama_petugas}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="pel_eksekutor">Pelaksana Eksekutor</label>
                                        @php
                                            $ex = explode(",",$dtBG->pelaksana_eksekutor);
                                        @endphp
                                        <select class="form-control select2bs4" multiple="multiple" name="pel_eksekutor[]" required>
                                            @foreach($dtPkEksekutor as $dt)
                                            <option value="{{$dt->pelaksana_eksekutor}}" {{ (in_array($dt->pelaksana_eksekutor, $ex)) ? "selected" : "" }}>{{$dt->pelaksana_eksekutor}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                </div>
                                @if(count($dtBG->BGchild) > 0)
                                @foreach($dtBG->BGchild as $key => $dt)
                                <div class ="row rowsidang sidangke-1">
                                    <div class="form-group col-md-2">
                                        <label for="urt_sidang">Sidang ke</label>
                                        <input type="number" id="urt_sidang" name="urt_sidang[]" class="form-control urt_sidang" min="1" value="{{$dt->sidang_ke}}" {{$dt->status_sidang =="Tunda" ? "readonly" : ''}}>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="tgl_sidang">Tanggal</label>
                                        <div class='input-group'>
                                            @php
                                                $tgl = date('d-m-Y', strtotime($dt->tanggal_sidang));
                                            @endphp
                                            <input type="text" class="form-control datetimepicker-input tgl_sidang datenya" name="tgl_sidang[]" placeholder="Tanggal Sidang" value="{{$tgl}}" required {{$dt->status_sidang =="Tunda" ? "readonly" : ''}}/>
                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="status_sidang">Status</label>
                                        <br>
                                        <label class="form-check-label">Tunda</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input class="form-check-input stts_tunda" name="status_sidang[]" value="Tunda" type="checkbox" onchange="handleChange({{$key+1}})" {{$dt->status_sidang =="Tunda" ? "checked" : ''}}>
                                        <label class="form-check-label">Cukup</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input class="form-check-input stts_cukup" name="status_sidang[]" value="Cukup" type="checkbox" onchange="handleChange({{$key+1}})" {{$dt->status_sidang =="Cukup" ? "checked" : ''}}>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <div class ="row rowsidang sidangke-1">
                                    <div class="form-group col-md-2">
                                        <label for="urt_sidang">Sidang ke</label>
                                        <input type="number" id="urt_sidang" name="urt_sidang[]" class="form-control urt_sidang-1" min="1" value="1">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="urt_sidang">Tanggal</label>
                                        <div class='input-group'>
                                            <input type="text" class="form-control datetimepicker-input datenya tgl_sidang-1" name="tgl_sidang[]" placeholder="Tanggal Sidang" required />
                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="status_sidang">Status</label>
                                        <br>
                                        <label class="form-check-label">Tunda</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input class="form-check-input stts_tunda" name="status_sidang[]" value="Tunda" type="checkbox" onchange="handleChange(1)">
                                        <label class="form-check-label">Cukup</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input class="form-check-input stts_cukup" name="status_sidang[]" value="Cukup" type="checkbox" onchange="handleChange(1)">
                                    </div>
                                </div>
                                @endif
                                <div class ="row row-amar-putusan" style="display:none">
                                    <div class="form-group col-md-2"></div>
                                    <div class="form-group col-md-2">
                                        <label for="tgl_ucp_pts">Tanggal ucap putusan</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datetimepicker-input datenya" name="tgl_ucp_pts" placeholder="Tanggal Ucap Putusan" />
                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="amar_putusan">Amar Putusan</label>
                                        <select class="form-control select2bs4" name="amar_putusan" >
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
                                        <textarea id="keterangan" name="keterangan" class="form-control" rows="3" placeholder=""></textarea>
                                    </div>
                                </div>  
                                <div class="form-group col-md-2">
                                    @if($mode=='edit')
                                    <br><button type="submit" name="mode" class="btn btn-success float-left" value="edit">Update</button>
                                    @else
                                    <br><button type="submit" name="mode" class="btn btn-success float-left" value="add">Add</button>
                                    &nbsp &nbsp<button type="reset" class="btn btn-default ">Reset</button>
                                    @endif
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
  </div>
  <!-- ./wrapper -->
  @include('layouts.dashboard.javascript')
</body>

</html>
<script src="{{asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<!-- InputMask --> 
<script src="{{asset('assets/AdminLTE/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/AdminLTE/plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/js/bootstrap-datetimepicker.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('assets/AdminLTE/plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    jQuery(document).ready(function() {
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });
        $('.datenya').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
        });
        $('.year').datepicker({
            minViewMode: 2,
            format: 'yyyy'
        });
        var cekuser = "{{$user->peran}}";
        var mode = "{{$mode}}";
        if(mode != 'edit'){
            if (cekuser == 'Eksekutor'){
                $('.card-bg, .card-proses-sidang').css('display','none');
            }else if(cekuser == 'Forecaster'){
                $('.card-bg, .card-proses-sidang').css('display','block');
            }
        }
        if (cekuser == 'Eksekutor'){
            $('#majelis, #no_sengketa, #nama_wjb_pjk, #ob_bg, #tahun_pajak ').prop('readonly',true);
            $('#jenis_pajak').prop('disabled',true);
        }else if(cekuser == 'Forecaster'){
            $('#majelis, #no_sengketa, #nama_wjb_pjk, #ob_bg, #jenis_pajak, #tahun_pajak ').prop('readonly',false);
            $('#jenis_pajak').prop('disabled',false);
        }
    });
    
    // stts_tunda
    $(document).on('change','.stts_tunda', function () {
        if($(this).prop('checked') === true){
            var count_sidang = $('div.rowsidang').length;
            var set_idx = count_sidang+1;
            $('.row-amar-putusan:last').before(
                '<div class ="row rowsidang sidangke-'+set_idx+'">'+
                    '<div class="form-group col-md-2">'+
                        '<label for="urt_sidang">Sidang ke</label>'+
                        '<input type="number" id="urt_sidang" name="urt_sidang[]" class="form-control urt_sidang-'+set_idx+'" min="1" value="'+set_idx+'">'+
                    '</div>'+
                    '<div class="form-group col-md-2">'+
                        '<label for="tgl_sidang">Tanggal</label>'+
                        '<div class="input-group">'+
                            '<input type="text" class="form-control datetimepicker-input tgl_sidang datenya tgl_sidang-'+set_idx+'" name="tgl_sidang[]" placeholder="Tanggal Sidang" required/>'+
                            '<div class="input-group-append" data-toggle="datetimepicker">'+
                                '<div class="input-group-text"><i class="fa fa-calendar"></i></div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="form-group col-md-2">'+
                        '<label for="status_sidang">Status</label>'+
                        '<br>'+
                        '<label class="form-check-label">Tunda</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
                        '<input class="form-check-input stts_tunda" name="status_sidang[]" value="Tunda" type="checkbox" onchange="handleChange('+set_idx+')">'+
                        '<label class="form-check-label">Cukup</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
                        '<input class="form-check-input stts_cukup" name="status_sidang[]" value="Cukup" type="checkbox">'+
                        '&nbsp;&nbsp;&nbsp;'+
                        '<i class="fas fa-times deleterow" onClick=deleterow('+set_idx+')></i>'+
                    '</div>'+
                '</div>'
            );
        }
    });
    function deleterow(index){
        $(".sidangke-"+index).remove();
        var count_sidang = $('div.rowsidang').length;
        console.log(count_sidang);
    }
    function handleChange(index){
        console.log(index);
        console.log("ABC");
        if($('.stts_tunda').prop('checked') === true){
            $(".urt_sidang-"+index).prop('readonly',true);
            $(".tgl_sidang-"+index).prop('readonly',true);
        }else{
            console.log("unchecked");
            $(".urt_sidang-"+index).prop('readonly',false);
            $(".tgl_sidang-"+index).prop('readonly',false);
        }
        $('.datenya').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
        });
    }
    $(document).on('click','.datenya', function () {
            console.log("tgl");
            $(this).datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });
        });
    $(document).on('change','.stts_cukup', function () {
        if($(this).prop('checked') === true){
            // $('.row-amar-putusan').css('display','block');
            $('.row-amar-putusan').show();
            $('.stts_tunda').attr('disabled',true);
            $(this).closest('tr').find(".urt_sidang").attr('readonly',true);
            $(this).closest('tr').find(".tgl_sidang").attr('readonly',true);
        }else{
            $('.row-amar-putusan').hide();
            $('.stts_tunda').attr('disabled',false);
            $(this).closest('tr').find(".urt_sidang").attr('readonly',false);
            $(this).closest('tr').find(".tgl_sidang").attr('readonly',false);
        }
    });
</script>