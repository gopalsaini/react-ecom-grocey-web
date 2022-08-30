<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PreLoginController;
use App\Http\Controllers\API\PostLoginController;

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

Route::group([
    'middleware' => 'auth:api'
  ], function() {
    
	Route::get("user-profile","API\PostLoginController@userProfile");
	Route::post("add-address","API\PostLoginController@addAddress");
	Route::post("update-profile","API\PostLoginController@updateProfile");
	Route::post("delete-address","API\PostLoginController@deleteAddress");
	Route::post("get-address-by-id","API\PostLoginController@getAddressById");
	Route::get("address-list","API\PostLoginController@addressList");
	Route::post("add-cart","API\PostLoginController@addToCart");
	Route::post("delete-cart","API\PostLoginController@deleteCart");
	Route::get("delete-complete-cart","API\PostLoginController@deleteCompleteCart");
	Route::get("cart-list","API\PostLoginController@cartList");
	Route::post("checkout","API\PostLoginController@checkout");
	Route::get("order","API\PostLoginController@order");
	Route::post("wishlist-product","API\PostLoginController@wishlistProduct");
	Route::get("wishlist-product-list","API\PostLoginController@wishlistProductList");
	Route::post("delete-wishlist-product","API\PostLoginController@deleteWishlistProduct");
	Route::post("check-coupon-code","API\PostLoginController@checkCouponCode");
	Route::get("logout","API\PostLoginController@logout");
    Route::get("coupon-list","API\PostLoginController@couponList");
    Route::post('productlist','API\PreLoginController@produactList');
    Route::post('product-list-by-brand','API\PreLoginController@produactListByBrand');
    Route::get('topselling-product',[preLoginController::class, 'topSellingProduct']);
    Route::get('dealsoftheday-product',[preLoginController::class, 'dealsOfTheDay']);
    Route::get('total-cart-item','API\PostLoginController@TotalCartCountQty');

    Route::post("paytm-checksum","API\PostLoginController@paytmChecksum");


    Route::post('banner-category-products','API\PreLoginController@sliderCategoryProduactList');

});

Route::get('product-detail','API\PreLoginController@productDetail');

Route::get('all-product-list','API\PreLoginController@allProduactList');
Route::get('topselling-product',[preLoginController::class, 'topSellingProduct']);
Route::get('dealsoftheweek-productlist',[preLoginController::class, 'dealsOfTheWeekProductList']);

Route::post("sendotp","API\PreLoginController@sendOtp");
Route::post("verify-otp","API\PreLoginController@verifyOtp");
Route::post("login","API\PreLoginController@LoginAndRegister");
Route::post("social-login","API\PreLoginController@socialLogin");
Route::get('country-list',[preLoginController::class, 'countryList']);
Route::post('state-list',[preLoginController::class, 'stateList']);
Route::post('city-list',[preLoginController::class, 'cityList']);

Route::get('banner-list',[preLoginController::class, 'sliderList']);
Route::get("information/{id}","API\PreLoginController@Information");

Route::get('blog-list',[preLoginController::class, 'blogList']);
Route::post('blog-detail',[preLoginController::class, 'singleBlog']);
Route::post('contact-submit',[preLoginController::class, 'submitContact']);

Route::get('category-list',[preLoginController::class, 'categoryList']);

Route::get('brand-list',"API\PreLoginController@brandList");
Route::post('sub-category-list',[preLoginController::class, 'SubCateList']);

Route::get('variant-attribute-list',[preLoginController::class, 'variantAttributeList']);
Route::get('search-product',[preLoginController::class, 'searchProduct']);
Route::get('latest-orders',[preLoginController::class, 'latestOrders']);
Route::get('testimonial-list',[preLoginController::class, 'testimonialList']);

Route::get('top-category',[preLoginController::class, 'topCategory']);
Route::get('must-try-category',[preLoginController::class, 'mustTryCategory']);



Route::post('get-information-pages-data',[preLoginController::class, 'informationData']);

Route::post("guest-checkout","API\PostLoginController@checkout");
Route::post("initiate-payment","API\PreLoginController@initiatePayment");

Route::get("check-pincode","API\PreLoginController@checkPincode");
Route::post("fail-order","API\PostLoginController@failedOrder");

Route::any("update-payment","API\PreLoginController@updatePayment");
Route::get("update-shipping-status","API\PreLoginController@changeDeliveryStatus");

Route::post("newsletter-subscribe","API\PreLoginController@newsletterSubscribe");
Route::post("track-order","API\PreLoginController@trackOrder");
Route::post("ondemand-enquiry","API\PreLoginController@ondemandEnquiry");

Route::get("getfreeshippingamount","API\PreLoginController@getFreeShippingAmount");