<?php

use App\Http\Controllers\Bo\Categories\CategoriesController;
use App\Http\Controllers\Bo\Categories\SubCategoriesController;
use App\Http\Controllers\Bo\Categories\ColorCategoryController;
use App\Http\Controllers\Bo\Home\HomeController;
use App\Http\Controllers\Bo\Auth\LoginController;
use App\Http\Controllers\Bo\Comprof\ContactController;
use App\Http\Controllers\Bo\Comprof\EventController;
use App\Http\Controllers\Bo\Comprof\CProfileController;
use App\Http\Controllers\Bo\Comprof\ServiceController;
use App\Http\Controllers\Bo\Comprof\PartnerController;
use App\Http\Controllers\Bo\Comprof\CertificateController;
use App\Http\Controllers\Bo\Account\ProfileController;
use App\Http\Controllers\Bo\Categories\SkuController;
use App\Http\Controllers\Bo\Discount\ManagementDiscountEventController;
use App\Http\Controllers\Bo\Discount\ManagementDiscountNewCustomerController;
use App\Http\Controllers\Bo\Customer\CustomerManagementController;
use App\Http\Controllers\Bo\Product\ProductController;
use App\Http\Controllers\Bo\Product\HampersProductController;
use App\Http\Controllers\Bo\Order\ManagementOrderController;
use App\Http\Controllers\Bo\Order\ManagementOrderHampersController;
use App\Http\Controllers\Bo\User\UserController;
use App\Http\Controllers\Bo\visitor\VisitorMailController;
use App\Http\Controllers\Bo\testimoni\TestimoniController as BoTestimoniController;

use App\Http\Controllers\Fo\distric\DistrictDataController;
use App\Http\Controllers\Fo\payment\PaymentOrderController;
use App\Http\Controllers\Fo\cart\CartProductController;
use App\Http\Controllers\Fo\cart\CartHampersController;
use App\Http\Controllers\Fo\shop\ShopProductController;
// use App\Http\Controllers\PaymentMethodController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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
//product cart
Route::post('cart-product-update-quantity', [CartProductController::class, 'updateQuantity'])->name('cart-product-update-quantity');

//hampers cart
Route::post('cart-hampers-update-quantity', [CartHampersController::class, 'updateQuantity'])->name('cart-hampers-update-quantity');

//payment
Route::post('payment-callback', [PaymentOrderController::class, 'handleCallback']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("districs", [DistrictDataController::class, 'datas'])->name('districs.data');

Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => ['web', 'auth']], function () {

        //category
        Route::get("categories", [CategoriesController::class, 'datas'])->name('categories.data');
        Route::get("s_categories", [CategoriesController::class, 'sDatas'])->name('categories.sDatas');

        //sucategory
        Route::get("subcategories", [SubCategoriesController::class, 'datas'])->name('subcategories.data');
        Route::get("getsubcategories", [SubCategoriesController::class, 'cate'])->name('subcategories.data.categori');

        //color
        Route::get("colors", [ColorCategoryController::class, 'datas'])->name('color.data');
        Route::get("s_color", [ColorCategoryController::class, 'sDatas'])->name('color.sDatas');

        // SKU
        Route::get("skus", [SkuController::class, 'datas'])->name('sku.data');
        Route::get("s_skus", [SkuController::class, 'sDatas'])->name('sku.sDatas');

        //users
        Route::get("users", [UserController::class, 'datas'])->name('users.data');
        Route::get("s_users", [UserController::class, 'sDatas'])->name('users.sDatas');

        // Products
        Route::get("products", [ProductController::class, 'datas'])->name('products.data');
        Route::get("s_products", [ProductController::class, 'sDatas'])->name('products.sDatas');

        // Hampers Products
        Route::get("hamperss", [HampersProductController::class, 'datas'])->name('hampers.data');
        Route::get("s_hamperss", [HampersProductController::class, 'sDatas'])->name('hampers.sDatas');

        // Discount event management
        Route::get("discounts", [ManagementDiscountEventController::class, 'datas'])->name('discount.data');

        // Customer management
        Route::get("management_customers", [CustomerManagementController::class, 'datas'])->name('management_customer.data');
        Route::get("s_management_customer", [CustomerManagementController::class, 'sDatas'])->name('management_customer.sDatas');

        // Payment Method
        // Route::get("paymentmethods", [PaymentMethodController::class, 'datas'])->name('paymentmethod.data');

        // Order
        Route::get("orders", [ManagementOrderController::class, 'datas'])->name('order_product.data');
        Route::get("orders-detail/{id}", [ManagementOrderController::class, 'detail'])->name('order_product.detail');

        // Order Hampers
        Route::get("order_hamperss", [ManagementOrderHampersController::class, 'datas'])->name('order_hampers.data');
        Route::get("order_hampers-detail/{id}", [ManagementOrderHampersController::class, 'detail'])->name('order_hampers.detail');

        //comprof event
        Route::get("events", [EventController::class, 'datas'])->name('events.data');

        //comprof service
        Route::get("services", [ServiceController::class, 'datas'])->name('services.data');

        //comprof partner
        Route::get("partners", [PartnerController::class, 'datas'])->name('partners.data');

        //comprof certificate
        Route::get("certificates", [CertificateController::class, 'datas'])->name('certificates.data');

        //mail
        Route::get("mail_visitors", [VisitorMailController::class, 'datas'])->name('mail_visitor.data');

        //testimoni
        Route::get("testimonis", [BoTestimoniController::class, 'datas'])->name('testimoni.data');

        //product-color-change
        Route::post("product-refresh", [ShopProductController::class, 'refresh'])->name('product.refresh');
    });
});
