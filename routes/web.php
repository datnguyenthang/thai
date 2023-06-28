<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\Moderator\ModeratorController;
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
/*
Route::get('/', function () {
    return view('welcome');
});
*/
Route::get('/', [App\Http\Controllers\Home\HomeController::class, 'index'])->name('home');
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Auth::routes();

Route::get('/trip', App\Http\Livewire\Frontend\Homepage\Trip::class)->name('trip');
Route::post('/proceedbooking', App\Http\Livewire\Frontend\Homepage\ProceedBooking::class)->name('proceedbooking');
Route::get('/payment/{code}', App\Http\Livewire\Frontend\Homepage\Payment::class)->name('payment');


Route::get('/policy-for-customer', App\Http\Livewire\Frontend\Policy::class)->name('policycustomer');
Route::get('/privacy-policy', App\Http\Livewire\Frontend\PrivatePolicy::class)->name('privatepolicy');
/*
Route::get('/admin', [AdminController::class,'index'])->name('dashboard');
Route::get('/manager', [ManagerController::class,'index'])->name('dashboard');
Route::get('/moderator', [ModeratorController::class,'index'])->name('dashboard');
*/

/*---All Admin Routes List----*/

Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('dashboard');
});

/*------All Manager Routes List------*/

Route::middleware(['auth', 'user-access:manager'])->group(function () {
    Route::get('/manager', App\Http\Livewire\Backend\Manager\ManagerDashboard::class)->name('managerDashboard');

    Route::get('/user', App\Http\Livewire\Backend\Manager\ManagerListUser::class)->name('managerListUser');
    Route::get('/user/create/{userId}', App\Http\Livewire\Backend\Manager\ManagerCreateUser::class)->name('managerCreateUser');

    Route::get('/location', App\Http\Livewire\Backend\Manager\ManagerListLocation::class)->name('managerLocation');
    Route::get('/location/create/{locationId}', App\Http\Livewire\Backend\Manager\ManagerCreateLocation::class)->name('managerCreateLocation');

    Route::get('/agent', App\Http\Livewire\Backend\Manager\ManagerListAgent::class)->name('managerAgent');
    Route::get('/agent/create/{agentId}', App\Http\Livewire\Backend\Manager\ManagerCreateAgent::class)->name('managerCreateAgent');
    
    Route::get('/ride', App\Http\Livewire\Backend\Manager\ManagerListRide::class)->name('managerListRide');
    Route::get('/ride/create/{rideId}', App\Http\Livewire\Backend\Manager\ManagerCreateRide::class)->name('managerCreateRide');
    Route::get('/ride/massiveCreate', App\Http\Livewire\Backend\Manager\ManagerMassiveCreateRide::class)->name('massiveCreate');

    Route::get('/promotion', App\Http\Livewire\Backend\Manager\ManagerListPromotion::class)->name('managerPromotion');
    Route::get('/promotion/create/{promotionId}', App\Http\Livewire\Backend\Manager\ManagerCreatePromotion::class)->name('managerCreatePromotion');

    Route::get('/customertype', App\Http\Livewire\Backend\Manager\ManagerListCustomerType::class)->name('managerCustomerType');
    Route::get('/customertype/create/{customerTypeId}', App\Http\Livewire\Backend\Manager\ManagerCreateCustomerType::class)->name('managerCreateCustomerType');
});

/*------All Moderator Routes List------*/

Route::middleware(['auth', 'user-access:moderator'])->group(function () {
    Route::get('/moderator', App\Http\Livewire\Backend\Moderator\ModeratorDashboard::class)->name('moderatorDashboard');
    Route::get('/moderatororder', App\Http\Livewire\Backend\Moderator\ModeratorOrder::class)->name('moderatorOrder');
    Route::get('/moderatororderlist', App\Http\Livewire\Backend\Moderator\ModeratorOrderlist::class)->name('moderatorOrderlist');
});

/*------All Agent Routes List------*/

Route::middleware(['auth', 'user-access:agent'])->group(function () {
    Route::get('/agent', App\Http\Livewire\Backend\Moderator\ModeratorDashboard::class)->name('agentDashboard');
    Route::get('/agentorder', App\Http\Livewire\Backend\Agent\AgentOrder::class)->name('agentOrder');
    Route::get('/agentorderlist', App\Http\Livewire\Backend\Agent\AgentOrderList::class)->name('agentOrderlist');
});