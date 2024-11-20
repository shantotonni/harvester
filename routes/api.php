<?php

use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DealerController;
use App\Http\Controllers\Api\FuelPumpController;
use App\Http\Controllers\Api\HarvesterInfoController;
use App\Http\Controllers\Api\HarvesterPartsController;
use App\Http\Controllers\Api\HarvesterServiceController;
use App\Http\Controllers\Api\HarvestingCostController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\MenuPermissionController;
use App\Http\Controllers\Api\Mobile\CommonController;
use App\Http\Controllers\Api\ProductModelController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SeasonalCropsController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\ServiceCenterController;
use App\Http\Controllers\Api\ServiceEngineerController;
use App\Http\Controllers\Api\ServiceRequestController;
use App\Http\Controllers\Api\ServiceTipsController;
use App\Http\Controllers\Api\ServiceTypeController;
use App\Http\Controllers\Api\ServicingTypeController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ShowroomController;
use App\Http\Controllers\Api\SmartAssistController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\PushNotificationController;
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
    Route::get('get-all-mirror-product', [CommonController::class, 'getAllMirrorProduct']);
    Route::post('get-all-mirror-price/{ProductCode}', [CommonController::class, 'getAllPriceByMirror']);
    Route::get('get-all-sections', [CommonController::class, 'getAllSections']);
    Route::get('get-all-harvester-service-details', [CommonController::class, 'getAllHarvesterServiceDetails']);
    Route::get('get-all-harvester-info', [CommonController::class, 'getAllHarvesterInfo']);
    Route::get('get-all-harvester-parts', [CommonController::class, 'getAllHarvesterParts']);
    Route::get('get-all-crops', [CommonController::class, 'getAllCrops']);
    Route::get('get-all-districts', [CommonController::class, 'getAllDistricts']);
    Route::get('get-all-upazilla-by-district', [CommonController::class, 'getAllUpazillaByDistricts']);
    Route::get('get-all-showrooms', [CommonController::class, 'getAllShowroom']);
    Route::post('get-all-district-wise-seasonal-crops', [CommonController::class, 'getAllDistrictWiseSeasonalCrops']);
    Route::get('get-all-model-by-product/{id}', [CommonController::class, 'getAllModelByProduct']);
    Route::get('get-all-service-tips', [CommonController::class, 'getAllServiceTips']);
    Route::get('get-all-service-engineer', [CommonController::class, 'getAllServiceEngineer']);
    Route::get('get-all-fuel-pump', [CommonController::class, 'getAllFuelPump']);
    Route::get('get-all-dealer', [CommonController::class, 'getAllDealer']);
    Route::get('get-all-pending-service-request-list', [CommonController::class, 'getAllPendingServiceRequestList']);
    Route::get('get-all-completed-service-request-list', [CommonController::class, 'getAllCompletedServiceRequestList']);
    Route::get('get-all-service-request-details-list', [CommonController::class, 'getAllServiceRequestDetailsList']);
    Route::get('get-all-technician', [CommonController::class, 'getAllTechnician']);
    Route::get('get-all-engineer', [CommonController::class, 'getAllEngineer']);
    Route::get('get-all-section', [CommonController::class, 'getAllSectionList']);

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

    //HarvesterParts
    Route::apiResource('harvester-parts', HarvesterPartsController::class);
    Route::get('search/harvester-parts{query}', [HarvesterPartsController::class, 'search']);

    //District wise seasonal crops
    Route::apiResource('district-wise-seasonal-crops', SeasonalCropsController::class);
    Route::get('search/district-wise-seasonal-crops/{query}', [SeasonalCropsController::class, 'search']);

    //Showroom
    Route::apiResource('showroom-list', ShowroomController::class);
    Route::get('search/showroom-list/{query}', [ShowroomController::class, 'search']);

    //Service Tips
    Route::apiResource('service-tips', ServiceTipsController::class);
    Route::get('search/service-tips/{query}', [ServiceTipsController::class, 'search']);

    //Service Tips
    Route::apiResource('service-engineer', ServiceEngineerController::class);
    Route::get('search/service-engineer/{query}', [ServiceEngineerController::class, 'search']);

    //Pending Service List
    Route::get('pending-service-request-list', [ServiceRequestController::class,'pendingJobCard'])->name('pendingRequest');
    Route::get('search/pending-service-request-list	/{query}', [ServiceRequestController::class, 'search']);
    Route::post('assign-technician', [ServiceRequestController::class, 'assignTechnician']);

    //Completed Service List
    Route::get('completed-service-request-list', [ServiceRequestController::class,'approveJobCard'])->name('completeRequest');
    Route::get('search/completed-service-request-list/{query}', [ServiceRequestController::class, 'search']);

    //Service Details
    Route::get('/service-request-details/{id}', [ServiceRequestController::class,'serviceRequestDetails']);

    //Section
    Route::apiResource('section-list', SectionController::class);
    Route::get('search/section-list/{query}', [SectionController::class, 'search']);
    //Push Notification
    Route::apiResource('notification-list', PushNotificationController::class);
    Route::get('search/notification-list/{query}', [PushNotificationController::class, 'search']);
    Route::get('get-all-notification', [PushNotificationController::class, 'getAllNotification']);
    //product model
    Route::apiResource('product-model', ProductModelController::class);
    Route::get('search/product-model/{query}', [ProductModelController::class, 'search']);

    //harvesting cost
    Route::apiResource('harvesting-cost', HarvestingCostController::class);
    Route::get('search/harvesting-cost/{query}', [HarvestingCostController::class, 'search']);

    //Smart Assist
    Route::apiResource('smart-assist-list', SmartAssistController::class);
    Route::get('search/smart-assist-list/{query}', [SmartAssistController::class, 'search']);
    //fuel pump
    Route::apiResource('fuel-pump-list', FuelPumpController::class);
    Route::get('search/fuel-pump-list/{query}', [FuelPumpController::class, 'search']);
    //dealer
    Route::apiResource('dealer-list', DealerController::class);
    Route::get('search/dealer-list/{query}', [DealerController::class, 'search']);

    Route::post('add-additional-chassis', [CustomerController::class, 'addChassis']);

    //customer status customer-status
    Route::post('customer-status', [CustomerController::class, 'customerStatus']);

});

