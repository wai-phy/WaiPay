<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Frontend\PageController;

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
            Route::delete('admin/delete/{id}',[AdminController::class,'destroy'])->name('admin#delete');

            Route::resource('users', UserController::class);
            Route::get('users/datatable/serverData',[UserController::class,'serverData']);

            Route::get('wallet/index',[WalletController::class,'index'])->name('wallet.index');
            Route::get('wallet/datatable/serverData',[WalletController::class,'serverData']);
        });
    });

    Route::middleware(['user_auth'])->group(function () {
        Route::get('/',[PageController::class,'home'])->name('home');
        Route::get('/profile',[PageController::class,'profile'])->name('profile');
        Route::get('/update_password',[PageController::class,'updatePassword'])->name('update.password');
        Route::post('/update_password',[PageController::class,'updatePasswordStore'])->name('store.password');

        Route::get('/wallet',[PageController::class,'wallet'])->name('wallet');
        Route::get('/transfer',[PageController::class,'transfer'])->name('transfer');
        Route::get('/transfer_confirm',[PageController::class,'transferConfirm'])->name('transfer_confirm');
        Route::post('/transfer_complete',[PageController::class,'transferComplete'])->name('transfer_complete');

        Route::get('/transaction',[PageController::class,'transaction'])->name('transaction');
        Route::get('/transaction/{trx_id}',[PageController::class,'transactionDetail'])->name('transaction_detail');

        Route::get('/to-account-info',[PageController::class,'verifyAccount'])->name('to_verify_info');
        Route::get('/password_check',[PageController::class,'passwordCheck'])->name('password_check');
        Route::get('/transfer_hash',[PageController::class,'transferHash'])->name('transfer_hash');

        Route::get('/recieve_qr',[PageController::class,'recieveQR'])->name('recieve_qr');
        Route::get('/scan_and_pay',[PageController::class,'scanAndPay'])->name('scan_and_pay');
        Route::get('/scan_and_pay_transfer',[PageController::class,'scanAndPayTransfer'])->name('scan_and_pay_transfer');
        Route::get('/scan_and_pay_transfer/confirm',[PageController::class,'scanAndPayTransferConfirm'])->name('scan_and_pay_transfer_confirm');
        Route::post('/scan_and_pay_transfer/complete',[PageController::class,'scanAndPayTransferComplete'])->name('scan_and_pay_transfer_complete');

        Route::get('/notification',[NotificationController::class,'notification'])->name('notification');
        Route::get('/notification/{id}',[NotificationController::class,'show'])->name('notification_show');
        

    });

});


