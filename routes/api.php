<?php

use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\HarvesterInfoController;
use App\Http\Controllers\Api\HarvesterServiceController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\MenuPermissionController;
use App\Http\Controllers\Api\Mobile\CommonController;
use App\Http\Controllers\Api\Mobile\ServiceRequestController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SeasonalCropsController;
use App\Http\Controllers\Api\ServiceCenterController;
use App\Http\Controllers\Api\ServiceTypeController;
use App\Http\Controllers\Api\ServicingTypeController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerAuthController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('dashboard-login', [AuthController::class, 'dashboardLogin']);

Route::group(['middleware' => 'jwtauth:api'], function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    //user api route
    Route::apiResource('user', UserController::class);
    Route::get('search/user/{query}', [UserController::class, 'search']);
    Route::get('get-all-users/', [UserController::class, 'getAllUser']);
    Route::get('user-by-user-id', [UserController::class, 'getUserByUserId']);
    Route::post('user-profile-update', [UserController::class, 'updateProfile']);



    //menu resource route
    Route::apiResource('menu', MenuController::class);
    Route::get('search/menu/{query}', [MenuController::class, 'search']);
    Route::get('get-all-menu', [MenuController::class, 'getAllMenu']);

    //menu permission route
    Route::get('get-user-menu-details/{UserID}', [MenuPermissionController::class, 'getUserMenuPermission']);
    Route::get('sidebar-get-all-user-menu', [MenuPermissionController::class, 'getSidebarAllUserMenu']);
    Route::post('save-user-menu-permission', [MenuPermissionController::class, 'saveUserMenuPermission']);

    //customer resource route
    Route::apiResource('customer', CustomerController::class);
    Route::get('search/customer/{query}', [CustomerController::class, 'search']);

    //Role resource route
    Route::apiResource('role', RoleController::class);
    Route::get('search/role/{query}', [RoleController::class, 'search']);

    //Brand resource route
    Route::apiResource('brand', BrandController::class);
    Route::get('search/brand/{query}', [BrandController::class, 'search']);

    //change-password
    Route::post('change-password', [SettingController::class, 'changePassword']);

    //common route
    Route::get('get-all-brand', [CommonController::class, 'getAllBrand']);
    Route::get('get-all-user', [CommonController::class, 'getAllUser']);
    Route::get('get-all-customer', [CommonController::class, 'getAllCustomer']);
    Route::get('get-all-role', [CommonController::class, 'getAllRole']);
    Route::get('get-all-service-centers', [CommonController::class, 'getAllServiceCenter']);
    Route::get('get-all-areas', [CommonController::class, 'getAllArea']);
    Route::get('get-all-service-type', [CommonController::class, 'getAllServiceType']);
    Route::get('get-all-servicing-type', [CommonController::class, 'getAllServicingType']);
    Route::get('get-all-product-model', [CommonController::class, 'getAllProductModel']);
    Route::get('get-all-products', [CommonController::class, 'getAllProducts']);
    Route::get('get-all-harvester-service-details', [CommonController::class, 'getAllHarvesterServiceDetails']);
    Route::get('get-all-harvester-info', [CommonController::class, 'getAllHarvesterInfo']);
    Route::get('get-all-crops', [CommonController::class, 'getAllCrops']);
    Route::get('get-all-districts', [CommonController::class, 'getAllDistricts']);
    Route::get('get-all-district-wise-seasonal-crops/{id}', [CommonController::class, 'getAllDistrictWiseSeasonalCrops']);
    Route::get('get-all-model-by-product/{id}', [CommonController::class, 'getAllModelByProduct']);

    //get dashboard data route
    Route::get('get-all-dashboard-data', [DashboardController::class, 'getDashboardAllDara']);
    Route::get('check-expired', [DashboardController::class, 'checkExpired']);

    //admin dashboard
    Route::get('get-all-admin-dashboard-data', [AdminDashboardController::class, 'getAdminDashboardAllData']);

    //ServiceCenter
    Route::apiResource('service-centers', ServiceCenterController::class);
    Route::get('search/service-centers/{query}', [ServiceCenterController::class, 'search']);

    //ServiceCenter
    Route::apiResource('service-types', ServiceTypeController::class);
    Route::get('search/service-types/{query}', [ServiceTypeController::class, 'search']);

    //ServiceCenter
    Route::apiResource('servicing-types', ServicingTypeController::class);
    Route::get('search/servicing-types/{query}', [ServicingTypeController::class, 'search']);

    //HarvesterServiceRequest
    Route::apiResource('harvester-service-details', HarvesterServiceController::class);
    Route::get('search/harvester-service-details/{query}', [HarvesterServiceController::class, 'search']);

    //HarvesterInfoRequest
    Route::apiResource('harvester-info', HarvesterinfoController::class);
    Route::get('search/harvester-info/{query}', [HarvesterInfoController::class, 'search']);


    //District wise seasonal crops
    Route::apiResource('district-wise-seasonal-crops', SeasonalCropsController::class);
    Route::get('search/district-wise-seasonal-crops/{query}', [SeasonalCropsController::class, 'search']);



    Route::get('get-all-district-wise-seasonal-crops/{id}', [CommonController::class, 'getAllDistrictWiseSeasonalCrops']);

});

Route::get('get-all-districts', [CommonController::class, 'getAllDistricts']);

//For Customer
//Customer Login
Route::post('customer-login', [CustomerAuthController::class, 'login']);
Route::post('send-otp-for-registration', [CustomerAuthController::class, 'sendOtp']);
Route::post('check-otp-for-registration', [CustomerAuthController::class, 'checkOtp']);
Route::post('customer-registration', [CustomerAuthController::class, 'registration']);
Route::post('verify-chassis-number', [CustomerAuthController::class, 'findChassisNumber']);
Route::post('find-mobile', [CustomerAuthController::class, 'findMobile']);

Route::group(['middleware' => 'CustomerAuth'], function () {
    Route::post('auth/profile-update', [CustomerAuthController::class, 'updateProfile']);
    Route::post('change-password', [CustomerAuthController::class, 'changePassword']);

    Route::post('customer-service-request', [ServiceRequestController::class, 'customerServiceRequest']);
    //District wise seasonal crops

    //Route::apiResource('service-request', ServiceRequestController::class);

});


