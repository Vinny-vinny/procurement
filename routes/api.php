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

Route::apiResource('machines','MachineController');
Route::get('import-machines','MachineController@importMachines');
Route::apiResource('parts','PartsController');
Route::apiResource('categories','CategoriesController');
Route::apiResource('warehouse','WarehouseController');
Route::get('import-parts/{warehouse}','PartsController@importParts');
Route::apiResource('projects','ProjectController');
Route::apiResource('customers','CustomerController');
Route::apiResource('customer-types','CustomerTypeController');
Route::apiResource('requisitions','RequisitionController');
Route::post('custom-requistions','RequisitionController@customRequisitions');
Route::get('import-projects','ProjectController@importProjects');
Route::get('import-customers','CustomerController@importCustomers');
///
Route::apiResource('requisition-types','RequisitionTypeController');
Route::apiResource('departments','DepartmentController');
Route::apiResource('department-budget','DepartmentBudgetController');
Route::apiResource('item-types','ItemTypesController');
Route::apiResource('priorities','PriorityController');
Route::apiResource('suppliers','SupplierController');
Route::get('import-suppliers','SupplierController@importSuppliers');
Route::apiResource('enquiry','EnquiryController');
Route::apiResource('quotations','QuotationController');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});
