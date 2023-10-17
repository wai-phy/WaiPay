<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

//register login page

Route::middleware(['admin_auth'])->group(function () {
    Route::redirect('/', 'loginPage');
    Route::get('loginPage',[AuthController::class,'loginPage'])->name('auth#login');
    Route::get('registerPage',[AuthController::class,'registerPage'])->name('auth#register');
});

Route::middleware(['auth'])->group(function () {
    
    Route::get('dashboard',[AuthController::class,'dashboard'])->name('dashboard');

    Route::middleware(['admin_auth'])->group(function () {
        Route::prefix('backend')->group(function () {
            Route::get('admin/home',[AdminController::class,'adminHome'])->name('admin#Home');
            //admin /admin list
            Route::get('admin/list',[AdminController::class,'adminList'])->name('admin#List');
            Route::get('admin/datatable',[AdminController::class,'DataTable'])->name('admin#DataTable');
            Route::get('admin/create',[AdminController::class,'adminCreate'])->name('admin#Create');
            Route::post('admin/store',[AdminController::class,'store'])->name('admin#store');
            Route::get('admin/edit/{id}',[AdminController::class,'edit'])->name('admin#edit');
            Route::post('admin/update/{id}',[AdminController::class,'update'])->name('admin#update');
            Route::get('admin/delete/{id}',[AdminController::class,'destroy'])->name('admin#delete');
        });
    });
    
    Route::middleware(['user_auth'])->group(function () {
        Route::get('user/home', function () {
            return view('userHome');
        })->name('user#Home');
    });
    
});


