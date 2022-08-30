<?php

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

	
	//admin URLS  
	Route::get('/',function(){
		return redirect('admin/login');
	});
	
	Route::get('admin/login', 'Auth\LoginController@showLoginForm');
	Route::post('admin/login', 'Auth\LoginController@login')->name('admin.login'); 
		
	Route::group(['middleware' => ['auth']], function() {
		Route::resource('roles', 'RoleController');
	});

	Route::group(['prefix'=>'admin','as'=>'admin','middleware'=>['auth','checkadminurl'],'as'=>'admin.'],function() {

		Route::match(['get','post'],'/change-password', 'Admin\AdminController@changePassword')->name('changepassword');
		Route::get('dashboard', 'Admin\DashboardController@index');
		Route::post('logout', 'Auth\LoginController@logout')->name('logout');
		
		//subadmin
		Route::group(['prefix'=>'subadmin'],function() {
			Route::match(['get','post'],'add', 'Admin\UserController@addSubAdmin')->name('subadmin.add');
			Route::get('/list', 'Admin\UserController@subadminList');
			Route::get('update/{id}','Admin\UserController@updateSubAdmin');
			Route::get('delete/{id}','Admin\UserController@deleteSubAdmin');
			Route::post('change-status','Admin\UserController@changeStatus')->name('subadmin.changestatus');
		});

		
		//blog category
		Route::group(['prefix'=>'blog'],function() {
			Route::match(['get','post'],'category/add','Admin\BlogCategoryController@add')->name('category.add');
			Route::get('category/list', 'Admin\BlogCategoryController@categoryList')->name('category.list');
			Route::get('category/update/{id}','Admin\BlogCategoryController@categoryUpdate')->name('category.update');
			Route::get('category/delete/{id}','Admin\BlogCategoryController@categoryDelete')->name('category.delete');
			Route::post('category/change-status','Admin\BlogCategoryController@changeStatus')->name('category.changestatus');

			Route::match(['get','post'],'add','Admin\BlogController@add')->name('blog.add');
			Route::get('list', 'Admin\BlogController@blogList')->name('blog.list');
			Route::get('update/{id}','Admin\BlogController@blogUpdate')->name('blog.update');
			Route::get('delete/{id}','Admin\BlogController@blogDelete')->name('blog.delete');
			Route::post('change-status','Admin\BlogController@blogStatus')->name('blog.changestatus');

		});

		// slider list
		Route::group(['prefix'=>'slider'],function() {
			Route::match(['get','post'],'add', 'Admin\SliderController@add')->name('slider.add');
			Route::get('list', 'Admin\SliderController@sliderList');
			Route::post('change-status','Admin\SliderController@changeStatus')->name('slider.changestatus');
			Route::get('update/{id}','Admin\SliderController@updateSlider');
			Route::get('delete/{id}','Admin\SliderController@deleteSlider');
		});
		
		
		// pincode list
		Route::group(['prefix'=>'pincode'],function() {
			Route::match(['get','post'],'add', 'Admin\PincodeController@add')->name('pincode.add');
			Route::get('list', 'Admin\PincodeController@sliderList');
			Route::post('change-status','Admin\PincodeController@changeStatus')->name('pincode.changestatus');
			Route::get('update/{id}','Admin\PincodeController@updateSlider');
			Route::get('delete/{id}','Admin\PincodeController@deleteSlider');
		});
		
		//category
		Route::group(['prefix'=>'catalog'],function() {
			
			Route::group(['prefix'=>'category'],function() {
				Route::match(['get','post'],'add', 'Admin\CategoryController@add')->name('category.addcategory');
				Route::get('/list', 'Admin\CategoryController@categoryList');
				Route::get('update/{id}','Admin\CategoryController@updateCategory');
				Route::get('delete/{id}','Admin\CategoryController@deleteCategory');
				Route::post('change-status','Admin\CategoryController@changeStatus')->name('category.changestatus');
				Route::post('select-topcategory','Admin\CategoryController@selectTopCategory')->name('category.selectyopcategory');
			    Route::post('must-category','Admin\CategoryController@MustCategory')->name('category.mustcategory');
				Route::post('sub-category','Admin\CategoryController@selectSubCategory')->name('get.sub.category');
			});
			
			Route::group(['prefix'=>'brand'],function() {
				Route::match(['get','post'],'add', 'Admin\BrandController@add')->name('brand.add');
				Route::get('/list', 'Admin\BrandController@categoryList');
				Route::get('update/{id}','Admin\BrandController@updateCategory');
				Route::get('delete/{id}','Admin\BrandController@deleteCategory');
				Route::post('change-status','Admin\BrandController@changeStatus')->name('brand.changestatus');
				Route::post('home-status','Admin\BrandController@HomeStatus')->name('brand.homestatus');
			});
			
			Route::group(['prefix'=>'product'],function() {
				Route::match(['get','post'],'add', 'Admin\ProductController@add')->name('product.addproduct');
				Route::match(['get','post'],'list', 'Admin\ProductController@productList')->name('product.filter');
				Route::get('update/{id}','Admin\ProductController@updateProduct');
				Route::get('delete/{id}','Admin\ProductController@deleteProduct');
				Route::post('change-status','Admin\ProductController@changeStatus')->name('product.changestatus');
				Route::post('topselling-status','Admin\ProductController@topSellingStatus')->name('product.topsellingstatus');
				Route::post('dealsoftheday','Admin\ProductController@dealsofTheDay')->name('product.dealsoftheday');
				Route::post('dealsoftheweek','Admin\ProductController@dealsofTheWeek')->name('product.dealsoftheweek');
				
				Route::match(['get','post'],'add-variant-product/{any}', 'Admin\ProductController@addVariantProduct')->name('product.addvariant');
				Route::get('variant-productlist/{id}', 'Admin\ProductController@variantProductList');
				Route::get('update-variant-product/{pparent_id}/{variant_id}', 'Admin\ProductController@updateVariantProduct');
				Route::get('delete-variantproduct/{id}','Admin\ProductController@deleteVariantProduct');
				Route::post('variantproduct-change-status','Admin\ProductController@changeVariantProductStatus')->name('product.variantproduct.changestatus');
			});
			
			Route::group(['prefix'=>'variant-attribute'],function() {
				Route::match(['get','post'],'add', 'Admin\ProductController@addVariantAttribute');
				Route::get('list', 'Admin\ProductController@attributeList');
				Route::get('update-variant-attribute/{id}','Admin\ProductController@updateVariantAttribute');
			});
			
			Route::group(['prefix'=>'variant'],function() {
				Route::match(['get','post'],'add', 'Admin\ProductController@addVariant');
				Route::get('list', 'Admin\ProductController@VariantList');
				Route::get('update/{id}','Admin\ProductController@updateVariant');
				Route::post('status','Admin\ProductController@statusVariant')->name('status.variant');
			});
			
			Route::group(['prefix'=>'coupon'],function() {
				Route::match(['get','post'],'add', 'Admin\CouponController@add')->name('coupon.add');
				Route::get('list', 'Admin\CouponController@couponList');
				Route::get('update/{id}','Admin\CouponController@updateCoupon');
				Route::get('delete/{id}','Admin\CouponController@deleteCoupon');
				Route::post('change-status','Admin\CouponController@changeStatus')->name('coupon.changestatus');
			});

			
		});
		
		
		//customers
		Route::group(['prefix'=>'user'],function() {
			Route::get('/list', 'Admin\UserController@userList');
			Route::post('block-user','Admin\UserController@blockUser')->name('user.block');
			Route::get('update/{id}','Admin\UserController@updateUser');
			Route::post('update-user','Admin\UserController@updateUserEnd');
			Route::get('address-book/{id}','Admin\UserController@addressBookList');
			Route::get('delete-address/{id}','Admin\UserController@deleteAddress');
			Route::post('add-address','Admin\UserController@addAddress');
			Route::get('update-address/{userid}/{id}','Admin\UserController@updateAddress');
			Route::post('getstates-bycountryid','Admin\UserController@getStateByCountryId')->name('getstates-bycountryid');
			Route::post('getcity-bystateid','Admin\UserController@getCityByStateId')->name('getcity-bystateid');
			Route::get('view-order/{id}','Admin\UserController@viewOrder');
		});
		
		// sales list
		Route::group(['prefix'=>'sales'],function() {
			Route::get('list/{type}', 'Admin\SalesController@salesList');
			Route::post('getsaledetail','Admin\SalesController@getSalesDetail');
			Route::post('update-orderstatus','Admin\SalesController@updateOrderStatus');
			Route::get('download-packaging-slip/{no}','Admin\SalesController@downloadPackagingSlip');
			Route::get('order-invoice/{no}','Admin\SalesController@orderInvoice');
			Route::get('ondemand-enquiry','Admin\SalesController@ondemandEnquiry');
			Route::post('orderready','Admin\SalesController@orderReady')->name('sales.orderready');

			//mannual order
			Route::match(['get','post'],'mannual-orders/create-order','Admin\SalesController@createMannualOrder')->name('sales.createmanualorder');
			Route::get('mannual-orders/orders-list','Admin\SalesController@mannualOrdersList');
			Route::post('mannual-getsaledetail','Admin\SalesController@getMannualSalesDetail');
			Route::get('mannual-orders/order-invoice/{id}','Admin\SalesController@mannualOrderInvoice');
		});
		
		Route::group(['prefix'=>'testimonial'],function() {
			Route::match(['get','post'],'add', 'Admin\TestimonialController@add')->name('testimonial.add');
			Route::get('/list', 'Admin\TestimonialController@testimonialList');
			Route::get('update/{id}','Admin\TestimonialController@updateTestimonial');
			Route::get('delete/{id}','Admin\TestimonialController@deleteTestimonial');
			Route::post('change-status','Admin\TestimonialController@changeStatus')->name('testimonial.changestatus');
		});

		Route::get('/transaction-history', 'Admin\TransactionController@index');
		
		// information list
		Route::group(['prefix'=>'information'],function() {
			Route::get('term-and-condition', 'Admin\InformationController@termCondition');
			Route::get('privacy-policy', 'Admin\InformationController@privacyPolicy');
			Route::get('about-us', 'Admin\InformationController@aboutUs');
			Route::get('return-and-refund-policy', 'Admin\InformationController@returnPolicy');
			Route::get('shipping-policy', 'Admin\InformationController@shippingPolicy');
			Route::get('cancellation-policy', 'Admin\InformationController@cancellationPolicy');
			Route::post('update','Admin\InformationController@UpdateDetail')->name('information.update');
		});

		// seo
		Route::group(['prefix'=>'seo'],function() {
			Route::match(['get','post'],'home-page', 'Admin\SeoController@homePage');
		});

		// settings
		Route::group(['prefix'=>'settings'],function() {
			Route::match(['get','post'],'update-price', 'Admin\SettingController@updatePrice');
			Route::match(['get','post'],'currency', 'Admin\SettingController@updateCurrency');
		});
		
	});

	Route::any('delhivery-webhook',function(){
		echo "Done";
	});

	
	