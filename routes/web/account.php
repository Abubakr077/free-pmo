<?php
/**
 * Installation Routes.
 */
Route::group(['middleware' => 'web'], function () {
    Route::get('app-install', ['as' => 'app.install', 'uses' => 'InstallationController@index']);
    Route::post('app-install', ['as' => 'app.install', 'uses' => 'InstallationController@store']);
});

// login with google
Route::get('/redirect', 'Auth\LoginController@redirectToProvider')->name('auth.google');;
Route::get('/callback', 'Auth\LoginController@handleProviderCallback')->name('auth.callback');;

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('auth.login');
Route::post('login', 'Auth\LoginController@doLogin');
Route::get('logout', 'Auth\LoginController@logout')->name('auth.logout');
Route::get('request-account', 'Auth\LoginController@request')->name('auth.request');
Route::post('store-account', 'Auth\LoginController@requestStore')->name('auth.store-account');
Route::get('pending', 'Users\UsersController@getUsers')->name('auth.pending-users');
Route::get('invites', 'Projects\InviteController@pending')->name('auth.pending-invites')->middleware(['auth']);
Route::get('approve/{user}', 'Users\UsersController@approvePending')->name('update.pending-users');


// Change Password Routes...
Route::get('change-password', 'Auth\ChangePasswordController@show')->name('auth.change-password');
Route::patch('change-password', 'Auth\ChangePasswordController@update')->name('auth.change-password');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.reset-request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('auth.reset-email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('reset-password');

// User's Profile routes
Route::get('profile', [
    'as'         => 'users.profile.show',
    'uses'       => 'Users\ProfileController@show',
    'middleware' => ['auth'],
]);

Route::get('profile/edit', [
    'as'         => 'users.profile.edit',
    'uses'       => 'Users\ProfileController@edit',
    'middleware' => ['auth'],
]);

Route::patch('profile/update', [
    'as'         => 'users.profile.update',
    'uses'       => 'Users\ProfileController@update',
    'middleware' => ['auth'],
]);

Route::patch('profile/switch-lang', [
    'as'         => 'users.profile.switch-lang',
    'uses'       => 'Users\ProfileController@switchLang',
    'middleware' => ['auth'],
]);

// User's Agency routes
Route::get('agency', [
    'as'         => 'users.agency.show',
    'uses'       => 'Users\AgencyController@show',
    'middleware' => ['auth'],
]);

Route::get('agency/edit', [
    'as'         => 'users.agency.edit',
    'uses'       => 'Users\AgencyController@edit',
    'middleware' => ['role:admin'],
]);

Route::patch('agency/update', [
    'as'         => 'users.agency.update',
    'uses'       => 'Users\AgencyController@update',
    'middleware' => ['role:admin'],
]);

Route::patch('agency/logo-upload', [
    'as'         => 'users.agency.logo-upload',
    'uses'       => 'Users\AgencyController@logoUpload',
    'middleware' => ['role:admin'],
]);
