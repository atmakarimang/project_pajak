<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
//login logout
Route::get('/login', 'App\Http\Controllers\Auth\LoginController@index')->name('login');
Route::post('/proses_login', 'App\Http\Controllers\Auth\LoginController@proses_login')->name('proses_login');
Route::get('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Route::get('/dashboard', 'App\Http\Controllers\DashboardController@index')->name('dashboard');

Route::get('/crud', 'App\Http\Controllers\CrudController@index')->name('crud');
Auth::routes();

//register
Route::get('/daftar-akun', 'App\Http\Controllers\Auth\RegisterController@index')->name('daftarakun');
Route::post('/daftar-akun/create', 'App\Http\Controllers\Auth\RegisterController@create')->name('daftarakun.create');

//setting akun
// ->middleware('auth')
Route::get('/pengaturan-akun', 'App\Http\Controllers\PengaturanAkun\PengaturanAkunController@index')->name('set.akun');
Route::get('/pengaturan-akun/delete/{user_id}', 'App\Http\Controllers\PengaturanAkun\PengaturanAkunController@delete')->name('delete.akun');

//form master data
Route::get('/master-data/seksi', 'App\Http\Controllers\MasterData\DataSeksiController@index')->name('seksi.index');
Route::post('/master-data/storeKepSeksi', 'App\Http\Controllers\MasterData\DataSeksiController@storeKepSeksi')->name('seksi.storeKepSeksi');
Route::get('/delete-kepsek', 'App\Http\Controllers\MasterData\DataSeksiController@deleteKepsek')->name('seksi.deleteKepSeksi');
Route::post('/ajaxDataKepsek', 'App\Http\Controllers\MasterData\DataSeksiController@ajaxDataKepsek')->name('seksi.ajaxDataKepsek');
Route::post('/master-data/storeKonseptor', 'App\Http\Controllers\MasterData\DataSeksiController@storeKonseptor')->name('seksi.storeKonseptor');

Route::get('/master-data/permohonan', 'App\Http\Controllers\MasterData\DataPermohonanController@index')->name('permohonan.index');
Route::post('/master-data/storePemohon', 'App\Http\Controllers\MasterData\DataPermohonanController@storePemohon')->name('permohonan.storePemohon');
Route::post('/master-data/storeJP', 'App\Http\Controllers\MasterData\DataPermohonanController@storeJP')->name('permohonan.storeJP');
Route::post('/master-data/storeKP', 'App\Http\Controllers\MasterData\DataPermohonanController@storeKP')->name('permohonan.storeKP');

Route::get('/master-data/pajak', 'App\Http\Controllers\MasterData\DataPajakController@index')->name('pajak.index');
Route::post('/master-data/storePajak', 'App\Http\Controllers\MasterData\DataPajakController@storePajak')->name('pajak.storePajak');
Route::post('/master-data/storeJK', 'App\Http\Controllers\MasterData\DataPajakController@storeJK')->name('pajak.storeJK');

Route::get('/master-data/statusprogress', 'App\Http\Controllers\MasterData\DataStatusProgressController@index')->name('stapro.index');
Route::post('/master-data/storeStatus', 'App\Http\Controllers\MasterData\DataStatusProgressController@storeStatus')->name('stapro.storeStatus');
Route::post('/master-data/storeProgress', 'App\Http\Controllers\MasterData\DataStatusProgressController@storeProgress')->name('stapro.storeProgress');

Route::get('/master-data/keputusan', 'App\Http\Controllers\MasterData\DataKeputusanController@index')->name('keputusan.index');
Route::post('/master-data/store', 'App\Http\Controllers\MasterData\DataKeputusanController@store')->name('keputusan.store');

Route::get('/master-data/amar-putusan', 'App\Http\Controllers\MasterData\DataAmarPutusanController@index')->name('amarputusan.index');
Route::post('/master-data/store', 'App\Http\Controllers\MasterData\DataAmarPutusanController@store')->name('amarputusan.store');

Route::get('/master-data/petugas-banding-gugatan', 'App\Http\Controllers\MasterData\PetugasBandingGugatanController@index')->name('ptg_banding.index');
Route::post('/master-data/storePetSidang', 'App\Http\Controllers\MasterData\PetugasBandingGugatanController@storePetSidang')->name('ptg_banding.storePetSidang');
Route::post('/master-data/storeEksekutor', 'App\Http\Controllers\MasterData\PetugasBandingGugatanController@storeEksekutor')->name('ptg_banding.storeEksekutor');

//form permohonan
Route::get('/permohonan/pelaksana-bidang', 'App\Http\Controllers\Permohonan\PelaksanaBidangController@index')->name('pelaksanabidang.index');
Route::post('pelaksana-bidang/store', 'App\Http\Controllers\Permohonan\PelaksanaBidangController@store')->name('pelaksanabidang.store');
Route::get('/permohonan/pelaksana-bidang/browse', 'App\Http\Controllers\Permohonan\PelaksanaBidangController@browse')->name('pelaksanabidang.browse');
Route::get('/permohonan/pelaksana-bidang/datatablePB', 'App\Http\Controllers\Permohonan\PelaksanaBidangController@datatablePB')->name('pelaksanabidang.datatablePB');
Route::get('/permohonan/pelaksana-bidang/delete/{no_agenda}', 'App\Http\Controllers\Permohonan\PelaksanaBidangController@delete');
Route::get('/permohonan/pelaksana-bidang/print/{id}/{doctype}', 'App\Http\Controllers\Permohonan\PelaksanaBidangController@print')->name('pelaksanabidang.print');

Route::get('/permohonan/kasi', 'App\Http\Controllers\Permohonan\KasiController@index')->name('kasi.index');
Route::get('/permohonan/kasi/create/{no_agenda}', 'App\Http\Controllers\Permohonan\KasiController@create')->name('kasi.create');
Route::post('/permohonan/kasi/store', 'App\Http\Controllers\Permohonan\KasiController@store')->name('kasi.store');

//form non permohonan
Route::get('/nonpermohonan/nonpelaksana-bidang', 'App\Http\Controllers\NonPermohonan\NonPelaksanaBidangController@index')->name('nonpelaksanabidang.index');
Route::post('/store', 'App\Http\Controllers\NonPermohonan\NonPelaksanaBidangController@store')->name('nonpelaksanabidang.store');
Route::get('/nonpermohonan/nonpelaksana-bidang/browse', 'App\Http\Controllers\NonPermohonan\NonPelaksanaBidangController@browse')->name('nonpelaksanabidang.browse');
Route::get('/nonpermohonan/nonpelaksana-bidang/datatablePB', 'App\Http\Controllers\NonPermohonan\NonPelaksanaBidangController@datatablePB')->name('nonpelaksanabidang.datatablePB');
Route::get('/nonpermohonan/nonpelaksana-bidang/delete/{no_agenda}', 'App\Http\Controllers\NonPermohonan\NonPelaksanaBidangController@delete');
Route::get('/nonpermohonan/nonpelaksana-bidang/print/{id}/{doctype}', 'App\Http\Controllers\NonPermohonan\NonPelaksanaBidangController@print')->name('nonpelaksanabidang.print');

Route::get('/nonpermohonan/kasi', 'App\Http\Controllers\NonPermohonan\KasiController@index')->name('nonkasi.index');
Route::get('/nonpermohonan/kasi/create/{no_agenda}', 'App\Http\Controllers\NonPermohonan\KasiController@create')->name('nonkasi.create');
Route::post('/nonpermohonan/kasi/store', 'App\Http\Controllers\NonPermohonan\KasiController@store')->name('nonkasi.store');

//form banding gugatan
Route::get('/banding-gugatan', 'App\Http\Controllers\BandingGugatan\BandingGugatanController@index')->name('bandinggugatan.index');