<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>A.K.B.P | Log in</title>
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

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">

  <style>
    .login-page {
      background-image: url("{{asset('assets/img/bg_new-min.jpg')}}");
      /* Center and scale the image nicely */
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      position: relative; 
    }

    .companyname {
      font-family: 'Roboto', sans-serif;
      font-size: 33px;
      font-weight: bold;
      color: rgb(33, 39, 43);
      text-align: center; 
      margin-left: 5px;
    }

    .photo-tag {
      position: relative;
      color: aliceblue;
      top: 130px;
      text-decoration: underline;
    }

    .ion-link {
      position: relative; 
      top: 5px;
      right: 4px;
      font-size: 28px;
    }

    #logoperusahaan{
      position: relative;
      bottom: 5px;
    }
    
    @media (min-width: 481px) {
      .login-box {
        position: relative;
        left: 400px;
      }

      .photo-tag {
        position: relative;
        color: aliceblue;
        right: 500px;
        top: 120px;
        text-decoration: underline;
      }

      .ion-link {
        position: relative; 
        top: 5px;
        right: 4px;
        font-size: 28px;
      }

      #logoperusahaan{
        position: relative;
        bottom: 5px;
        right: 1px;
      }
    }

  </style>
</head>

<body class="hold-transition login-page ">
<div class="login-box">
  <div class="login-logo">
    <img src="{{asset('assets/img/LogoKW1.png')}}" width="46px" id="logoperusahaan"> <b class="companyname">A.K.B.P</b> 
  </div>
  <!-- /.login-logo -->
  <div class="card"> 
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in </p>

      <form action="{{url('proses_login')}}" method="post">
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
          <input type="password" name ="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<div class="photo-tag"> 
  <i class="ion-link"></i><b>Photo by M Ahsannudin A</b>
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
