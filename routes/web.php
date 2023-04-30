<?php
/* if (isset($_SERVER['HTTP_ORIGIN'])) {
    // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
    // whitelist of safe domains
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
    header('X-Frame-Options: *');
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	header('X-Frame-Options: *');
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
} */
DB::enableQueryLog() ;
include(app_path().'/global_constants.php');
include(app_path().'/settings.php');
require_once(APP_PATH.'/libraries/CustomHelper.php');

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

	/* 	Route::get('/', function () {
			return view('welcome');
		}); */
// Route::group('/', fun)
Route::get('/',function () {
    return redirect()->route('login');
});


################################################################# Admin Routing start here###################################################
Route::group(array('prefix' => 'adminpnlx'), function() {
    Route::group(array('middleware' => 'App\Http\Middleware\GuestAdmin','namespace'=>'adminpnlx'),function(){
        Route::get('',array('as'=>'login','uses'=>'AdminLoginController@login'));
        Route::any('/login','AdminLoginController@login');
        Route::get('forget_password','AdminLoginController@forgetPassword');
        Route::post('send_password','AdminLoginController@sendPassword');
        Route::get('reset_password/{validstring}','AdminLoginController@resetPassword');
        Route::post('save_password','AdminLoginController@resetPasswordSave');
        // Route::get('custom_login/{username}',array('uses'=>'UsersController@custom_login'));
    });

    Route::group(array('middleware' => 'App\Http\Middleware\AuthAdmin','namespace'=>'adminpnlx'), function() {
        Route::get('/logout','AdminLoginController@logout');
        Route::get('dashboard' ,array('as'=>'dashboard','uses'=>'AdminDashboardController@showdashboard'));
        Route::get('/myaccount','AdminDashboardController@myaccount');
        Route::post('/myaccount','AdminDashboardController@myaccountUpdate');
        Route::get('/change-password','AdminDashboardController@change_password');
        Route::post('/changed-password','AdminDashboardController@changedPassword');

        /** Service routing **/
        Route::any('/service',array('as'=>'Service.index','uses'=>'ServiceController@index'));
        Route::get('service/add-new-service',array('as'=>'Service.add','uses'=>'ServiceController@add'));
        Route::post('service/add-new-service',array('as'=>'Service.add','uses'=>'ServiceController@save'));
        Route::get('service/update-service-status/{id}',array('as'=>'Service.status','uses'=>'ServiceController@changeStatus'));
        Route::get('service/edit-new-service/{id}',array('as'=>'Service.edit','uses'=>'ServiceController@edit'));
        Route::post('service/edit-new-service/{id}',array('as'=>'Service.edit','uses'=>'ServiceController@update'));
        Route::get('service/delete-service/{id}',array('as'=>'Service.delete','uses'=>'ServiceController@delete'));


        /* cms-manager routes */
        Route::get('cms-manager',array('as'=>'Cms.index','uses'=>'CmspagesController@index'));
        Route::post('cms-manager',array('as'=>'Cms.index','uses'=>'CmspagesController@index'));
        Route::get('cms-manager/view/{id}',array('as'=>'Cms.view','uses'=>'CmspagesController@view'));
        Route::get('cms-manager/edit/{id}',array('as'=>'Cms.edit','uses'=>'CmspagesController@edit'));
        Route::post('cms-manager/edit/{id}',array('as'=>'Cms.edit','uses'=>'CmspagesController@update'));
        /* cms-manager routes ends */


        /** Links routing**/
        Route::any('/links',array('as'=>'Links.index','uses'=>'LinksController@index'));
        Route::get('/links/{id}',array('as'=>'Links.edit','uses'=>'LinksController@edit'));
        Route::post('/links/{id}',array('as'=>'Links.edit','uses'=>'LinksController@update'));


        /** Colors routing**/
        Route::any('/colors',array('as'=>'Colors.index','uses'=>'ColorsController@index'));
        Route::get('/colors/{id}',array('as'=>'Colors.edit','uses'=>'ColorsController@edit'));
        Route::post('/colors/{id}',array('as'=>'Colors.edit','uses'=>'ColorsController@update'));


        /** Banner routing**/
        Route::any('/banners',array('as'=>'Banners.index','uses'=>'BannersController@index'));
        Route::get('/banners/{id}',array('as'=>'Banners.edit','uses'=>'BannersController@edit'));
        Route::post('/banners/{id}',array('as'=>'Banners.edit','uses'=>'BannersController@update'));


        /** Blocks routing**/
        Route::any('/blocks',array('as'=>'Blocks.index','uses'=>'BlocksController@index'));
        Route::get('/blocks/{id}',array('as'=>'Blocks.edit','uses'=>'BlocksController@edit'));
        Route::post('/blocks/{id}',array('as'=>'Blocks.edit','uses'=>'BlocksController@update'));

        /** Weeding routing**/
        Route::any('/wedding-location',array('as'=>'WeddingLocation.index','uses'=>'WeddingLocationController@index'));
        Route::any('/wedding-location/add-wedding-location',array('as'=>'WeddingLocation.add','uses'=>'WeddingLocationController@add'));
        Route::post('/wedding-location/add-wedding-location',array('as'=>'WeddingLocation.add','uses'=>'WeddingLocationController@save'));
        Route::any('/wedding-location/edit-wedding-location/{id}',array('as'=>'WeddingLocation.edit','uses'=>'WeddingLocationController@edit'));
        Route::post('/wedding-location/edit-wedding-location/{id}',array('as'=>'WeddingLocation.edit','uses'=>'WeddingLocationController@update'));
        Route::get('wedding-location/delete/{id}',array('as'=>'WeddingLocation.delete','uses'=>'WeddingLocationController@delete'));

        /** Reviews Location**/
        Route::any('/reviews',array('as'=>'Reviews.index','uses'=>'ReviewController@index'));
        Route::any('/reviews/add-reviews',array('as'=>'Reviews.add','uses'=>'ReviewController@add'));
        Route::post('/reviews/add-reviews',array('as'=>'Reviews.add','uses'=>'ReviewController@save'));
        Route::any('/reviews/edit-reviews/{id}',array('as'=>'Reviews.edit','uses'=>'ReviewController@edit'));
        Route::post('/reviews/edit-reviews/{id}',array('as'=>'Reviews.edit','uses'=>'ReviewController@update'));
        Route::get('reviews/delete/{id}',array('as'=>'Reviews.delete','uses'=>'ReviewController@delete'));

        /** Faq Location**/
        Route::any('/faq',array('as'=>'Faq.index','uses'=>'FaqController@index'));
        Route::any('/faq/add-faq',array('as'=>'Faq.add','uses'=>'FaqController@add'));
        Route::post('/faq/add-faq',array('as'=>'Faq.add','uses'=>'FaqController@save'));
        Route::any('/faq/edit-faq/{id}',array('as'=>'Faq.edit','uses'=>'FaqController@edit'));
        Route::post('/faq/edit-faq/{id}',array('as'=>'Faq.edit','uses'=>'FaqController@update'));
        Route::get('faq/delete/{id}',array('as'=>'Faq.delete','uses'=>'FaqController@delete'));



        /** settings routing**/
        Route::any('/settings',array('as'=>'settings.listSetting','uses'=>'SettingsController@listSetting'));
        Route::get('/settings/add-setting','SettingsController@addSetting');
        Route::post('/settings/add-setting','SettingsController@saveSetting');
        Route::get('/settings/edit-setting/{id}','SettingsController@editSetting');
        Route::post('/settings/edit-setting/{id}','SettingsController@updateSetting');
        Route::get('/settings/prefix/{slug}','SettingsController@prefix');
        Route::post('/settings/prefix/{slug}','SettingsController@updatePrefix');
        Route::delete('/settings/delete-setting/{id}','SettingsController@deleteSetting');
        /** settings routing**/


        /** Software Partner routing**/
        Route::any('/software-partner',array('as'=>'SoftwarePartner.index','uses'=>'SoftwarePartnerController@index'));
        Route::any('/software-partner/add-software-partner',array('as'=>'SoftwarePartner.add','uses'=>'SoftwarePartnerController@add'));
        Route::post('/software-partner/add-software-partner',array('as'=>'SoftwarePartner.add','uses'=>'SoftwarePartnerController@save'));
        Route::any('/software-partner/edit-software-partner/{id}',array('as'=>'SoftwarePartner.edit','uses'=>'SoftwarePartnerController@edit'));
        Route::post('/software-partner/edit-software-partner/{id}',array('as'=>'SoftwarePartner.edit','uses'=>'SoftwarePartnerController@update'));
        Route::get('software-partner/delete/{id}',array('as'=>'SoftwarePartner.delete','uses'=>'SoftwarePartnerController@delete'));
        /** Software Partner routing**/

            /** Blogs routing**/
        Route::any('/blogs',array('as'=>'Blogs.index','uses'=>'BlogsController@index'));
        Route::any('/blogs/add-blogs',array('as'=>'Blogs.add','uses'=>'BlogsController@add'));
        Route::post('/blogs/add-blogs',array('as'=>'Blogs.add','uses'=>'BlogsController@save'));
        Route::any('/blogs/edit-blogs/{id}',array('as'=>'Blogs.edit','uses'=>'BlogsController@edit'));
        Route::post('/blogs/edit-blogs/{id}',array('as'=>'Blogs.edit','uses'=>'BlogsController@update'));
        Route::get('blogs/delete/{id}',array('as'=>'Blogs.delete','uses'=>'BlogsController@delete'));

        /** Certificates routing**/
        Route::any('/certificates',array('as'=>'Certificates.index','uses'=>'CertificateController@index'));
        Route::get('/certificates/{id}',array('as'=>'Certificates.edit','uses'=>'CertificateController@edit'));
        Route::post('/certificates/{id}',array('as'=>'Certificates.edit','uses'=>'CertificateController@update'));

        /** Sub Services routing**/
        Route::any('/sub-services',array('as'=>'SubServices.index','uses'=>'SubServicesController@index'));
        Route::any('/sub-services/add-sub-services',array('as'=>'SubServices.add','uses'=>'SubServicesController@add'));
        Route::post('/sub-services/add-sub-services',array('as'=>'SubServices.add','uses'=>'SubServicesController@save'));
        Route::any('/sub-services/edit-sub-services/{id}',array('as'=>'SubServices.edit','uses'=>'SubServicesController@edit'));
        Route::post('/sub-services/edit-sub-services/{id}',array('as'=>'SubServices.edit','uses'=>'SubServicesController@update'));
        Route::get('sub-services/sub-services-status/{id}/{status}',array('as'=>'SubServices.status','uses'=>'SubServicesController@changeStatus'));
        Route::get('sub-services/delete/{id}',array('as'=>'SubServices.delete','uses'=>'SubServicesController@delete'));


        /* Route::get('login', [AuthController::class, 'index'])->name('login');
        Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post'); 
        Route::get('registration', [AuthController::class, 'registration'])->name('register');
        Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');  
        Route::get('logout', [AuthController::class, 'logout'])->name('logout'); */
        Route::any('/returnedtracker/index', array('as'=>'ReturnedController.index','uses'=>'ReturnedController@index'));
        Route::post('/returnedtracker/update', array('as'=>'ReturnedController.update','uses'=>'ReturnedController@update')); 
        Route::get('/onboardings/onboardings_hr/index',array('as'=>'OnboardingsHrController.index','uses'=>'OnboardingsHrController@index'));
        Route::get('/onboardings/onboardings_hr/update',array('as'=>'OnboardingsHrController.update','uses'=>'OnboardingsHrController@update'));
        Route::post('/onboardings/onboardings_hr/onboardedit', array('as'=>'OnboardingsHrController.onboardedit','uses'=>'OnboardingsHrController@onboardedit'));
        //Route::put('/onboardupdate/{id}', [OnboardingsHrController::class, 'onboardupdate'])->name('onboardupdate');
        //Route::put('/onboardings/onboardings_hr/onboardupdate/{id}', array('as'=>'OnboardingsHrController.onboardupdate','uses'=>'OnboardingsHrController@onboardupdate'));
        Route::post('/onboardingsHr/onboardupdate/{id}', array('as'=>'onboardupdate','uses'=>'OnboardingsHrController@onboardupdate'));
        //Route::get('/onboardings/onboardsuccess',  array('as'=>'OnboardingsHrController.onboardsuccess','uses'=>'OnboardingsHrController@onboardsuccess'));
        Route::get('creditmis/index', array('as'=>'CreditMisController.index','uses'=>'CreditMisController@index'));
        Route::post('creditmis/update',  array('as'=>'CreditMisController.update','uses'=>'CreditMisController@update'));
    });
});
##################################### Admin Routing end here#######################################