//For Customer Login
Route::post('customer-login', [CustomerAuthController::class, 'login']);
Route::post('send-otp-for-registration', [CustomerAuthController::class, 'sendOtp']);
Route::post('check-otp-for-registration', [CustomerAuthController::class, 'checkOtp']);
Route::post('customer-registration', [CustomerAuthController::class, 'registration']);
Route::post('verify-chassis-number', [CustomerAuthController::class, 'findChassisNumber']);
Route::post('find-mobile', [CustomerAuthController::class, 'findMobile']);
Route::get('get_districts_upazilla', [CommonController::class, 'getAllDistrictsUpazilla']);

Route::post('send-otp-for-forgot-password', [CustomerAuthController::class, 'sendOtpForForgotPassword']);
Route::post('verify-otp-for-forgot-password', [CustomerAuthController::class, 'verifyOtpForForgotPassword']);
Route::post('customer-forgot-password', [CustomerAuthController::class, 'customerForgotPassword']);


Route::group(['middleware' => 'CustomerAuth'], function () {
    //get data
    Route::get('get-all-problem-section', [CommonController::class, 'getAllProblemSection']);
    Route::get('get-all-harvester-cost', [CommonController::class, 'getAllHarvesterCost']);

    //service request
    Route::post('customer-service-request', [App\Http\Controllers\Api\Mobile\ServiceRequestController::class,'customerServiceRequest']);
    Route::get('get-all-customer-service-request', [App\Http\Controllers\Api\Mobile\ServiceRequestController::class,'getAllCustomerServiceRequest']);

    Route::post('auth/profile-update', [CustomerAuthController::class, 'updateProfileMobile']);
    Route::post('customer-password-change', [CustomerAuthController::class, 'changePassword']);

    Route::post('customer-service-request', [App\Http\Controllers\Api\Mobile\ServiceRequestController::class, 'customerServiceRequest']);
    //District wise seasonal crops

    Route::post('/harvester-warranty-parts', [App\Http\Controllers\Api\Mobile\CustomerController::class,'warrantyParts']);

    Route::post('/harvester-smart-assist', [App\Http\Controllers\Api\Mobile\CustomerController::class,'harvesterSmartAssist']);
    Route::get('/get-customer-profile', [App\Http\Controllers\Api\Mobile\CustomerController::class,'getCustomerProfile']);
    Route::post('/customer-feedback', [App\Http\Controllers\Api\Mobile\CustomerController::class,'customerFeedback']);
    Route::get('/get-harvester-warranty', [App\Http\Controllers\Api\Mobile\ServiceRequestController::class,'getHarvesterWarranty']);

});


