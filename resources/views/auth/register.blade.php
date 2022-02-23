<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>A.K.B.P | Daftar Akun</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="icon" href="{{asset('assets/img/LogoKW1.png')}}" sizes="16x16" type="image/png">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/AdminLTE/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('assets/AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/AdminLTE/dist/css/adminlte.min.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
    .login-page {
      background-image: url("{{asset('assets/img/bg2.png')}}");
      /* Center and scale the image nicely */
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      position: relative;
    } 
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <img src="{{asset('assets/img/LogoKW1.png')}}" width="46px"> <b>A.K.B.P</b> 
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Daftar Akun </p>

      <form action="{{route('daftarakun.create')}}" method="post">
        {{ csrf_field() }}
        <div class="input-group mb-3">
          <input type="text" name ="user_id" class="form-control" placeholder="User ID" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" name ="nama" class="form-control" placeholder="Nama" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="form-group mb-3">
            <select class="form-control select2" name="jabatan" required>
                <option selected disabled>Pilih Jabatan</option>
                <option value="Pelaksana">Pelaksana</option>
                <option value="Kepala Bidang">Kepala Bidang</option>
                <option value="Kepala Seksi">Kepala Seksi</option>
                <option value="Penelaah Keberatan">Penelaah Keberatan</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <select class="form-control select2" name="peran" required>
                <option selected disabled>Pilih Peran</option>
                <option value="Forecaster">Forecaster</option>
                <option value="Eksekutor">Eksekutor</option>
            </select>
        </div>
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
        <div class="row">
          <input type="hidden" name ="form" value="formregister">
          <button type="submit" class="btn btn-block btn-flat btn-primary" name="mode" value="add">
            <span class="fas fa-user-plus"></span>
            Daftar
          </button>
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{asset('assets/AdminLTE/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/AdminLTE/dist/js/adminlte.min.js')}}"></script>

</body>
</html>
