<?php

use App\Http\Controllers\backend\AdminController;
use App\Http\Controllers\backend\AuthController;
use App\Http\Controllers\backend\ForgotPasswordController;
use App\Http\Controllers\frontend\CollegeController;
use App\Http\Controllers\frontend\FeedbackController;
use Illuminate\Support\Facades\Route;

Route::get('/',[CollegeController::class,'index']);

Route::get('/{slug}',[CollegeController::class,'openCollege']);

Route::get('/about/{slug}',[CollegeController::class,'aboutPage'])->name('poly.about');
Route::get('/contact/{slug}',[CollegeController::class,'contactPage'])->name('poly.contact');

Route::post('/contact/submit-feedback/{slug}', [FeedbackController::class,'store'])->name('feedback.store');

Route::match(['get','post'],'/contact/search-feedback/{slug}', [FeedbackController::class,'searchFeedback'])->name('feedback.search');

Route::post('/contact/search-feedback/send-message/{slug}/{ack}', [FeedbackController::class,'sendMessage'])->name('feedback.send');

Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

//forget password
Route::get('/admin/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('admin.forgot');
Route::post('/admin/send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('admin.send.otp');
Route::post('/admin/verify-otp', [ForgotPasswordController::class, 'verifyOtp']);
Route::post('/admin/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('admin.reset.password');

Route::get('/admin/login/refresh_captcha', [AuthController::class, 'refreshCaptcha'])->name('refresh_captcha');


//admin routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});