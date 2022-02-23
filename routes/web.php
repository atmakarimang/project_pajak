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
// set middleware ->hnya bs diakses forecaster
// ->middleware('auth')
Route::get('/pengaturan-akun', 'App\Http\Controllers\PengaturanAkun\PengaturanAkunController@index')->name('set.akun');
Route::get('/pengaturan-akun/datatableAkun', 'App\Http\Controllers\PengaturanAkun\PengaturanAkunController@datatableAkun')->name('set.datatableAkun');
Route::get('/pengaturan-akun/delete/{user_id}', 'App\Http\Controllers\PengaturanAkun\PengaturanAkunController@delete')->name('delete.akun');

//form master data
//set middleware masterdata->hnya bs diakses forecaster
Route::get('/master-data/permohonan', 'App\Http\Controllers\MasterData\DataPermohonanController@index')->name('permohonan.index');
Route::post('/master-data/storePemohon', 'App\Http\Controllers\MasterData\DataPermohonanController@storePemohon')->name('permohonan.storePemohon');
Route::post('/master-data/storeJP', 'App\Http\Controllers\MasterData\DataPermohonanController@storeJP')->name('permohonan.storeJP');
Route::post('/master-data/storeKP', 'App\Http\Controllers\MasterData\DataPermohonanController@storeKP')->name('permohonan.storeKP');
Route::get('/master-data/ajaxDataPemohon', 'App\Http\Controllers\MasterData\DataPermohonanController@ajaxDataPemohon')->name('permohonan.ajaxDataPemohon');
Route::get('/master-data/editPemohon/', 'App\Http\Controllers\MasterData\DataPermohonanController@editPemohon')->name('permohonan.editPemohon');
Route::get('/master-data/deletePmh/{id}', 'App\Http\Controllers\MasterData\DataPermohonanController@deletePemohon');
Route::get('/master-data/ajaxDataJP', 'App\Http\Controllers\MasterData\DataPermohonanController@ajaxDataJP')->name('permohonan.ajaxDataJP');
Route::get('/master-data/editJP/', 'App\Http\Controllers\MasterData\DataPermohonanController@editJP')->name('permohonan.editJP');
Route::get('/master-data/deleteJP/{id}', 'App\Http\Controllers\MasterData\DataPermohonanController@deleteJP');
Route::get('/master-data/ajaxDataKP', 'App\Http\Controllers\MasterData\DataPermohonanController@ajaxDataKP')->name('permohonan.ajaxDataKP');
Route::get('/master-data/editKP/', 'App\Http\Controllers\MasterData\DataPermohonanController@editKP')->name('permohonan.editKP');
Route::get('/master-data/deleteKP/{id}', 'App\Http\Controllers\MasterData\DataPermohonanController@deleteKP');

Route::get('/master-data/seksi', 'App\Http\Controllers\MasterData\DataSeksiController@index')->name('seksi.index');
Route::post('/master-data/storeKepSeksi', 'App\Http\Controllers\MasterData\DataSeksiController@storeKepSeksi')->name('seksi.storeKepSeksi');
Route::get('/master-data/ajaxDataKepsek', 'App\Http\Controllers\MasterData\DataSeksiController@ajaxDataKepsek')->name('seksi.ajaxDataKepsek');
Route::get('/master-data/editKS/', 'App\Http\Controllers\MasterData\DataSeksiController@editKS')->name('seksi.editKS');
Route::get('/master-data/deleteKS/{id}', 'App\Http\Controllers\MasterData\DataSeksiController@deleteKS');
Route::post('/master-data/storeKonseptor', 'App\Http\Controllers\MasterData\DataSeksiController@storeKonseptor')->name('seksi.storeKonseptor');
Route::get('/master-data/ajaxDataKS', 'App\Http\Controllers\MasterData\DataSeksiController@ajaxDataKS')->name('seksi.ajaxDataKS');
Route::get('/master-data/editKonseptor/', 'App\Http\Controllers\MasterData\DataSeksiController@editKonseptor')->name('seksi.editKonseptor');
Route::get('/master-data/deleteKonseptor/{id}', 'App\Http\Controllers\MasterData\DataSeksiController@deleteKonseptor');
Route::post('/master-data/storePK', 'App\Http\Controllers\MasterData\DataSeksiController@storePK')->name('seksi.storePK');
Route::get('/master-data/ajaxDataPK', 'App\Http\Controllers\MasterData\DataSeksiController@ajaxDataPK')->name('seksi.ajaxDataPK');
Route::get('/master-data/editPK/', 'App\Http\Controllers\MasterData\DataSeksiController@editPK')->name('seksi.editPK');
Route::get('/master-data/deletePK/{id}', 'App\Http\Controllers\MasterData\DataSeksiController@deletePK');

