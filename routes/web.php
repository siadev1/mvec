<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\UserController;
// {{asset('adminbackend/')}}
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('frontend.index');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', [UserController::class, 'UserDashboard'])->name('dashboard');
    Route::get('/user/logout', [UserController::class, 'Userdestroy'])->name('user.logout');
    Route::post('/user/update', [UserController::class, 'UserProfileUpdate'])->name('userProfile.update');


});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'AdminDestroy'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/admin/profile/change-password', [AdminController::class, 'AdminChangePassword'])->name('admin.profile.change-password');
    Route::post('/admin/update/password', [AdminController::class, 'AdminUpdatePassword'])->name('update.password');
});
Route::middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('/vendor/dashboard', [VendorController::class, 'vendorDashboard'])->name('vendor.dashboard');
    Route::post('/vendor/profile/store', [VendorController::class, 'VendorProfileStore'])->name('vendor.profile.store');
    Route::get('/vendor/profile', [VendorController::class, 'VendorProfile'])->name('vendor.profile');
    Route::get('/vendor/profile/change-password', [VendorController::class, 'VendorChangePassword'])->name('vendor.profile.change-password');
    Route::post('/vendor/update/password', [VendorController::class, 'VendorUpdatePassword'])->name('update.password');
    Route::get('/vendor/logout', [VendorController::class, 'VendorDestroy'])->name('vendor.logout');

});
Route::get('/admin/login', [AdminController::class, 'AdminLogin'])->name('admin.login');
Route::get('/vendor/login', [VendorController::class, 'VendorLogin'])->name('vendor.login');

