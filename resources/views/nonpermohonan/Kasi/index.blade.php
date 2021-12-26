@section('title','Kasi')
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
              <h1 class="m-0 text-dark">Form Kasi</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Kasi</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead style="text-align:center">                
                                    <tr>
                                    <th style="width: 10px">No</th>
                                    <th>No Agenda</th>
                                    <th>NPWP</th>
                                    <th>Nama Wajib Pajak</th>
                                    <th>Jenis Permohonan</th>
                                    <th>Jenis Pajak</th>
                                    <th>No Ketetapan</th>
                                    <th>Seksi Konseptor</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th>Keputusan</th>
                                    <th style="width: 8%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataPB as $key => $dt)
                                        <tr>
                                            <td>{{$page++}}</td>
                                            <td>{{$dt->no_agenda}}</td>
                                            <td>{{$dt->npwp}}</td>
                                            <td>{{$dt->nama_wajib_pajak}}</td>
                                            <td>{{$dt->id_jenis_permohonan}}</td>
                                            <td>{{$dt->id_jenis_pajak}}</td>
                                            <td>{{$dt->no_ketetapan}}</td>
                                            <td>{{$dt->seksi_konseptor}}</td>
                                            <td>
                                                <center>
                                                    @if($dt->progress == 'Final')
                                                        @php $badge = 'badge-success'; @endphp
                                                    @elseif($dt->progress == 'Proses')
                                                        @php $badge = 'badge-primary'; @endphp
                                                    @else
                                                        @php $badge = 'badge-info'; @endphp
                                                    @endif
                                                    <span class="badge {{$badge}}">{{$dt->progress}}</span>
                                                </center>
                                            </td>
                                            <td>
                                                <center>
                                                    @if($dt->status == 'Selesai')
                                                        @php $badge = 'badge-success'; @endphp
                                                    @elseif($dt->status == 'Tunggakan')
                                                        @php $badge = 'badge-danger'; @endphp
                                                    @elseif($dt->status == 'Kembali')
                                                        @php $badge = 'badge-warning'; @endphp
                                                    @else
                                                        @php $badge = 'badge-info'; @endphp
                                                    @endif
                                                    <span class="badge {{$badge}}">{{$dt->status}}</span>
                                                </center>
                                            </td>
                                            <td>
                                                <center>
                                                    @if($dt->hasil_keputusan == 'Diterima')
                                                        @php $badge = 'badge-success'; @endphp
                                                    @elseif($dt->hasil_keputusan == 'Ditolak')
                                                        @php $badge = 'badge-danger'; @endphp
                                                    @elseif($dt->hasil_keputusan == 'Dicabut')
                                                        @php $badge = 'badge-warning'; @endphp
                                                    @elseif($dt->hasil_keputusan == 'Tolak Formal')
                                                        @php $badge = 'badge-secondary'; @endphp
                                                    @elseif($dt->hasil_keputusan == 'Sebagian')
                                                        @php $badge = 'badge-default'; @endphp    
                                                    @else
                                                        @php $badge = 'badge-info'; @endphp
                                                    @endif
                                                    <span class="badge {{$badge}}">{{$dt->hasil_keputusan}}</span>
                                                </center>
                                            </td>
                                            <td>
                                                <center>
                                                    <a href="{{url('/permohonan/kasi/create',base64_encode($dt->no_agenda))}}">
                                                        <button data-toggle="tooltip" data-original-title="Kasi" type="button" class="btn btn-xs btn-primary btn-circle"><i class="fas fa-sign-in-alt"></i></button>
                                                    </a>
                                                    <button onclick="buttonDelete(this)" data-link="" data-toggle="tooltip" data-original-title="Delete" type="button" class="btn btn-xs btn-default btn-circle"><i class="fas fa-trash"></i></button></td>
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
<!-- Select2 -->
<script src="../../plugins/select2/js/select2.full.min.js"></script>
<script>
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
})
<script>
@endsection