Route::get('/master-data/pajak', 'App\Http\Controllers\MasterData\DataPajakController@index')->name('pajak.index');
Route::post('/master-data/storePajak', 'App\Http\Controllers\MasterData\DataPajakController@storePajak')->name('pajak.storePajak');
Route::get('/master-data/ajaxDataPajak', 'App\Http\Controllers\MasterData\DataPajakController@ajaxDataPajak')->name('pajak.ajaxDataPajak');
Route::get('/master-data/editPj/', 'App\Http\Controllers\MasterData\DataPajakController@editPj')->name('pajak.editPj');
Route::get('/master-data/deletePj/{id}', 'App\Http\Controllers\MasterData\DataPajakController@deletePj');
Route::post('/master-data/storeJK', 'App\Http\Controllers\MasterData\DataPajakController@storeJK')->name('pajak.storeJK');
Route::get('/master-data/ajaxDataJK', 'App\Http\Controllers\MasterData\DataPajakController@ajaxDataJK')->name('pajak.ajaxDataJK');
Route::get('/master-data/editJk/', 'App\Http\Controllers\MasterData\DataPajakController@editJk')->name('pajak.editJk');
Route::get('/master-data/deleteJk/{id}', 'App\Http\Controllers\MasterData\DataPajakController@deleteJk');

Route::get('/master-data/statusprogress', 'App\Http\Controllers\MasterData\DataStatusProgressController@index')->name('stapro.index');
Route::post('/master-data/storeStatus', 'App\Http\Controllers\MasterData\DataStatusProgressController@storeStatus')->name('stapro.storeStatus');
Route::get('/master-data/ajaxDataSt', 'App\Http\Controllers\MasterData\DataStatusProgressController@ajaxDataSt')->name('stapro.ajaxDataSt');
Route::get('/master-data/editSt/', 'App\Http\Controllers\MasterData\DataStatusProgressController@editSt')->name('stapro.editSt');
Route::get('/master-data/deleteSt/{id}', 'App\Http\Controllers\MasterData\DataStatusProgressController@deleteSt');
Route::post('/master-data/storeProgress', 'App\Http\Controllers\MasterData\DataStatusProgressController@storeProgress')->name('stapro.storeProgress');
Route::get('/master-data/ajaxDataPr', 'App\Http\Controllers\MasterData\DataStatusProgressController@ajaxDataPr')->name('stapro.ajaxDataPr');
Route::get('/master-data/editPr/', 'App\Http\Controllers\MasterData\DataStatusProgressController@editPr')->name('stapro.editPr');
Route::get('/master-data/deletePr/{id}', 'App\Http\Controllers\MasterData\DataStatusProgressController@deletePr');

Route::get('/master-data/keputusan', 'App\Http\Controllers\MasterData\DataKeputusanController@index')->name('keputusan.index');
Route::post('/master-data/storeKep', 'App\Http\Controllers\MasterData\DataKeputusanController@storeKep')->name('keputusan.storeKep');
Route::get('/master-data/ajaxDataKep', 'App\Http\Controllers\MasterData\DataKeputusanController@ajaxDataKep')->name('keputusan.ajaxDataKep');
Route::get('/master-data/editKep/', 'App\Http\Controllers\MasterData\DataKeputusanController@editKep')->name('keputusan.editKep');
Route::get('/master-data/deleteKep/{id}', 'App\Http\Controllers\MasterData\DataKeputusanController@deleteKep');

