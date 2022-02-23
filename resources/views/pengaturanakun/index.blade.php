@section('title','Pengaturan Akun')
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
  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('assets/AdminLTE/plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
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
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" accept=".jpeg,.jpg,.png" class="custom-file-input" id="ft_profil" name="ft_profil" value="{{ !empty($usernya->ft_profil) ? $usernya->ft_profil : '' }}">
                                        <label class="custom-file-label" for="ft_profil">{{ !empty($usernya->ft_profil) ? $usernya->ft_profil : 'Pilih Foto Profil' }}</label>
                                    </div>
                                     @if(empty($usernya->ft_profil))
                                        <div class="input-group-append">
                                            <span class="input-group-text" type="button" id="preview-image">Preview</span>
                                        </div>
                                    @endif
                                </div>
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
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" accept=".jpeg,.jpg,.png" class="custom-file-input" id="ft_profil" name="ft_profil" value="{{ !empty($usernya->ft_profil) ? $usernya->ft_profil : '' }}">
                                        <label class="custom-file-label" for="ft_profil">{{ !empty($usernya->ft_profil) ? $usernya->ft_profil : 'Pilih Foto Profil' }}</label>
                                    </div>
                                     @if(empty($dataPB->no_resi))
                                        <div class="input-group-append">
                                            <span class="input-group-text" type="button" id="preview-image">Preview</span>
                                        </div>
                                    @endif
                                </div>
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
                        <div class="card-body">
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
<!-- InputMask -->
<script src="{{asset('assets/AdminLTE/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/AdminLTE/plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- bs-custom-file-input -->
<script src="{{asset('assets/AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script>
    jQuery(document).ready(function(){
        if($('#table-user tbody .dataTables_empty').length){
            $('#table-user, #user_datatable').hide();
        }
        $('#table-user').DataTable({
            "bDestroy": true,
            "paging": true,
            "ordering": true,
            "searching": true,
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('set.datatableAkun')}}",
                columnDefs: [
                    {"targets": 0, "orderable": false},
                    {"targets": 1, "name": 'user_id'},
                    {"targets": 2, "name": 'nama'},
                    {"targets": 3, "name": 'jabatan'},
                    {"targets": 4, "name": 'peran'},
                    {"targets": 5, "orderable": false},
            ],
            order: [[ 0, "DESC" ]],
        });
    })
    function buttonDelete(data){
        window.location.href = data.getAttribute('data-link');
    }
    //Preview image
    var dataTarget = '';
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                dataTarget = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    function debugBase64(base64URL){
        var win = window.open('');
        var html = "<html><head><style type='text/css'>body, html{margin: 0; padding: 0; height: 100%; overflow: hidden;}.h_iframe {height: 100%; width:100%;}</style></head>";
        html += "<div class='h_iframe'><iframe src='" + base64URL  + "' frameborder='0' style='border:0; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:100%;' allowfullscreen></iframe></div></Html>";
        win.document.write(html);
    }
    $("#ft_profil").change(function(){
        readURL(this);
    });
    $('#preview-image').click(function(){
        if(dataTarget !== ''){
            debugBase64(dataTarget);
        }
    });
</script>