
<?php
//bo
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
use App\Http\Controllers\Bo\Discount\ManagementDiscountEventController;
use App\Http\Controllers\Bo\Discount\ManagementDiscountNewCustomerController;
use App\Http\Controllers\Bo\Customer\CustomerManagementController;
use App\Http\Controllers\Bo\Product\ProductController;
use App\Http\Controllers\Bo\Product\HampersProductController;
use App\Http\Controllers\Bo\Order\ManagementOrderController;
use App\Http\Controllers\Bo\Order\ManagementOrderHampersController;
use App\Http\Controllers\Bo\AppFee\AppFeeController;
use App\Http\Controllers\Bo\Categories\SkuController;
use App\Http\Controllers\Bo\User\UserController;
use App\Http\Controllers\Bo\visitor\VisitorMailController;
use App\Http\Controllers\Bo\testimoni\TestimoniController as BoTestimoniController;

//fo
use App\Http\Controllers\Fo\home\BerandaController;
use App\Http\Controllers\Fo\contact\ContactUsController;
use App\Http\Controllers\Fo\about\AboutUsController;
use App\Http\Controllers\Fo\event\EventController as FoEventController;
use App\Http\Controllers\Fo\partner\PartnerController as FoPartnerController;
use App\Http\Controllers\Fo\certificate\CertificateController as FoCertificateController;
use App\Http\Controllers\Fo\shop\ShopProductController;
use App\Http\Controllers\Fo\shop\ShopHampersController;
use App\Http\Controllers\Fo\cart\CartProductController;
use App\Http\Controllers\Fo\cart\CartHampersController;
use App\Http\Controllers\Fo\auth\AuthCustomerController;
use App\Http\Controllers\Fo\pass_change\PasswordChangeContoller;
use App\Http\Controllers\Fo\customer\DashboardCustomerController;
use App\Http\Controllers\Fo\customer\OrderHistoryController;
use App\Http\Controllers\Fo\customer\CouponListController;
use App\Http\Controllers\Fo\customer\TestimoniController;
use App\Http\Controllers\Fo\customer\UlasanController;
use App\Http\Controllers\Fo\payment\PaymentOrderController;
use App\Http\Controllers\Fo\payment\PaymentOrderHampersController;
use App\Http\Controllers\Fo\shipping\ShippingProductController;
use App\Http\Controllers\Fo\shipping\ShippingHampersController;
use App\Http\Controllers\Fo\discount\DiscountEventApplyController;
use App\Http\Controllers\Fo\discount\DiscountEventApplyHController;

use Illuminate\Support\Facades\Route;

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
    // fo
    Route::get('/', [BerandaController::class, 'index'])->name('fo.home');

    //contacct
    Route::resource("contact", ContactUsController::class, ['as' => 'fo'], ['only' => ['index', 'store']]);

    //about
    Route::resource("about", AboutUsController::class, ['as' => 'fo'], ['only' => ['index']]);

    //event
    Route::resource("event", FoEventController::class, ['as' => 'fo'], ['only' => ['index', 'show']]);

    //partner
    Route::resource("partner", FoPartnerController::class, ['as' => 'fo'], ['only' => ['show']]);

    //certificate
    Route::resource("certificate", FoCertificateController::class, ['as' => 'fo'], ['only' => ['show']]);

    //product shop
    Route::resource("shop-product", ShopProductController::class, ['only' => ['index', 'show']]);
    Route::get("/shop-product-category/{id}", [ShopProductController::class, 'categoryShow'])->name('shop-product.category');

    //product hampers
    Route::resource("shop-hampers", ShopHampersController::class, ['only' => ['index', 'show']]);
    Route::get("/shop-hampers-category/{id}", [ShopHampersController::class, 'categoryShow'])->name('shop-hampers.category');

    //cart product
    Route::resource("cart-product", CartProductController::class, ['as' => 'fo']);

    //cart hampers
    Route::resource("cart-hampers", CartHampersController::class, ['as' => 'fo']);

//auth customer

    Route::get('login', [AuthCustomerController::class, 'index'])->name('loginf.customer');
    Route::post('login', [AuthCustomerController::class, 'login'])->name('login.customer');
    Route::get('register', [AuthCustomerController::class, 'registerf'])->name('registerf.customer');
    Route::post('register', [AuthCustomerController::class, 'register'])->name('register.customer');

    Route::get('verify/{token}', [AuthCustomerController::class, 'verify'])->name('verify');
    Route::resource("change-password", PasswordChangeContoller::class);

