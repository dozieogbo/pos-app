<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::put('categories/{id}/toggle/{status}', ['uses' => 'CategoryController@toggleStatus', 'as' => 'categories.status']);
Route::get('categories/active', ['uses' => 'CategoryController@active', 'as' => 'categories.active']);

Route::put('states/{id}/toggle/{status}', ['uses' => 'StateController@toggleStatus', 'as' => 'states.status']);
Route::get('states/active', ['uses' => 'StateController@active', 'as' => 'states.active']);

Route::put('branches/{id}/toggle/{status}', ['uses' => 'BranchController@toggleStatus', 'as' => 'branches.status']);
Route::get('branches/active', ['uses' => 'BranchController@active', 'as' => 'branches.active']);

Route::put('units/{id}/toggle/{status}', ['uses' => 'UnitController@toggleStatus', 'as' => 'units.status']);
Route::get('units/active', ['uses' => 'UnitController@active', 'as' => 'units.active']);

Route::put('suppliers/{id}/toggle/{status}', ['uses' => 'SupplierController@toggleStatus', 'as' => 'suppliers.status']);
Route::get('suppliers/active', ['uses' => 'SupplierController@active', 'as' => 'suppliers.active']);

Route::get('staffs/branch/{branch}', ['uses' => 'StaffController@index', 'as' => 'staffs.branch']);
Route::get('staffs/branch/{branch}/active', ['uses' => 'StaffController@active', 'as' => 'staffs.branch.active']);
Route::get('staffs/active', ['uses' => 'StaffController@active', 'as' => 'staffs.active']);

Route::get('products/category/{category}', ['uses' => 'ProductController@index', 'as' => 'products.category']);
Route::get('products/category/{category}/active', ['uses' => 'ProductController@active', 'as' => 'products.category.active']);
Route::get('products/active', ['uses' => 'ProductController@active', 'as' => 'products.active']);
Route::post('products/{id}/arrivals', ['uses' => 'ProductController@addArrival', 'as' => 'products.arrivals.add']);
Route::get('products/arrivals', ['uses' => 'ProductController@getArrivals', 'as' => 'products.arrivals']);
Route::get('products/{id}/arrivals', ['uses' => 'ProductController@getArrivals', 'as' => 'products.arrivals.single']);
Route::post('products/{id}/returns', ['uses' => 'ProductController@addReturn', 'as' => 'products.returns.add']);
Route::get('products/returns', ['uses' => 'ProductController@getReturns', 'as' => 'products.returns']);
Route::get('products/{id}/returns', ['uses' => 'ProductController@getReturns', 'as' => 'products.returns.single']);

Route::apiResources([
    'settings' => 'SettingsController',
    'flags' => 'FlagController',
    'products' => 'ProductController',
    'branches' => 'BranchController',
    'categories' => 'CategoryController',
    'states' => 'StateController',
    'staffs' => 'StaffController',
    'suppliers' => 'SupplierController',
    'units' => 'UnitController',
    'customers' => 'CustomerController',
]);

Route::resource( 'purchases' , 'PurchaseController', ['except' => ['create', 'edit', 'update']]);

Route::post('auth/login', ['uses' => 'AuthController@login', 'as' => 'login']);