Route::get('/master-data/amar-putusan', 'App\Http\Controllers\MasterData\DataAmarPutusanController@index')->name('amarputusan.index');
Route::post('/master-data/storeAP', 'App\Http\Controllers\MasterData\DataAmarPutusanController@storeAP')->name('amarputusan.storeAP');
Route::get('/master-data/ajaxDataAP', 'App\Http\Controllers\MasterData\DataAmarPutusanController@ajaxDataAP')->name('amarputusan.ajaxDataAP');
Route::get('/master-data/editAP/', 'App\Http\Controllers\MasterData\DataAmarPutusanController@editAP')->name('amarputusan.editAP');
Route::get('/master-data/deleteAP/{id}', 'App\Http\Controllers\MasterData\DataAmarPutusanController@deleteAP');

Route::get('/master-data/petugas-banding-gugatan', 'App\Http\Controllers\MasterData\PetugasBandingGugatanController@index')->name('ptg_banding.index');
Route::post('/master-data/storePetSidang', 'App\Http\Controllers\MasterData\PetugasBandingGugatanController@storePetSidang')->name('ptg_banding.storePetSidang');
Route::get('/master-data/ajaxDataPS', 'App\Http\Controllers\MasterData\PetugasBandingGugatanController@ajaxDataPS')->name('ptg_banding.ajaxDataPS');
Route::get('/master-data/editPS/', 'App\Http\Controllers\MasterData\PetugasBandingGugatanController@editPS')->name('ptg_banding.editPS');
Route::get('/master-data/deletePS/{id}', 'App\Http\Controllers\MasterData\PetugasBandingGugatanController@deletePS');
Route::post('/master-data/storeEksekutor', 'App\Http\Controllers\MasterData\PetugasBandingGugatanController@storeEksekutor')->name('ptg_banding.storeEksekutor');
Route::get('/master-data/ajaxDataEks', 'App\Http\Controllers\MasterData\PetugasBandingGugatanController@ajaxDataEks')->name('ptg_banding.ajaxDataEks');
Route::get('/master-data/editEks/', 'App\Http\Controllers\MasterData\PetugasBandingGugatanController@editEks')->name('ptg_banding.editEks');
Route::get('/master-data/deleteEks/{id}', 'App\Http\Controllers\MasterData\PetugasBandingGugatanController@deleteEks');

//form permohonan
Route::get('/permohonan/pelaksana-bidang', 'App\Http\Controllers\Permohonan\PelaksanaBidangController@index')->name('pelaksanabidang.index');
Route::post('pelaksana-bidang/store', 'App\Http\Controllers\Permohonan\PelaksanaBidangController@store')->name('pelaksanabidang.store');
Route::get('/permohonan/pelaksana-bidang/browse', 'App\Http\Controllers\Permohonan\PelaksanaBidangController@browse')->name('pelaksanabidang.browse');
Route::get('/permohonan/pelaksana-bidang/datatablePB', 'App\Http\Controllers\Permohonan\PelaksanaBidangController@datatablePB')->name('pelaksanabidang.datatablePB');
Route::get('/permohonan/pelaksana-bidang/delete/{no_agenda}', 'App\Http\Controllers\Permohonan\PelaksanaBidangController@delete');
Route::get('/permohonan/pelaksana-bidang/print/{id}', 'App\Http\Controllers\Permohonan\PelaksanaBidangController@print')->name('pelaksanabidang.print');
Route::get('/permohonan/pelaksana-bidang/printAll', 'App\Http\Controllers\Permohonan\PelaksanaBidangController@printAll')->name('pelaksanabidang.printAll');

Route::get('/permohonan/kasi', 'App\Http\Controllers\Permohonan\KasiController@index')->name('kasi.index');
Route::get('/permohonan/kasi/datatableKasi', 'App\Http\Controllers\Permohonan\KasiController@datatableKasi')->name('kasi.datatableKasi');
Route::get('/permohonan/kasi/create/{no_agenda}', 'App\Http\Controllers\Permohonan\KasiController@create')->name('kasi.create');
Route::post('/permohonan/kasi/store', 'App\Http\Controllers\Permohonan\KasiController@store')->name('kasi.store');
Route::get('/permohonan/kasi/print/{id}', 'App\Http\Controllers\Permohonan\KasiController@print')->name('kasi.print');
Route::get('/permohonan/kasi/printAll', 'App\Http\Controllers\Permohonan\KasiController@printAll')->name('kasi.printAll');