Route::group(['middleware' => ['auth:customer']], function () {
    Route::get('dashboard', [DashboardCustomerController::class, 'index'])->name('dashboard.customer');
    Route::get('profile-edit', [DashboardCustomerController::class, 'edit'])->name('customer.profiile.edit');
    Route::post('profile-edit', [DashboardCustomerController::class, 'update'])->name('customer.profiile.update');
    Route::post('password-edit', [DashboardCustomerController::class, 'upassword'])->name('customer.password.update');

    Route::resource("coupon", CouponListController::class, ['only' => ['index']]);
    Route::resource("testimoni", TestimoniController::class, ['as' => 'fo', 'only' => ['store', 'update']]);
    Route::resource("ulasan", UlasanController::class, ['only' => ['store', 'update']]);

    Route::get('hproduct/{id}', [OrderHistoryController::class, 'product'])->name('historyp.customer');
    Route::get('hhampers/{id}', [OrderHistoryController::class, 'hampers'])->name('historyh.customer');

    //product payment order
        //payment product
        Route::get('payment/{order}', [PaymentOrderController::class, 'index'])->name('product-payment');
        Route::post('payment-create/{order}', [PaymentOrderController::class, 'create'])->name('product-payment.create');

        //payment hampers
        Route::get('paymenth/{order}', [PaymentOrderHampersController::class, 'index'])->name('hampers-payment');
        Route::post('paymenth-create/{order}', [PaymentOrderHampersController::class, 'create'])->name('hampers-payment.create');

        //ongkir product
        Route::resource("shipping-product", ShippingProductController::class, ['as' => 'fo']);
        Route::post('shipping-product-cekk/{order}', [ShippingProductController::class, 'storeCek'])->name('fo.shipping-product.cekk');

        //ongkir hampers
        Route::resource("shipping-hampers", ShippingHampersController::class, ['as' => 'fo']);
        Route::post('shipping-hampers-cekk/{order}', [ShippingHampersController::class, 'storeCek'])->name('fo.shipping-hampers.cekk');

        //discount
        Route::resource("discount-apply", DiscountEventApplyController::class, ['as' => 'fo']);
        Route::resource("discounth-apply", DiscountEventApplyHController::class, ['as' => 'fo']);

    //hampers payment order

    //logout
    Route::post('logout', [AuthCustomerController::class, 'logout'])->name('logout.customer');
});


// bo
Route::group(['prefix' => 'admin'], function () {

    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('auth', [LoginController::class, 'auth'])->name('authorization');

    Route::group(['middleware' => ['web', 'auth']], function () {

        //auth
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('home', [HomeController::class, 'index'])->name('home');
        Route::resource("profile", ProfileController::class, ['only' => ['index', 'update']]);

        // admin
        Route::group(['middleware' => ['can:admin']], function () {
            //App Fee
            Route::resource("appfee", AppFeeController::class);

            Route::group(['prefix' => 'category'], function () {
                //category
                Route::resource("category", CategoriesController::class);

                //sucategory
                Route::resource("subcategory", SubCategoriesController::class);

                //Color category
                Route::resource("color", ColorCategoryController::class);
                Route::resource("sku", SkuController::class);
            });

            //users
            Route::resource("user", UserController::class);

            Route::group(['prefix' => 'product'], function () {
                // Products
                Route::resource("product", ProductController::class);

                // Hampers Products
                Route::resource("hampers", HampersProductController::class);
            });

            Route::group(['prefix' => 'discount'], function () {
                // Discount event management
                Route::resource("discount", ManagementDiscountEventController::class);
                Route::post("/discounts/{id}", [ManagementDiscountEventController::class, 'active'])->name('discount.active');

                //discount new customer
                Route::resource("disc_new_customer", ManagementDiscountNewCustomerController::class, ['only' => ['index', 'update']]);
             });

            // Customer management
            Route::resource("management_customer", CustomerManagementController::class);
            Route::post("/management_customers/{id}", [CustomerManagementController::class, 'active'])->name('management_customer.active');

            // Payment Method
            // Route::resource("paymentmethod", PaymentMethodController::class);

            // Order
            Route::resource("order_product", ManagementOrderController::class);
            Route::get("resi_product/{id}", [ManagementOrderController::class, 'printresi'])->name('resi_product.print');

            // Order Hampers
            Route::resource("order_hampers", ManagementOrderHampersController::class);
            Route::get("resi_hampers/{id}", [ManagementOrderHampersController::class, 'printresi'])->name('resi_hampers.print');


            Route::group(['prefix' => 'comprof'], function () {
                //comprof contact us
                Route::resource("contact", ContactController::class, ['only' => ['index', 'update']]);

                //comprof event
                Route::resource("event", EventController::class);
                Route::post("/events/{id}", [EventController::class, 'active'])->name('events.active');

                //comprof service
                Route::resource("service", ServiceController::class);

                //comprof partner
                Route::resource("partner", PartnerController::class);

                //comprof certificate
                Route::resource("certificate", CertificateController::class);

                //comprof profile
                Route::resource("com_profile", CProfileController::class, ['only' => ['index', 'update']]);
            });

            // mail visitor
            Route::resource("mail_visitor", VisitorMailController::class);

            // Testimoni
            Route::resource("testimoni", BoTestimoniController::class);
        });
    });
});
