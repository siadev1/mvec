<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Middleware\RedirectIfAuthenticated;


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
    Route::get('/user/logout', [UserController::class, 'UserDestroy'])->name('user.logout');
    Route::post('/user/update', [UserController::class, 'UserProfileUpdate'])->name('userProfile.update');
    Route::post('/user/password/update', [UserController::class, 'UserUpdatePassword'])->name('userPassword.update');



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
Route::get('/admin/login', [AdminController::class, 'AdminLogin'])->name('admin.login')->middleware(RedirectIfAuthenticated::class);;
Route::get('/vendor/login', [VendorController::class, 'VendorLogin'])->name('vendor.login')->middleware(RedirectIfAuthenticated::class);;
Route::post('vendor/register/store',[VendorController::class, 'vendorRegister'])->name('vendor.register');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::controller(BrandController::Class)->group(function(){
        Route::get('all/brand', 'AllBrand')->name('all.brand');
        Route::get('add/brand','AddBrand')->name('add.brand');
        Route::post('add_brand/store', 'StoreBrand')->name('add.brand.store');
        Route::get('brand/edit/{id}', 'BrandEdit')->name('brand.edit');
        Route::post('brand/edit/store/', 'StoreEditBrand')->name('brand.edit.store');
        Route::get('brand/delete/{id}', 'DeleteBrand')->name('brand.delete');
    });

    Route::controller(CategoryController::class)->group(function(){
        Route::get('all/category','AllCategory')->name('all.category');
        Route::get('add/category','AddCategory')->name('add.category');
        Route::post('add_category/store', 'StoreCategory')->name('add.category.store');
        Route::get('category/edit/{id}', 'CategoryEdit')->name('category.edit');
        Route::post('category/edit/store/', 'StoreEditCategory')->name('category.edit.store');
        Route::get('category/delete/{id}', 'DeleteCategory')->name('category.delete');
    });

    Route::controller(SubCategoryController::class)->group(function(){
        Route::get('all/subcategory','AllSubCategory')->name('all.subCategory');
        Route::get('add/subcategory','AddSubCategory')->name('add.subCategory');
        Route::post('add_subcategory/store', 'StoreSubCategory')->name('add.subCategory.store');
        Route::get('subcategory/edit/{id}', 'subCategoryEdit')->name('subCategory.edit');
        Route::post('subcategory/edit/store/', 'StoreEditSubCategory')->name('subCategory.edit.store');
        Route::get('subcategory/delete/{id}', 'DeleteSubCategory')->name('subCategory.delete');
        Route::get('/subcategory/ajax/{category_id}' , 'GetSubCategory');
    });

    Route::controller(AdminController::class)->group(function(){
        Route::get('/inactive/vendor' , 'InactiveVendor')->name('inactive.vendor');
        Route::get('/active/vendor' , 'ActiveVendor')->name('active.vendor');
        Route::get('/inactive/vendor/details/{id}' , 'InactiveVendorDetails')->name('inactive.vendor.details');
        Route::post('/active/vendor/approve' , 'ActiveVendorApprove')->name('active.vendor.approve');
        Route::get('/active/vendor/details/{id}' , 'ActiveVendorDetails')->name('active.vendor.details');
        Route::post('/inactive/vendor/approve' , 'InActiveVendorApprove')->name('inactive.vendor.approve');
    
    });

    Route::controller(ProductController::class)->group(function(){
        Route::get('/all/product' , 'AllProduct')->name('all.product');
        Route::get('/add/product' , 'AddProduct')->name('add.product');
        Route::post('/store/product' , 'StoreProduct')->name('store.product');
        Route::get('/edit/product/{id}' , 'EditProduct')->name('product.edit');
        Route::post('/update/product' , 'UpdateProduct')->name('product.update');
        Route::post('/update/product/thumbnail' , 'UpdateProductThumbnail')->name('product.update.thumbnail');
        Route::post('/update/product/multiimage' , 'UpdateProductMultiimage')->name('product.update.multiimage');
        Route::get('/product/multiimg/delete/{id}' , 'MulitImageDelelte')->name('product.multiimg.delete');
        Route::get('/product/inactive/{id}' , 'ProductInactive')->name('product.inactive');
        Route::get('/product/active/{id}' , 'ProductActive')->name('product.active');
        Route::get('/delete/product/{id}' , 'ProductDelete')->name('delete.product');
    });
    
});
