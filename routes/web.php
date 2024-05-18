<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\AmenitiesController;
use App\Http\Controllers\Admin\AttributesController;
use App\Http\Controllers\Admin\AttributeValuesController;
use App\Http\Controllers\Admin\LocationsController;
use App\Http\Controllers\Admin\FeaturesController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\SlidersController;
use App\Http\Controllers\Admin\LineItemsController;
use App\Http\Controllers\Admin\AppointmentStatusController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardStatController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaController;

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

use Illuminate\Support\Facades\Hash;

Route::get('/hash/{password}', function ($password) {
    $hashedPassword = Hash::make($password);

    return "Original Password: $password <br> Hashed Password: $hashedPassword";
});
Route::get('/wpp', function () {
    return 'aa';
});

Route::get('/', function () {
    return view('welcome');
});
Route::get('/wp', function () {
    return 'TestTestTest';
});

Route::get('/admin/dashboard', function () {
    return view('dashboard');
});

// /api/service/{service_id}/media/all
// /api/service/{service_id}/media/featured-update/{media_id}
// /api/service/{service_id}/media/add-remove/{media_id}

Route::prefix('/api/slider/{slider_id}/media')->name('api.slider.media.')->group(function () {
    Route::get('/all', [SlidersController::class, 'getAllMedia'])->name('index');
    Route::get('/featured-update/{media_id}', [SlidersController::class, 'featuredUpdate'])->name('featured-update');
    Route::get('/add-remove/{media_id}', [SlidersController::class, 'addOrRemoveMedia'])->name('add-remove');
});

Route::prefix('/api/service/{service_id}/media')->name('api.service.media.')->group(function () {
    Route::get('/all', [ServicesController::class, 'getAllMedia'])->name('index');
    Route::get('/featured-update/{media_id}', [ServicesController::class, 'featuredUpdate'])->name('featured-update');
    Route::get('/add-remove/{media_id}', [ServicesController::class, 'addOrRemoveMedia'])->name('add-remove');
});

Route::prefix('/api/location/{location_id}/media')->name('api.location.media.')->group(function () {
    Route::get('/all', [LocationsController::class, 'getAllMedia'])->name('index');
    Route::get('/featured-update/{media_id}', [LocationsController::class, 'featuredUpdate'])->name('featured-update');
    Route::get('/add-remove/{media_id}', [LocationsController::class, 'addOrRemoveMedia'])->name('add-remove');
});

Route::prefix('/api/lineitem/{line_item_id}/media')->name('api.lineitem.media.')->group(function () {
    Route::get('/all', [LineitemsController::class, 'getAllMedia'])->name('index');
    Route::get('/featured-update/{media_id}', [LineitemsController::class, 'featuredUpdate'])->name('featured-update');
    Route::get('/add-remove/{media_id}', [LineitemsController::class, 'addOrRemoveMedia'])->name('add-remove');
});

Route::prefix('/api/feature/{feature_id}/media')->name('api.feature.media.')->group(function () {
    Route::get('/all', [FeaturesController::class, 'getAllMedia'])->name('index');
    Route::get('/featured-update/{media_id}', [FeaturesController::class, 'featuredUpdate'])->name('featured-update');
    Route::get('/add-remove/{media_id}', [FeaturesController::class, 'addOrRemoveMedia'])->name('add-remove');
});

Route::prefix('/api/attribute/{attribute_id}/media')->name('api.attribute.media.')->group(function () {
    Route::get('/all', [AttributesController::class, 'getAllMedia'])->name('index');
    Route::get('/featured-update/{media_id}', [AttributesController::class, 'featuredUpdate'])->name('featured-update');
    Route::get('/add-remove/{media_id}', [AttributesController::class, 'addOrRemoveMedia'])->name('add-remove');
});

Route::prefix('/api/attributevalue/{attribute_value_id}/media')->name('api.attributevalue.media.')->group(function () {
    Route::get('/all', [AttributeValuesController::class, 'getAllMedia'])->name('index');
    Route::get('/featured-update/{media_id}', [AttributeValuesController::class, 'featuredUpdate'])->name('featured-update');
    Route::get('/add-remove/{media_id}', [AttributeValuesController::class, 'addOrRemoveMedia'])->name('add-remove');
});

Route::prefix('/api/category/{category_id}/media')->name('api.category.media.')->group(function () {
    Route::get('/all', [CategoriesController::class, 'getAllMedia'])->name('index');
    Route::get('/featured-update/{media_id}', [CategoriesController::class, 'featuredUpdate'])->name('featured-update');
    Route::get('/add-remove/{media_id}', [CategoriesController::class, 'addOrRemoveMedia'])->name('add-remove');
});

