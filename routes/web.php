<?php

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

Route::get('/','indexController@index');
Route::get('dashboard','indexController@dashboard');
Route::get('logout','indexController@logout');
Route::get('login','indexController@login');
Route::post('validatelogin','indexController@validatelogin');

//dashboard
Route::post('getexpensecategory','indexController@getExpenseCategory');
//role
/*Route::get('user-management/{item}','indexController@role');*/
Route::get('user-management-role','indexController@role');
Route::post('getuserrole','indexController@getuserrole');
Route::post('validateupdaterole','indexController@validateupdaterole');
Route::post('deleterole','indexController@deleterole');
Route::post('addrole','indexController@addrole');
//user
Route::get('user-management-user','indexController@user');
Route::post('getuserdetails','indexController@getuserdetails');
Route::post('validateupdateuser','indexController@validateupdateuser');
Route::post('displayuserrole','indexController@displayuserrole');
Route::post('deleteuser','indexController@deleteuser');
Route::post('adduser','indexController@adduser');
//expense category
Route::get('expense-category', 'indexController@getexpensecategorydetails');
Route::post('getexpensecategorydetails','indexController@getexpensecategories');
Route::post('validateupdatecategory','indexController@validateupdatecategory');
Route::post('validatedeletecategory','indexController@validatedeletecategory');
Route::post('validateaddcategory','indexController@validateaddcategory');
//expenses
Route::get('expenses', 'indexController@getexpenses');
Route::post('getexpensedetails', 'indexController@getexpensedetails');
Route::post('validateupdateexpenses', 'indexController@validateupdateexpenses');
Route::post('validatedeleteexpenses', 'indexController@validatedeleteexpenses');
Route::post('validateaddexpenses', 'indexController@validateaddexpenses');
//change password
Route::post('validatepassword', 'indexController@validatepassword');

