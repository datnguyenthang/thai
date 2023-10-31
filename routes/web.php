<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Auth::routes(['register' => false]);

Route::get('/trip', App\Http\Livewire\Frontend\Homepage\Trip::class)->name('trip');
Route::post('/proceedbooking', App\Http\Livewire\Frontend\Homepage\ProceedBooking::class)->name('proceedbooking');
Route::get('/payment/{code}', App\Http\Livewire\Frontend\Homepage\Payment::class)->name('payment');
Route::get('/payment_complete/{code}', App\Http\Livewire\Frontend\Homepage\PaymentComplete::class)->name('paymentComplete');

Route::get('/policy-for-customer', App\Http\Livewire\Frontend\Policy::class)->name('policycustomer');
Route::get('/privacy-policy', App\Http\Livewire\Frontend\PrivatePolicy::class)->name('privatepolicy');
Route::get('/aboutus', App\Http\Livewire\Frontend\Aboutus::class)->name('aboutus');
Route::get('/contactus', App\Http\Livewire\Frontend\Contactus::class)->name('contactus');

Route::middleware(['auth'])->group(function () {
    /*---User authenciation, EDIT, PROFILE----*/
    Route::get('/user/profile',  App\Http\Livewire\Component\User\Profile::class)->name('userprofile');
    Route::get('/user/edit',  App\Http\Livewire\Component\User\Edit::class)->name('useredit');

    //CMS only for creator, manager
    Route::get('/pagelist', App\Http\Livewire\Component\Cms\PageList::class)->name('pageList');
    Route::get('/createpage/{pageId}', App\Http\Livewire\Component\Cms\CreatePage::class)->name('createPage');

    //MANAGER USER only for admin and manager
    Route::get('/user', App\Http\Livewire\Component\Role\ListUser::class)->name('listUser');
    Route::get('/user/create/{userId}', App\Http\Livewire\Component\Role\CreateUser::class)->name('createUser');

    //Manage Agent for manager and moderator
    Route::get('/agentlist', App\Http\Livewire\Component\Agent\ListAgent::class)->name('agentList');
    Route::get('/agent/create/{agentId}', App\Http\Livewire\Component\Agent\CreateAgent::class)->name('createAgent');

    Route::get('/appearance', App\Http\Livewire\Component\Appearance\Customize::class)->name('customizeHomepage');
    Route::get('/settingseo', App\Http\Livewire\Component\Seo\SeoSetting::class)->name('settingSeo');

    Route::get('/menu', App\Http\Livewire\Component\Menu\ListMenu::class)->name('listMenu');
    Route::get('/menu/create/{menuId}', App\Http\Livewire\Component\Menu\CreateMenu::class)->name('createMenu');

    //REPORT
    Route::get('/dashboard', App\Http\Livewire\Component\Report\Dashboard::class)->name('dashboard');
    Route::get('/dailyreport', App\Http\Livewire\Component\Report\Daily::class)->name('dailyReport');
    Route::get('/monthlyreport', App\Http\Livewire\Component\Report\Monthly::class)->name('monthlyReport');
    Route::get('/yearlyreport', App\Http\Livewire\Component\Report\Yearly::class)->name('yearlyReport');
    Route::get('/saleperformance', App\Http\Livewire\Component\Report\Performance::class)->name('salePerformance');

    /*------All Manager Routes List------*/
    Route::middleware(['user-access:manager'])->group(function () {

        Route::get('/managerorder', App\Http\Livewire\Backend\Manager\ManagerOrder::class)->name('managerOrder');
        Route::get('/managerorderlist', App\Http\Livewire\Backend\Manager\ManagerOrderlist::class)->name('managerOrderlist');
        Route::get('/managerprocessorder/{orderId}', App\Http\Livewire\Backend\Manager\ManagerProcessOrder::class)->name('managerProcessOrder');

        Route::get('/location', App\Http\Livewire\Backend\Manager\ManagerListLocation::class)->name('managerLocation');
        Route::get('/location/create/{locationId}', App\Http\Livewire\Backend\Manager\ManagerCreateLocation::class)->name('managerCreateLocation');
        
        Route::get('/ride', App\Http\Livewire\Backend\Manager\ManagerListRide::class)->name('managerListRide');
        Route::get('/ride/create/{rideId}', App\Http\Livewire\Backend\Manager\ManagerCreateRide::class)->name('managerCreateRide');
        Route::get('/ride/massiveCreate', App\Http\Livewire\Backend\Manager\ManagerMassiveCreateRide::class)->name('massiveCreate');

        Route::get('/promotion', App\Http\Livewire\Backend\Manager\ManagerListPromotion::class)->name('managerPromotion');
        Route::get('/promotion/create/{promotionId}', App\Http\Livewire\Backend\Manager\ManagerCreatePromotion::class)->name('managerCreatePromotion');

        Route::get('/customertype', App\Http\Livewire\Backend\Manager\ManagerListCustomerType::class)->name('managerCustomerType');
        Route::get('/customertype/create/{customerTypeId}', App\Http\Livewire\Backend\Manager\ManagerCreateCustomerType::class)->name('managerCreateCustomerType');

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
        Route::get('/agentorder', App\Http\Livewire\Backend\Agent\AgentOrder::class)->name('agentOrder');
        Route::get('/agentorderlist', App\Http\Livewire\Backend\Agent\AgentOrderList::class)->name('agentOrderlist');
    });

    /*------All Viewer Routes List------*/
    Route::middleware(['user-access:viewer'])->group(function () {
        Route::get('/viewerorderlist', App\Http\Livewire\Backend\Viewer\ViewerOrderList::class)->name('viewerOrderlist');
        Route::get('/viewerride', App\Http\Livewire\Backend\Viewer\ViewerRide::class)->name('viewerRide');
        Route::get('/vieweragent', App\Http\Livewire\Backend\Viewer\ViewerAgent::class)->name('viewerAgent');
    });

    /*---All Admin Routes List----*/
    Route::middleware(['user-access:admin'])->group(function () {
        Route::get('/importorder', App\Http\Livewire\Backend\Admin\ImportOrder::class)->name('importOrder');
        Route::get('/adminorder', App\Http\Livewire\Backend\Admin\AdminOrder::class)->name('adminOrder');
        Route::get('/adminorderlist', App\Http\Livewire\Backend\Admin\AdminOrderlist::class)->name('adminOrderlist');
        Route::get('/adminprocessorder/{orderId}', App\Http\Livewire\Backend\Admin\AdminProcessOrder::class)->name('adminProcessOrder');
    });
});
