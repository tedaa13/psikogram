<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReportController;
use App\http\Controllers\ReportRekapController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\ProfileController;

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');

Route::get('home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout')->middleware('auth');

Route::get('register', [RegisterController::class, 'register'])->name('register');
Route::post('register/action', [RegisterController::class, 'actionregister'])->name('actionregister');

// Route::post('psikogram/getDataUser', [ReportController::class, 'getDataUser'])->name('getDataUser');

Route::controller(UserController::class)->group(function(){
    Route::get('peserta', 'index')->middleware('auth');
    Route::post('peserta/addData', 'addData')->middleware('auth');
    Route::post('peserta/getData', 'getData')->middleware('auth');
    Route::post('peserta/getDataDetail', 'getDataDetail')->middleware('auth');
    Route::post('peserta/masterTes', 'masterTes')->middleware('auth');
});

Route::controller(ProfileController::class)->group(function(){
    Route::get('profile', 'index')->middleware('auth');
    Route::post('profile/updateData', 'updateData')->middleware('auth');
    Route::post('profile/simpan_data', 'simpan_data')->middleware('auth');
    Route::post('profile/loadProfile', 'loadProfile')->middleware('auth');
});

Route::controller(DashboardUserController::class)->group(function(){
    Route::get('dashboard_user', 'index')->middleware('auth');
    Route::post('dashboard_user/getQuiz', 'getQuiz')->middleware('auth');
    Route::post('dashboard_user/getContentQuiz', 'getContentQuiz')->middleware('auth');
    Route::post('dashboard_user/getContohSoal', 'getContohSoal')->middleware('auth');
    Route::post('dashboard_user/getSoal', 'getSoal')->middleware('auth');
    Route::post('dashboard_user/getTableNumber', 'getTableNumber')->middleware('auth');
    Route::post('dashboard_user/SubmitAnswer', 'SubmitAnswer')->middleware('auth');
    Route::post('dashboard_user/cekLastSave', 'cekLastSave')->middleware('auth');
    Route::post('dashboard_user/updateDone', 'updateDone')->middleware('auth');
    Route::post('dashboard_user/cekWaktu', 'cekWaktu')->middleware('auth');
});

Route::controller(FormController::class)->group(function(){
    Route::get('form', 'index')->middleware('auth');
    Route::post('form/getData', 'getData')->middleware('auth');
    Route::post('form/getDataDetail', 'getDataDetail')->middleware('auth');
    Route::post('form/updateData', 'updateData')->middleware('auth');
    Route::post('form/saveData', 'saveData')->middleware('auth');
});

Route::controller(ReportController::class)->group(function(){
    Route::get('report', 'index')->middleware('auth');
    Route::post('report/getProfileUser', 'getProfileUser')->middleware('auth');
    Route::post('report/getDataUser', 'getDataUser')->middleware('auth');
    Route::post('report/getDataTest', 'getDataTest')->middleware('auth');
    Route::post('report/getDataResultWPT', 'getDataResultWPT')->middleware('auth');
    Route::post('report/getDataResultPAPI', 'getDataResultPAPI')->middleware('auth');
    Route::post('report/getDataResultDISC', 'getDataResultDISC')->middleware('auth');
    Route::post('report/getDataReport', 'getDataReport')->middleware('auth');
    Route::post('report/getDataChart', 'getDataChart')->middleware('auth');
    Route::post('report/getReportENG', 'getReportENG')->middleware('auth');
});

Route::controller(ReportRekapController::class)->group(function(){
    Route::get('reportRekap', 'index')->middleware('auth');
    Route::post('reportRekap/getData', 'getData')->middleware('auth');
});

// Route::post('psikogram/getDataUser','App\Http\Controllers\ReportController@getDataUser')->middleware('auth');