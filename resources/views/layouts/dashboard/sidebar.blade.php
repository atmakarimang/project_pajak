<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{route('dashboard')}}" class="brand-link">
    <img src="{{asset('assets/img/LogoKWputih148px.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
      style="opacity: .8">
    <span class="brand-text font-weight-light">A.K.B.P</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      @php
        $dtUser = \App\Models\User::where('user_id',session('user_id'))->first();
      @endphp
      <div class="image">
        <!-- {{asset('assets/AdminLTE/dist/img/user2-160x160.jpg')}} -->
        <img src="{{!empty($dtUser->ft_profil) ? asset('akun/'.$dtUser->ft_profil) : asset('assets/AdminLTE/dist/img/user2-160x160.jpg')}}" style="width:60px" class="img-circle elevation-2" alt="{{$dtUser->user_id}}">
      </div>
      <div class="info">
        @php
          $username = $dtUser->nama;
          $nama = explode(" ", $username);
        @endphp
        <a href="{{url('/pengaturan-akun?mode=edit&usrid=')}}{{base64_encode($dtUser->user_id)}}" class="d-block">{{session('user_id')}}</a>
        <a href="{{url('/pengaturan-akun?mode=edit&usrid=')}}{{base64_encode($dtUser->user_id)}}" class="d-block">{{$nama[0]}}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item has-treeview {{ (request()->is('pengaturan-akun*')) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ (request()->is('pengaturan-akun*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-cog"></i>
            <p>
              Akun
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if($dtUser->peran == 'Forecaster')
            @php
                //dd(request()->segment(1));
            @endphp
            <li class="nav-item">
              <a href="{{route('set.akun')}}" class="nav-link {{ (request()->is('pengaturan-akun*')) ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-cog"></i>
                <p>
                  Pengaturan Akun
                </p>
              </a>
            </li>
            @endif
            <li class="nav-item">
              <a href="{{url('logout')}}" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Logout</p>
              </a>
            </li>
          </ul>
        </li>
        <!-- <li class="nav-item has-treeview">
          <a href="{{route('dashboard')}}" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li> -->
        
        <li class="nav-item has-treeview {{ (request()->is('master-data*')) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ (request()->is('master-data*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-edit"></i>
            <p>
              Master Data
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item has-treeview {{ (request()->is('master-data*')) ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ (request()->is('master-data*')) ? 'menu-open' : ''  }}">
                <i class="fas fa-book nav-icon"></i>
                <p>Permohonan</p>
                <i class="fas fa-angle-left right"></i>
              </a>
              @if($dtUser->peran == 'Forecaster')
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('permohonan.index')}}" class="nav-link {{ (request()->is('master-data/permohonan*')) ? 'active' : '' }}">
                    <i class="fas fa-book nav-icon"></i>
                    <p>Data Permohonan</p>
                  </a>
                </li>
              </ul>
              @endif
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('kr_permohonan.index')}}" class="nav-link {{ (request()->is('master-data/kriteriaPermohonan*')) ? 'active' : '' }}">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Kriteria Permohonan</p>
                  </a>
                </li>
              </ul>
            </li>
            @if($dtUser->peran == 'Forecaster')
            <li class="nav-item">
              <a href="{{route('seksi.index')}}" class="nav-link {{ (request()->is('master-data/seksi*')) ? 'active' : '' }}">
                <i class="fas fa-clipboard-check nav-icon"></i>
                <p>Data Seksi</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('pajak.index')}}" class="nav-link {{ (request()->is('master-data/pajak*')) ? 'active' : '' }}">
                <i class="fas fa-calculator nav-icon"></i>
                <p>Data Pajak</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('stapro.index')}}" class="nav-link {{ (request()->is('master-data/statusprogress*')) ? 'active' : '' }}">
                <i class="fas fa-star-half-alt nav-icon"></i>
                <p>Status & Progress</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('keputusan.index')}}" class="nav-link {{ (request()->is('master-data/keputusan*')) ? 'active' : '' }}">
                <i class="fas fa-landmark nav-icon"></i>
                <p>Data Keputusan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('amarputusan.index')}}" class="nav-link {{ (request()->is('master-data/amar-putusan*')) ? 'active' : '' }}">
                <i class="fas fa-landmark nav-icon"></i>
                <p>Data Amar Putusan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('ptg_banding.index')}}" class="nav-link {{ (request()->is('master-data/petugas-banding-gugatan*')) ? 'active' : '' }}">
                <i class="fas fa-user-shield nav-icon"></i>
                <p>Petugas Banding Gugatan</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        <li class="nav-item has-treeview {{ (request()->is('permohonan*')) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ (request()->is('permohonan*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-paste"></i>
            <p>
              Permohonan
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('pelaksanabidang.index')}}" class="nav-link {{ (request()->is('permohonan/pelaksana-bidang*')) ? 'active' : '' }}">
                <i class="fas fa-chess-queen nav-icon"></i>
                <p>Forecaster</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('kasi.index')}}" class="nav-link {{ (request()->is('permohonan/kasi*')) ? 'active' : '' }}">
                <i class="fas fa-chess-king nav-icon"></i>
                <p>Eksekutor</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview {{ (request()->is('nonpermohonan*')) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ (request()->is('nonpermohonan*')) ? 'active' : '' }}">
            {{-- <i class="nav-icon fas fa-edit"></i> --}}
            <i class="nav-icon fas fa-print"></i>
            <p>
              Non Permohonan
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('nonpelaksanabidang.index')}}" class="nav-link {{ (request()->is('nonpermohonan/nonpelaksana-bidang*')) ? 'active' : '' }}">
                <i class="fas fa-chess nav-icon"></i>
                <p>Eksekutor & Forecaster</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview">
          <a href="{{route('bandinggugatan.index')}}" class="nav-link {{ (request()->is('banding-gugatan*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-balance-scale"></i>
            <p>
              Banding & Gugatan
            </p>
          </a>
        </li>
        <li class="nav-header">
          E-REPORTING
        </li>
        <li class="nav-item">
          <a href="{{route('laporan_permohonan.index')}}" class="nav-link {{ (request()->is('laporan/permohonan*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-file"></i>
            <p>
              Permohonan
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('laporan_nonpermohonan.index')}}" class="nav-link {{ (request()->is('laporan/nonpermohonan*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>
              Non Permohonan
            </p>
          </a>
        </li>
        <!-- <li class="nav-item">
          <a href="" class="nav-link">
            <i class="nav-icon fas fa-copy"></i>
            <p>
              Banding & Gugatan
            </p>
          </a>
        </li> -->
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
<script>
  // /* Code for changing active 
  //       link on clicking */
  //       var btns = 
  //           $(".sidebar .nav-link");

  //       for (var i = 0; i < btns.length; i++) {
  //         console.log("F");
  //           btns[i].addEventListener("click",function () {
  //                                   console.log("ADF");
  //               var current = document.getElementsByClassName("active");
  //             console.log(current);
  //               current[0].className = current[0].className.replace(" active", "");

  //               this.className += " active";
  //           });
  //       }
</script>