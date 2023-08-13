<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\Moderator\ModeratorController;
use Illuminate\Support\Facades\DB;
use MSA\LaravelGrapes\Http\Controllers\FrontendController;
use App\Http\Controllers\OmiseWebhookController;
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

$page = DB::table('menu_items')
    ->join('pages', 'menu_items.page_id', '=', 'pages.id')
    ->where('menu_items.name', '=', 'home')
    ->where('menu_items.status', ACTIVE)
    ->first();

if ($page) {
    if($page->url) Route::get('/', '/'.$page->url.'')->name('home');

    $methodName = implode('', array_map('ucwords', explode('-', $page->slug)));
    if($page->page_id) Route::get('/', [FrontendController::class, ''.$methodName.''])->name('home');
} else {
    Route::get('/', App\Http\Livewire\Frontend\Homepage\Booking::class)->name('home');
}

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/login', '\App\Http\Controllers\Auth\LoginController@logout');

//404 page
Route::get('/404', function () {
    return response()->view('errors.404', [], 404);
});
//500 page
Route::get('/500', function () {
    return response()->view('errors.500', [], 500);
});

//OLD PAGE
Route::get('/timetable', '\App\Http\Controllers\Home\HomeController@timetable')->name('timetable');

Auth::routes();

Route::get('/trip', App\Http\Livewire\Frontend\Homepage\Trip::class)->name('trip');
Route::post('/proceedbooking', App\Http\Livewire\Frontend\Homepage\ProceedBooking::class)->name('proceedbooking');
Route::get('/payment/{code}', App\Http\Livewire\Frontend\Homepage\Payment::class)->name('payment');

Route::get('/policy-for-customer', App\Http\Livewire\Frontend\Policy::class)->name('policycustomer');
Route::get('/privacy-policy', App\Http\Livewire\Frontend\PrivatePolicy::class)->name('privatepolicy');
Route::get('/aboutus', App\Http\Livewire\Frontend\Aboutus::class)->name('aboutus');
Route::get('/contactus', App\Http\Livewire\Frontend\Contactus::class)->name('contactus');

//handle event from Omise
Route::post('/omise-webhook', [OmiseWebhookController::class, 'handleWebhook']);



Route::middleware(['auth'])->group(function () {
    //User profile
    Route::get('/user/profile',  App\Http\Livewire\Component\User\Profile::class)->name('userprofile');
    Route::get('/user/edit',  App\Http\Livewire\Component\User\Edit::class)->name('useredit');

    /*---All Admin Routes List----*/
    Route::middleware(['user-access:admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('dashboard');
    });

    /*------All Manager Routes List------*/
    Route::middleware(['user-access:manager'])->group(function () {
        Route::get('/manager', App\Http\Livewire\Backend\Manager\ManagerDashboard::class)->name('managerDashboard');

        Route::get('/managercms', App\Http\Livewire\Backend\Manager\ManagerCms::class)->name('managerCms');
        Route::get('/cms/create/{pageId}', App\Http\Livewire\Backend\Manager\ManagerCreateCms::class)->name('managerCreateCms');

        Route::get('/managerorder', App\Http\Livewire\Backend\Manager\ManagerOrder::class)->name('managerOrder');
        Route::get('/managerorderlist', App\Http\Livewire\Backend\Manager\ManagerOrderlist::class)->name('managerOrderlist');
        Route::get('/managerprocessorder/{orderId}', App\Http\Livewire\Backend\Manager\ManagerProcessOrder::class)->name('managerProcessOrder');

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

        Route::get('/menu', App\Http\Livewire\Backend\Manager\ManagerListMenu::class)->name('managerMenu');
        Route::get('/menu/create/{menuId}', App\Http\Livewire\Backend\Manager\ManagerCreateMenu::class)->name('managerCreateMenu');

        Route::get('/pkdp', App\Http\Livewire\Backend\Manager\ManagerListPickupDropoff::class)->name('managerPkdp');
        Route::get('/pkdp/create/{pkdpId}', App\Http\Livewire\Backend\Manager\ManagerCreatePickupDropoff::class)->name('managerCreatePkdp');
    });

    /*------All Moderator Routes List------*/
    Route::middleware(['user-access:moderator'])->group(function () {
        Route::get('/moderator', App\Http\Livewire\Backend\Moderator\ModeratorDashboard::class)->name('moderatorDashboard');
        Route::get('/moderatororder', App\Http\Livewire\Backend\Moderator\ModeratorOrder::class)->name('moderatorOrder');
        Route::get('/moderatororderlist', App\Http\Livewire\Backend\Moderator\ModeratorOrderlist::class)->name('moderatorOrderlist');
        Route::get('/moderatorprocessorder/{orderId}', App\Http\Livewire\Backend\Moderator\ModeratorProcessOrder::class)->name('moderatorProcessOrder');
    });

    /*------All Agent Routes List------*/
    Route::middleware(['user-access:agent'])->group(function () {
        Route::get('/agentdashboard', App\Http\Livewire\Backend\Agent\AgentDashboard::class)->name('agentDashboard');
        Route::get('/agentorder', App\Http\Livewire\Backend\Agent\AgentOrder::class)->name('agentOrder');
        Route::get('/agentorderlist', App\Http\Livewire\Backend\Agent\AgentOrderList::class)->name('agentOrderlist');
    });

/*
    Route::middleware(['user-access:manager|moderator'])->group(function () {dd(auth()->user());
        if (auth()->user()->role == 'moderator')
            Route::get('order', [App\Http\Livewire\Backend\Moderator\ModeratorDashboard::class, 'render'])->name('order');
        if (auth()->user()->role == 'manager')
            Route::get('order', [App\Http\Livewire\Backend\Manager\ManagerOrder::class, 'render'])->name('order');
    });
*/
});