Route::prefix('/api/product/{product_id}/media')->name('api.product.media.')->group(function () {
    Route::get('/all', [ProductsController::class, 'getAllMedia'])->name('index');
    Route::get('/featured-update/{media_id}', [ProductsController::class, 'featuredUpdate'])->name('featured-update');
    Route::get('/add-remove/{media_id}', [ProductsController::class, 'addOrRemoveMedia'])->name('add-remove');
});

Route::get('/api/media', [MediaController::class, 'index'])->name('media.index');
Route::get('/media/{id}/edit', [MediaController::class, 'edit'])->name('media.edit');
Route::post('/media/upload', [MediaController::class, 'upload'])->name('media.upload');
Route::delete('/api/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
Route::post('/media-upload', [MediaController::class, 'upload']);

// Route::post('/api/upload-file', [ApplicationController::class, 'uploadFile']);

Route::middleware('auth')->group(function () {

    Route::get('/api/sliders', [SlidersController::class, 'index']);
    Route::post('/api/sliders/create', [SlidersController::class, 'store']);
    Route::get('/api/sliders/{id}/edit', [SlidersController::class, 'edit']);
    Route::put('/api/sliders/{sliders}/edit', [SlidersController::class, 'update']);
    Route::delete('/api/sliders/{sliders}', [SlidersController::class, 'destroy']);
    Route::post('/api/sliders/update-sort-order', [SlidersController::class, 'updateSortOrder']);

    Route::get('/api/stats/appointments', [DashboardStatController::class, 'appointments']);
    Route::get('/api/stats/users', [DashboardStatController::class, 'users']);

    Route::get('/api/users', [UserController::class, 'index']);
    Route::post('/api/users', [UserController::class, 'store']);
    Route::patch('/api/users/{user}/change-role', [UserController::class, 'changeRole']);
    Route::put('/api/users/{user}', [UserController::class, 'update']);
    Route::delete('/api/users/{user}', [UserController::class, 'destory']);
    Route::delete('/api/users', [UserController::class, 'bulkDelete']);

    Route::get('/api/clients', [ClientController::class, 'index']);

    Route::get('/api/appointment-status', [AppointmentStatusController::class, 'getStatusWithCount']);
    Route::get('/api/appointments', [AppointmentController::class, 'index']);
    Route::post('/api/appointments/create', [AppointmentController::class, 'store']);
    Route::get('/api/appointments/{appointment}/edit', [AppointmentController::class, 'edit']);
    Route::put('/api/appointments/{appointment}/edit', [AppointmentController::class, 'update']);
    Route::delete('/api/appointments/{appointment}', [AppointmentController::class, 'destroy']);

    Route::get('/api/categories', [CategoriesController::class, 'index']);
    Route::get('/api/categories/parents', [CategoriesController::class, 'indexParents']);
    Route::get('/api/categories/parents/children', [CategoriesController::class, 'indexParentsChildren']);
    Route::post('/api/categories/create', [CategoriesController::class, 'store']);
    Route::get('/api/categories/withcount', [CategoriesController::class, 'getTypesWithCount']);
    Route::get('/api/categories/{category}/edit', [CategoriesController::class, 'edit']);
    Route::put('/api/categories/{category}/edit', [CategoriesController::class, 'update']);
    Route::delete('/api/categories/{category}', [CategoriesController::class, 'destroy']);
    Route::post('/api/categories/upload-image', [CategoriesController::class, 'uploadImage']);
    Route::post('/api/categories/update-sort-order', [CategoriesController::class, 'updateSortOrder']);

    Route::get('/api/products', [ProductsController::class, 'index']);
    Route::post('/api/products/create', [ProductsController::class, 'store']);
    Route::get('/api/products/{products}/edit', [ProductsController::class, 'edit']);
    Route::put('/api/products/{products}/edit', [ProductsController::class, 'update']);
    Route::delete('/api/products/{products}', [ProductsController::class, 'destroy']);
    Route::post('/api/products/update-sort-order', [ProductsController::class, 'updateSortOrder']);

    Route::get('/api/attributes', [AttributesController::class, 'index']);
    Route::post('/api/attributes/create', [AttributesController::class, 'store']);
    Route::get('/api/attributes/withcount', [AttributesController::class, 'getTypesWithCount']);
    Route::get('/api/attributes/{attributes}/edit', [AttributesController::class, 'edit']);
    Route::put('/api/attributes/{attributes}/edit', [AttributesController::class, 'update']);
    Route::delete('/api/attributes/{attributes}', [AttributesController::class, 'destroy']);
    Route::post('/api/attributes/upload-image', [AttributesController::class, 'uploadImage']);
    Route::post('/api/attributes/update-sort-order', [AttributesController::class, 'updateSortOrder']);

    Route::get('/api/attributevalues', [AttributeValuesController::class, 'index']);
    Route::post('/api/attributevalues/create', [AttributeValuesController::class, 'store']);
    Route::get('/api/attributevalues/withcount', [AttributeValuesController::class, 'getTypesWithCount']);
    Route::get('/api/attributevalues/{attributevalues}/edit', [AttributeValuesController::class, 'edit']);
    Route::put('/api/attributevalues/{attributevalues}/edit', [AttributeValuesController::class, 'update']);
    Route::delete('/api/attributevalues/{attributevalues}', [AttributeValuesController::class, 'destroy']);
    Route::post('/api/attributevalues/upload-image', [AttributeValuesController::class, 'uploadImage']);
    Route::post('/api/attributevalues/update-sort-order', [AttributeValuesController::class, 'updateSortOrder']);

    Route::get('/api/amenities', [AmenitiesController::class, 'index']);
    Route::post('/api/amenities/create', [AmenitiesController::class, 'store']);
    Route::get('/api/amenities/withcount', [AmenitiesController::class, 'getTypesWithCount']);
    Route::get('/api/amenities/{amenities}/edit', [AmenitiesController::class, 'edit']);
    Route::put('/api/amenities/{amenities}/edit', [AmenitiesController::class, 'update']);
    Route::delete('/api/amenities/{amenities}', [AmenitiesController::class, 'destroy']);
    Route::post('/api/amenities/upload-image', [AmenitiesController::class, 'uploadImage']);
    Route::post('/api/amenities/update-sort-order', [AmenitiesController::class, 'updateSortOrder']);

    Route::get('/api/features', [FeaturesController::class, 'index']);
    Route::post('/api/features/create', [FeaturesController::class, 'store']);
    Route::get('/api/features/withcount', [FeaturesController::class, 'getTypesWithCount']);
    Route::get('/api/features/{id}/edit', [FeaturesController::class, 'edit']);
    Route::put('/api/features/{features}/edit', [FeaturesController::class, 'update']);
    Route::delete('/api/features/{features}', [FeaturesController::class, 'destroy']);
    Route::post('/api/features/upload-image', [FeaturesController::class, 'uploadImage']);

    Route::get('/api/services', [ServicesController::class, 'index']);
    Route::post('/api/services/create', [ServicesController::class, 'store']);
    Route::get('/api/services/withcount', [ServicesController::class, 'getTypesWithCount']);
    Route::get('/api/services/{id}/edit', [ServicesController::class, 'edit']);
    Route::put('/api/services/{services}/edit', [ServicesController::class, 'update']);
    Route::delete('/api/services/{services}', [ServicesController::class, 'destroy']);
    Route::post('/api/services/upload-image', [ServicesController::class, 'uploadImage']);

    Route::get('/api/lineitems', [LineitemsController::class, 'index']);
    Route::post('/api/lineitems/create', [LineitemsController::class, 'store']);
    Route::get('/api/lineitems/withcount', [LineitemsController::class, 'getTypesWithCount']);
    Route::get('/api/lineitems/{id}/edit', [LineitemsController::class, 'edit']);
    Route::put('/api/lineitems/{lineitems}/edit', [LineitemsController::class, 'update']);
    Route::delete('/api/lineitems/{lineitems}', [LineitemsController::class, 'destroy']);
    Route::post('/api/lineitems/upload-image', [LineitemsController::class, 'uploadImage']);

    Route::get('/api/locations', [LocationsController::class, 'index']);
    Route::post('/api/locations/create', [LocationsController::class, 'store']);
    Route::get('/api/locations/withcount', [LocationsController::class, 'getTypesWithCount']);
    Route::get('/api/locations/{id}/edit', [LocationsController::class, 'edit']);
    Route::put('/api/locations/{locations}/edit', [LocationsController::class, 'update']);
    Route::delete('/api/locations/{locations}', [LocationsController::class, 'destroy']);
    Route::post('/api/locations/upload-image', [LocationsController::class, 'uploadImage']);

    Route::get('/api/settings', [SettingController::class, 'index']);
    Route::post('/api/settings', [SettingController::class, 'update']);

    Route::get('/api/profile', [ProfileController::class, 'index']);
    Route::put('/api/profile', [ProfileController::class, 'update']);
    Route::post('/api/upload-profile-image', [ProfileController::class, 'uploadImage']);
    Route::post('/api/change-user-password', [ProfileController::class, 'changePassword']);

    Route::get('/api/bookings', [BookingController::class, 'index']);
    Route::post('/api/booking/create', [BookingController::class, 'store']);
    Route::get('/api/booking/{booking}/edit', [BookingController::class, 'edit']);
    Route::put('/api/booking/{booking}/edit', [BookingController::class, 'update']);
    Route::delete('/api/booking/{booking}', [BookingController::class, 'destroy']);

});

Route::get('{view}', ApplicationController::class)->where('view', '(.*)')->middleware('auth');