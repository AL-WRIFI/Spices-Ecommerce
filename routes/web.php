<?php
use App\Http\Controllers\Admin\Categories\CategoryController;
use App\Http\Controllers\Admin\Categories\EndCategoryController;
use App\Http\Controllers\Admin\Categories\SubCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\User\AuthController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\EcommerceDashboard;
use App\Http\Controllers\Admin\EcommerceProductList;
use App\Http\Controllers\Admin\EcommerceProductAdd;
use App\Http\Controllers\Admin\EcommerceProductCategory;
use App\Http\Controllers\Admin\EcommerceOrderList;
use App\Http\Controllers\Admin\EcommerceOrderDetails;
use App\Http\Controllers\Admin\EcommerceCustomerAll;
use App\Http\Controllers\Admin\EcommerceCustomerDetailsOverview;
use App\Http\Controllers\Admin\EcommerceCustomerDetailsSecurity;
use App\Http\Controllers\Admin\EcommerceCustomerDetailsBilling;
use App\Http\Controllers\Admin\EcommerceCustomerDetailsNotifications;
use App\Http\Controllers\Admin\EcommerceManageReviews;
use App\Http\Controllers\Admin\EcommerceReferrals;
use App\Http\Controllers\Admin\EcommerceSettingsDetails;
use App\Http\Controllers\Admin\EcommerceSettingsPayments;
use App\Http\Controllers\Admin\EcommerceSettingsCheckout;
use App\Http\Controllers\Admin\EcommerceSettingsShipping;
use App\Http\Controllers\Admin\EcommerceSettingsLocations;
use App\Http\Controllers\Admin\EcommerceSettingsNotifications;

use App\Http\Controllers\language\LanguageController;

Route::get('/lang/{locale}', [LanguageController::class, 'swap']);


// Main Page Route
Route::get('/', [EcommerceDashboard::class, 'index'])->name('dashboard-analytics');

Route::get('/app/ecommerce/dashboard', [EcommerceDashboard::class, 'index'])->name('app-ecommerce-dashboard');
Route::get('/app/ecommerce/product/list', [EcommerceProductList::class, 'index'])->name('app-ecommerce-product-list');
Route::get('/app/ecommerce/product/add', [EcommerceProductAdd::class, 'index'])->name('app-ecommerce-product-add');
Route::get('/app/ecommerce/product/category', [EcommerceProductCategory::class, 'index'])->name('app-ecommerce-product-category');
Route::get('/app/ecommerce/order/list', [EcommerceOrderList::class, 'index'])->name('app-ecommerce-order-list');
Route::get('/app/ecommerce/order/details', [EcommerceOrderDetails::class, 'index'])->name('app-ecommerce-order-details');
Route::get('/app/ecommerce/customer/all', [EcommerceCustomerAll::class, 'index'])->name('app-ecommerce-customer-all');
Route::get('/app/ecommerce/customer/details/overview', [EcommerceCustomerDetailsOverview::class, 'index'])->name('app-ecommerce-customer-details-overview');
Route::get('/app/ecommerce/customer/details/security', [EcommerceCustomerDetailsSecurity::class, 'index'])->name('app-ecommerce-customer-details-security');
Route::get('/app/ecommerce/customer/details/billing', [EcommerceCustomerDetailsBilling::class, 'index'])->name('app-ecommerce-customer-details-billing');
Route::get('/app/ecommerce/customer/details/notifications', [EcommerceCustomerDetailsNotifications::class, 'index'])->name('app-ecommerce-customer-details-notifications');
Route::get('/app/ecommerce/manage/reviews', [EcommerceManageReviews::class, 'index'])->name('app-ecommerce-manage-reviews');
Route::get('/app/ecommerce/referrals', [EcommerceReferrals::class, 'index'])->name('app-ecommerce-referrals');
Route::get('/app/ecommerce/settings/details', [EcommerceSettingsDetails::class, 'index'])->name('app-ecommerce-settings-details');
Route::get('/app/ecommerce/settings/payments', [EcommerceSettingsPayments::class, 'index'])->name('app-ecommerce-settings-payments');
Route::get('/app/ecommerce/settings/checkout', [EcommerceSettingsCheckout::class, 'index'])->name('app-ecommerce-settings-checkout');
Route::get('/app/ecommerce/settings/shipping', [EcommerceSettingsShipping::class, 'index'])->name('app-ecommerce-settings-shipping');
Route::get('/app/ecommerce/settings/locations', [EcommerceSettingsLocations::class, 'index'])->name('app-ecommerce-settings-locations');
Route::get('/app/ecommerce/settings/notifications', [EcommerceSettingsNotifications::class, 'index'])->name('app-ecommerce-settings-notifications');



Route::resource('categories', CategoryController::class);
Route::resource('sub-categories', SubCategoryController::class);
Route::get('/get-subcategories/{categoryId}', [SubCategoryController::class, 'getSubCategories']);

Route::resource('products', ProductController::class);
Route::delete('/gallery/{image}', [ProductController::class, 'destroyImage'])->name('gallery.destroy');
Route::resource('drivers', DriverController::class);


Route::get('/get-regions/{country_id}', [LocationController::class, 'getRegions']);
Route::get('/get-cities/{region_id}', [LocationController::class, 'getCities']);
Route::get('/get-districts/{city_id}', [LocationController::class, 'getDistricts']);

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

Route::post('/orders/appoint-driver', [OrderController::class, 'appointDriver'])->name('orders.appointDriver');

Route::resource('users', UserController::class);