//form non permohonan
Route::get('/nonpermohonan/nonpelaksana-bidang', 'App\Http\Controllers\NonPermohonan\NonPelaksanaBidangController@index')->name('nonpelaksanabidang.index');
Route::post('/store', 'App\Http\Controllers\NonPermohonan\NonPelaksanaBidangController@store')->name('nonpelaksanabidang.store');
Route::get('/nonpermohonan/nonpelaksana-bidang/browse', 'App\Http\Controllers\NonPermohonan\NonPelaksanaBidangController@browse')->name('nonpelaksanabidang.browse');
Route::get('/nonpermohonan/nonpelaksana-bidang/datatablePB', 'App\Http\Controllers\NonPermohonan\NonPelaksanaBidangController@datatablePB')->name('nonpelaksanabidang.datatablePB');
Route::get('/nonpermohonan/nonpelaksana-bidang/delete/{no_agenda}', 'App\Http\Controllers\NonPermohonan\NonPelaksanaBidangController@delete');
Route::get('/nonpermohonan/nonpelaksana-bidang/print/{id}', 'App\Http\Controllers\NonPermohonan\NonPelaksanaBidangController@print')->name('nonpelaksanabidang.print');

Route::get('/nonpermohonan/kasi', 'App\Http\Controllers\NonPermohonan\KasiController@index')->name('nonkasi.index');
Route::get('/nonpermohonan/kasi/create/{no_agenda}', 'App\Http\Controllers\NonPermohonan\KasiController@create')->name('nonkasi.create');
Route::post('/nonpermohonan/kasi/store', 'App\Http\Controllers\NonPermohonan\KasiController@store')->name('nonkasi.store');

//form banding gugatan
Route::get('/banding-gugatan', 'App\Http\Controllers\BandingGugatan\BandingGugatanController@index')->name('bandinggugatan.index');
Route::post('banding-gugatan/store', 'App\Http\Controllers\BandingGugatan\BandingGugatanController@store')->name('bandinggugatan.store');
Route::get('/banding-gugatan/browse', 'App\Http\Controllers\BandingGugatan\BandingGugatanController@browse')->name('bandinggugatan.browse');
Route::get('/banding-gugatan/datatable', 'App\Http\Controllers\BandingGugatan\BandingGugatanController@datatable')->name('bandinggugatan.datatable');
Route::get('/banding-gugatan/print', 'App\Http\Controllers\BandingGugatan\BandingGugatanController@print')->name('bandinggugatan.print');
Route::get('/banding-gugatan/delete/{id_bg}', 'App\Http\Controllers\BandingGugatan\BandingGugatanController@delete');

//laporan permohonan
// ->middleware('auth')
Route::get('/laporan/permohonan', 'App\Http\Controllers\Laporan\Permohonan\LaporanPermohonanController@index')->name('laporan_permohonan.index');
Route::get('/laporan/permohonan/ajaxDataPB', 'App\Http\Controllers\Laporan\Permohonan\LaporanPermohonanController@ajaxDataPB')->name('laporan_permohonan.ajaxDataPB');
Route::get('/laporan/permohonan/print', 'App\Http\Controllers\Laporan\Permohonan\LaporanPermohonanController@print')->name('laporan_permohonan.print');

//laporan non permohonan
Route::get('/laporan/nonpermohonan', 'App\Http\Controllers\Laporan\NonPermohonan\LaporanNonPermohonanController@index')->name('laporan_nonpermohonan.index');
Route::get('/laporan/nonpermohonan/ajaxDataPB', 'App\Http\Controllers\Laporan\NonPermohonan\LaporanNonPermohonanController@ajaxDataPB')->name('laporan_nonpermohonan.ajaxDataPB');
Route::get('/laporan/nonpermohonan/print', 'App\Http\Controllers\Laporan\NonPermohonan\LaporanNonPermohonanController@print')->name('laporan_nonpermohonan.print');
