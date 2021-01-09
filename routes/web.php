<?php

use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

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

//Redirect
//Front-end links
Route::get('/', 'PagesController@index')->name('home');
Route::get('/about', 'PagesController@about');
Route::get('/news', 'PagesController@news');
Route::post('/news', 'PagesController@getNewsByFilters');
Route::get('/news/story/{id}', 'PagesController@showNewsStory');
Route::get('/events', 'PagesController@events');
Route::get('/event/{id}', 'PagesController@showEvent');
Route::post('/events', 'PagesController@getEventsByFilters');
Route::post('/events/register/guest', 'MailSend@registerEventGuest');
Route::post('/events/register', 'Events\EventsController@registerEventUser');
Route::get('/contact-us', 'PagesController@contact');
Route::post('/contact-submit', 'MailSend@contact_us');
Route::post('/subscribe', 'MailSend@subscribe');
Route::get('sub/verify/{token?}', 'MailSend@verified');
Route::get('event/verify/{token?}', 'MailSend@eventVerified');
Route::post('/event/reg/confirm', 'MailSend@validateEventToken');
Route::post('/user/create/setPassword/{id}', 'PagesController@createUserPassword');

//Auth Routes
Auth::routes(['verify' => true]);
Route::get('/register', 'Auth\RegisterController@index');
Route::post('/register', 'Auth\RegisterController@create');

Route::get('/member', 'MemberController@index')->name('member')->middleware('can:view-policy');
Route::post('/meeting/update', 'AdminPagesManagerController@updateMeeting')->middleware('can:manage-users');
Route::get('/users/resetPass', 'Admin\UsersController@displayResetUserPassword');
Route::post('/policy/download/{filename}', 'PoliciesController@downloadFile')->middleware('can:view-policy');
Route::post('/policy/delete/{filename}', 'PoliciesController@destroy')->middleware('can:manage-users');
Route::get('/policies', 'MemberController@viewPolicies')->middleware('can:view-policy');
Route::get('/events/dashboard', 'DashboardsController@event')->middleware('can:manage-events');

//Event Manager Links
Route::namespace('Events')->prefix('events')->name('events.')->middleware('can:manage-events')->group(function(){
    Route::get('/index', 'EventsController@index')->name('event');
    Route::get('/edit/{id}', 'EventsController@edit')->name('event-edit');
    Route::put('/edit/{id}', 'EventsController@update');
    Route::get('/registered/{id}', 'EventsController@showRegistered');
    Route::get('/create', 'EventsController@create');
    Route::post('/create', 'EventsController@store');
    Route::post('/delete', 'EventsController@destroy');
});

// Route::get('/events/dashboard', 'DashboardsController@events');

//User links
Route::get('/user/profile', 'AccountsController@profile')->middleware('auth');
Route::post('/user/profile', 'AccountsController@storeProfile')->middleware('auth');
Route::post('/user/profile/update', 'AccountsController@updateProfile')->middleware('auth');
Route::post('/user/profile/delete/{id}', 'AccountsController@deleteAccount')->middleware('auth');
Route::get('/user/events', 'AccountsController@events')->middleware('can:view-policy');
Route::get('/user/profile/create', 'AccountsController@createProfile')->middleware('auth');
Route::get('/user/committees/{id}', 'AccountsController@showCommittees')->middleware('can:view-policy');
Route::put('/user/committees/update/{id}', 'Admin\UsersController@updateUserCauses')->middleware('can:manage-users')->name('user.committees.update');
Route::get('/user/create/verify/{token?}', 'MailSend@validateUserToken');
Route::delete('cancel_reg/{id}', [
    'uses' => 'AccountsController@cancelReg'
])->middleware('auth');

//Author links
Route::namespace('News')->prefix('news')->name('news.')->middleware('can:manage-news')->group(function(){
Route::get('/index', 'NewsController@index');
Route::get('/edit/{id}', 'NewsController@edit')->name('edit-news');
Route::put('/edit/{id}', 'NewsController@update')->name('news-update');
Route::get('/create', 'NewsController@create');
Route::post('/create', 'NewsController@store');
Route::post('/delete', 'NewsController@destroy');
});

//Admin links
Route::get('/admin/contact', 'ContactController@index')->middleware('can:manage-users');
Route::get('/admin/userstest', 'Admin\UsersController@brdindex')->middleware('can:manage-users');
Route::get('/admin/dashboard', 'DashboardsController@admin');
Route::get('/policy-docs', 'PoliciesController@index')->middleware('can:manage-users');
Route::post('/policy/upload', 'PoliciesController@store')->middleware('can:manage-users');
Route::get('/policy/download/{filename}', 'PoliciesController@downloadFile')->name('downloadFile')->middleware('can:manage-users');
Route::post('admin/users/processResetPass', 'Admin\UsersController@resetUserPassword')->middleware('can:manage-users');
Route::get('/admin/getContactMessages', 'ContactController@getMessages')->middleware('can:manage-users');
Route::post('/admin/users/getByFilter', 'Admin\UsersController@getUsersByFilters')->middleware('can:manage-users');
Route::post('/admin/getUserCauses', 'Admin\UsersController@getUserCauses')->middleware('can:manage-users');
Route::get('/admin/getCommitteeGrowth', 'DashboardsController@getCommitteeGrowth')->middleware('can:manage-users');
Route::get('/admin/getSiteTraffic', 'DashboardsController@getSiteTraffic')->middleware('can:manage-users');
Route::get('/admin/contact/reply/{id}', 'ContactController@show')->middleware('can:manage-users');
Route::post('/admin/contact/reply/{id}', 'MailSend@contact_response')->middleware('can:manage-users');
Route::post('/admin/contact/close', 'ContactController@closeRequest')->middleware('can:manage-users');
Route::post('/admin/contact/flipToRead', 'ContactController@flipToRead')->middleware('can:manage-users');
Route::post('/admin/contact/flipToUnread', 'ContactController@flipToUnread')->middleware('can:manage-users');
Route::post('/admin/contact/delete', 'ContactController@destroy')->middleware('can:manage-users');
Route::post('/admin/contact/filter', 'ContactController@filter')->middleware('can:manage-users');
Route::get('/admin/events/applications', 'ApproveController@eventApplications')->middleware('can:manage-users');
Route::post('/admin/events/approve', 'ApproveController@approveEvent')->middleware('can:manage-users');
Route::post('/admin/events/amendApp', 'ApproveController@amendAppSubmit')->middleware('can:manage-users');
Route::delete('/admin/events/rejectApp/{id}', 'ApproveController@rejectApp')->middleware('can:manage-users');
Route::get('/admin/events/amend-application/{id}', 'ApproveController@amendApp')->middleware('can:manage-users');
Route::get('/test/event/widget', 'Events\EventsController@addwidget');
Route::post('/test/event/widget', 'Events\EventsController@insertwidget');
Route::post('/admin/users/createNew', 'MailSend@createdUserReg')->middleware('can:manage-users');
Route::namespace('Admin')->prefix('admin')->name('admin.')->middleware('can:manage-users')->group(function(){
    Route::resource('/users', 'UsersController', ['except' => ['show']]);
    Route::get('/users/resetPass', 'UsersController@displayResetUserPassword');
    Route::get('/users/{id}/edit', 'UsersController@edit');
});






