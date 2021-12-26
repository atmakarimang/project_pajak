@section('title','Pengaturan Akun')
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
              <h1 class="m-0 text-dark">Pengaturan Akun</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Pengaturan Akun</li>
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
                        <h3 class="card-title">User</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <form id="form-set-akun" data-toggle="validator" action="{{route('daftarakun.create')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="input-group mb-3">
                                @if(!empty($usernya->user_id))
                                    <input type="text" name ="user_id" class="form-control" value="{{$usernya->user_id}}" required readonly>
                                @else
                                    <input type="text" name ="user_id" class="form-control" placeholder="User ID" required>
                                @endif
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                @if(!empty($usernya))
                                    <input type="text" name ="nama" class="form-control" value="{{$usernya->nama}}" required>
                                @else
                                    <input type="text" name ="nama" class="form-control" placeholder="Nama" required>
                                @endif
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <select class="form-control select2" name="jabatan" required>
                                    <option selected disabled>Pilih Jabatan</option>
                                    <option value="Pelaksana" {{ ($usernya->jabatan=='Pelaksana') ? "selected" : "" }}>Pelaksana</option>
                                    <option value="Kepala Bidang" {{ ($usernya->jabatan=='Kepala Bidang') ? "selected" : "" }}>Kepala Bidang</option>
                                    <option value="Kepala Seksi" {{ ($usernya->jabatan=='Kepala Seksi') ? "selected" : "" }}>Kepala Seksi</option>
                                    <option value="Penelaah Keberatan" {{ ($usernya->jabatan=='Penelaah Keberatan') ? "selected" : "" }}>Penelaah Keberatan</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <select class="form-control select2" name="peran" required>
                                    <option selected disabled>Pilih Peran</option>
                                    <option value="Forecaster" {{ ($usernya->peran=='Forecaster') ? "selected" : "" }}>Forecaster</option>
                                    <option value="Eksekutor" {{ ($usernya->peran=='Eksekutor') ? "selected" : "" }}>Eksekutor</option>
                                </select>
                            </div>
                            @if($mode=='edit')
                            <div class="input-group mb-3">
                                <input type="password" name="password_lama" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    placeholder="Password Lama">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                @if($errors->has('password'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" name="password"
                                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    placeholder="Password Baru">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                @if($errors->has('password'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </div>
                                @endif
                            </div>
                            @else
                            <div class="input-group mb-3">
                                <input type="password" name="password"
                                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    placeholder="Password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                @if($errors->has('password'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </div>
                                @endif
                            </div>
                            @endif
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <input type="hidden" name ="form" value="formsetakun">
                            @if($mode=='edit')
                                <button type="submit" name="mode" class="btn btn-info" value="edit">Update</button>
                            @else
                                <button type="submit" name="mode" class="btn btn-info" value="add">Simpan</button>
                            @endif
                        </div>   
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Browse User</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-0" id="user_datatable">
                        <table class="table" id="table-user">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>User ID</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Peran</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataUser as $key => $dt)
                                    <tr>
                                        <td>{{$page++}}</td>
                                        <td>{{$dt->user_id}}</td>
                                        <td>{{$dt->nama}}</td>
                                        <td>{{$dt->jabatan}}</td>
                                        <td>{{$dt->peran}}</td>
                                        <td>
                                            <center>
                                                <a href="{{url('/pengaturan-akun?mode=edit&usrid=')}}{{base64_encode($dt->user_id)}}">
                                                    <button data-toggle="tooltip" data-original-title="Edit" type="button" class="btn btn-xs btn-primary btn-circle"><i class="fas fa-pencil-alt"></i></button>
                                                </a>
                                                <a href="{{url('/pengaturan-akun/delete',base64_encode($dt->user_id))}}">
                                                    <button data-toggle="tooltip" data-original-title="Delete" type="button" class="btn btn-xs btn-default btn-circle"><i class="fas fa-trash"></i></button>
                                                </a>
                                                <!-- <button onclick="buttonDelete(this)" data-link="{{url('/pengaturan-akun/delete',base64_encode($dt->user_id))}}" data-toggle="tooltip" data-original-title="Delete" type="button" class="btn btn-xs btn-default btn-circle"><i class="fas fa-trash"></i></button> -->
                                            </center>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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