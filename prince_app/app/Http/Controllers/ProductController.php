<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use Mail;	
use Validator;
use Srmklive\PayPal\Facades\Paypal;
use Srmklive\PayPal\Services\ExpressCheckout;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    { 
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
	 
    public function detail(Request $request,$slug){

		$result=\App\Helpers\commonHelper::callAPI('GET','/product-detail?slug='.$slug);
		
		if($result->status==200){
			
			$result=json_decode($result->content,true);
			$result=$result['result'];

			$latestOrders=\App\Helpers\commonHelper::callAPI('GET','/latest-orders');

			$onDemandProduct=false;

			$categoryTreeIds=\App\Helpers\commonHelper::getParentId($result['category_id']);

			$userDetail=[];$onDemandProduct=false;

			if(in_array('58',explode(',',$categoryTreeIds))){

				$onDemandProduct=true;

				$userResult=\App\Helpers\commonHelper::callAPI('userTokenget','/user-profile');
				 
				if($userResult->status==200){

					$userDetail=json_decode($userResult->content,true);
					$userDetail=$userDetail['data'];

				}
			}
			
			return view('product_detail',compact('latestOrders','result','onDemandProduct','userDetail'));
		
		}else{
			
			return redirect('/');
		
		}
		
    }
	 
    public function getVariantSlug(Request $request){

		$attributeId=$request->post('value');
		$attribiteIdArray=explode(',',$attributeId);
		sort($attribiteIdArray);
 
		$result=\App\Models\Variantproduct::where([
												['product_id',$request->post('product_id')],
												['variant_attributes',implode(',',$attribiteIdArray)],
												['status','1'],
												['recyclebin_status','0']
												])->first();

		if($result){
			 
			return response(array('message'=>'Product fetched successfully.','url'=>url('product-detail/'.$result->slug)),200);
		
		}else{
			
			return response(array('message'=>'Product not found.'),404);
		
		}
		
    }

	public function productListing(Request $request,$categoryslug){

		if($request->ajax()){

			$result=\App\Helpers\commonHelper::callAPI('GET','/productlist?sort_order='.$request->post('sort_order').'&price_id='.$request->post('price_id').'&attributeId='.$request->post('attributeId').'&category_slug='.$categoryslug.'&offset='.$request->post('offset').'&limit='.$request->post('limit'));
			$resultData=json_decode($result->content,true);

			if($result->status==200){

				$data=$resultData['result'];
				$offset=$request->post('offset');

				$wishlist=[];

				if(Session::has('wishlist_user')){

					$wishlist=Session::get('wishlist_user');
				}

				if($request->post('ondemandproduct')){

					$html=view('product_listing_product_ondemand',compact('data','offset','wishlist'))->render();

				}else{

					$html=view('product_listing_product',compact('data','offset','wishlist'))->render();
				}
				 
				return response(array('message'=>$resultData['message'],'html'=>$html,'total'=>count($data)),$result->status);
			}

			return response(array('message'=>$resultData['message']),$result->status);

		}

		$getCategoryId = \App\Models\Category::where('slug',$categoryslug)->first();

		if($getCategoryId){

			$getSelCategoryTreeId = \App\Helpers\commonHelper::getParentId($getCategoryId->id);

			$getParentCategory= \App\Models\Category::whereIn('id',explode(',',$getSelCategoryTreeId))->where('parent_id',Null)->first();

			$category=\App\Helpers\commonHelper::getCategoryTree($getParentCategory->id);

			$variant=\App\Helpers\commonHelper::callAPI('GET','/variant-attribute-list?category_id='.$getSelCategoryTreeId);

			$slugCategoryResult=\App\Models\Category::whereIn('id',explode(',',$getSelCategoryTreeId))->orderBy('id','Asc')->get();

			$ondemandCategory=false;

			if(in_array('58',explode(',',$getSelCategoryTreeId))){

				$ondemandCategory=true;
			}

			return view('product_listing',compact('getCategoryId','variant','getParentCategory','category','categoryslug','slugCategoryResult','ondemandCategory'));

		}else{

			return redirect()->back()->with('5fernsuser_error','Something went wrong. Please try again.');
		}
	}

	public function delasOfTheWeek(Request $request){

		if($request->ajax()){

			$result=\App\Helpers\commonHelper::callAPI('GET','/dealsoftheweek-productlist?sort_order='.$request->post('sort_order').'&offset='.$request->post('offset').'&limit='.$request->post('limit'));
			$resultData=json_decode($result->content,true);

			if($result->status==200){

				$data=$resultData['result'];
				$offset=$request->post('offset');

				$wishlist=[];

				if(Session::has('wishlist_user')){

					$wishlist=Session::get('wishlist_user');
				}

				$html=view('listing_dealsoftheweek_product',compact('data','offset','wishlist'))->render();
				 
				return response(array('message'=>$resultData['message'],'html'=>$html,'total'=>count($data)),$result->status);
			}
		}

		return view('listing_dealsoftheweek');
	} 

	public function addTocart(Request $request){

		if($request->ajax()){

			if(Session::has('5ferns_user')){

				$result=\App\Helpers\commonHelper::callAPI('userTokenpost','/add-cart',json_encode(array('id'=>'0','product_id'=>$request->post('product_id'),'qty'=>$request->post('qty'),'remark'=>$request->post('remark'),'add_type'=>'add')));
				$resultData=json_decode($result->content,true);

				return response(array('message'=>$resultData['message']),$result->status);

			}else{

				$rules = [ 
					'product_id'=>'required|exists:variantproducts,id',
					'qty'=>'required|numeric'
				];   
		
				$variantProductResult=\App\Models\Variantproduct::where('id',$request->post('product_id'))->first();
				$variantAttributeArray=explode(',',$variantProductResult->variant_attributes);
		
				if(in_array('17',$variantAttributeArray) || in_array('18',$variantAttributeArray) || in_array('19',$variantAttributeArray)){
		
					$rules['remark']='required';
				}
		
				$messages['remark.required']="Custom Remark Field is required";
		
				$validator = Validator::make($request->all(), $rules,$messages);
				
				if ($validator->fails()){
					$message = "";
					$messages_l = json_decode(json_encode($validator->messages()), true);
					foreach ($messages_l as $msg) {
						$message= $msg[0];
						break;
					}
					
					return response(array('message'=>$message),403);
					
				}else{

					$data=Session::get('5ferns_cartuser');

					$productResult=\App\Models\Product::where('variantproducts.id',$request->post('product_id'))->join('variantproducts','products.id','=','variantproducts.product_id')->first();

					if($productResult){

						$imagesArray=explode(',',$productResult->images);

						if(isset($data[$request->post('product_id')]['qty'])){

							$qty=$data[$request->post('product_id')]['qty']+$request->post('qty');

						}else{

							$qty=$request->post('qty');
						}

						$data[$request->post('product_id')]=array(
							'id'=>$request->post('product_id'),
							'product_id'=>$request->post('product_id'),
							'product_name'=>ucfirst($productResult->name),
							'qty'=>$qty,
							'sale_price'=>$productResult->sale_price,
							'discount_amount'=>$productResult->discount_amount,
							'offer_price'=>\App\Helpers\commonHelper::getOfferProductPrice($productResult->sale_price,$productResult->discount_type,$productResult->discount_amount),
							'package_length'=>$productResult->package_length,
							'package_breadth'=>$productResult->package_breadth,
							'package_height'=>$productResult->package_height,
							'package_weight'=>$productResult->package_weight,
							'image'=>asset('uploads/products/'.$imagesArray[0]),
							'short_description'=>$productResult->short_description,
							'remark'=>$request->post('remark')
						);

					}
				
					Session::put('5ferns_cartuser',$data);

					return response(array('message'=>'Product successfully added into cart'),200);

				}

			}
			

		}else{

			return response(array('message'=>'Method not found. Pleae try again.'),404);
		}
		
	}

	public function cart(Request $request){

		if(!Session::has('5ferns_user')){
			
			$result=Session::get('5ferns_cartuser');

		}else{

			$result=[];

			$apiData=\App\Helpers\commonHelper::callAPI('userTokenget','/cart-list');
	
			$resultData=json_decode($apiData->content,true);

			if($apiData->status==200){

				$result=$resultData['result'];
			}

		}

		$country=\App\Models\Country::select('id','name','phonecode')->get();

		return view('cart',compact('result','country'));
	}

	public function getTotalCart(Request $request){
		
		$result=array();
		
		if(!Session::has('5ferns_user') && Session::has('5ferns_cartuser')){
			
			$result=Session::get('5ferns_cartuser');

		}else{

			$result=[];

			$apiData=\App\Helpers\commonHelper::callAPI('userTokenget','/cart-list');
			$resultData=json_decode($apiData->content,true);

			if($apiData->status==200){

				$result=$resultData['result'];

			}

		}

		return response(array('message'=>'Cart count fetched successfully.','total_count'=>count($result)),200);
	}

	public function updateCart(Request $request){

		if(Session::has('5ferns_user')){

			$result=\App\Helpers\commonHelper::callAPI('userTokenpost','/add-cart',json_encode(array('id'=>$request->post('id'),'product_id'=>$request->post('product_id'),'qty'=>$request->post('qty'),'add_type'=>'update')));
			$resultData=json_decode($result->content,true);

			return response(array('message'=>$resultData['message']),$result->status);

		}else{

			$cartData=Session::get('5ferns_cartuser');

			if(!empty($cartData) && isset($cartData[$request->post('product_id')])){

				$cartData[$request->post('product_id')]['qty']=$request->post('qty');

				Session::put('5ferns_cartuser',$cartData);

				return response(array('message'=>'Cart updated successfully.'),200);

			}else{

				return response(array('message'=>'Something went wrong. Please try again.'),403);

			}
		}
	}

	public function deleteCart(Request $request,$cartId){

		if(Session::has('5ferns_user')){

			$result=\App\Helpers\commonHelper::callAPI('userTokenpost','/delete-cart',json_encode(array('id'=>$cartId)));
			$resultData=json_decode($result->content,true);

			Session::flash('5fernsuser_success',$resultData['message']);

		}else{

			$cartData=Session::get('5ferns_cartuser');

			if(!empty($cartData) && isset($cartData[$cartId])){

				unset($cartData[$cartId]);

				Session::put('5ferns_cartuser',$cartData);

				Session::flash('5fernsuser_success','Cart delete successfully.');

			}else{

				Session::flash('5fernsuser_error','Something went wrong. Please try again.');

			}
		}

		return redirect('cart');
	}

	public function getCartPriceDetails(Request $request){

		$result=array(); $totalItems='0'; $totalMrp=0;$totalShipping=0; $discountAmount=0; $finalAmount=0;$couponAmount=0;

		if(!Session::has('5ferns_user') && Session::get('5ferns_cartuser')){
			
			$result=Session::get('5ferns_cartuser');

		}else{

			$result=[];

			$apiData=\App\Helpers\commonHelper::callAPI('userTokenget','/cart-list');
			$resultData=json_decode($apiData->content,true);

			if($apiData->status==200){

				$result=$resultData['result'];

			}

		}

		if(!empty($result)){

			foreach($result as $value){

				$shippingAmount=\App\Helpers\commonHelper::getShippingAmount($value['package_length'],$value['package_breadth'],$value['package_height'],$value['package_weight'],$request->get('countryId'));

				$totalMrp+=($value['sale_price']*$value['qty']);
				$totalShipping+=($shippingAmount*$value['qty']);
				$discountAmount+=(($value['sale_price']-$value['offer_price'])*$value['qty']);
				$finalAmount+=(($value['offer_price'])*$value['qty']);
			}
		}

		$amountForCoupon=$totalMrp-$discountAmount;
		
		if($request->get('coupondisc_type')=='1'){

			$couponAmount=round((($amountForCoupon*$request->get('coupondisc_amount'))/100),2);

		}else if($request->get('coupondisc_type')=='2'){

			$couponAmount=round($request->get('coupondisc_amount'),2);

		}

		$finalAmount-=$couponAmount;

		$totalItems=count($result);

		$freeShippingAmount=\App\Helpers\commonHelper::callAPI('GET','/getfreeshippingamount');

		if($freeShippingAmount->status==200){

			$freeShippingResult=json_decode($freeShippingAmount->content,true);

			if($finalAmount>=(float) $freeShippingResult['amount']){

				$totalShipping=0;

			}

		}

		$finalAmount+=$totalShipping;

		$html=view('cart_price_details',compact('totalItems','totalMrp','totalShipping','discountAmount','finalAmount','couponAmount','amountForCoupon'))->render();

		return response(array('message'=>'Product price detail fetched successfully.','html'=>$html));
	}

	public function checkOut(Request $request){

		$rules['payment_type']='required|in:1,2';

		if($request->post('type')=='1'){

			$rules['phone_code']='required|in:1,2';
			$rules['country_id']='required|exists:countries,id';
			$rules['state_id']='required|exists:states,id';
			$rules['city_id']='required|exists:cities,id';
		}

		$validator = Validator::make($request->all(), $rules);
		
		if ($validator->fails()){
			
			$message = "";
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('message'=>$message),403);
			
			
		}else{

			if(Session::has('5ferns_user')){

				$data=[
					'address_id'=>$request->post('address_id'),
					'payment_type'=>$request->post('payment_type'),
					'coupon_id'=>$request->post('coupon_id'),
					'currency_id'=>Session::get('country_id'),
					'type'=>'2'
				];
	
				$apiData=\App\Helpers\commonHelper::callAPI('userTokenpost','/checkout',json_encode($data));
	
			}else{
	
				$cartData=Session::has('5ferns_cartuser');

				if($cartData){
	
					$data=array(
						'type'=>'1',
						'name'=>$request->post('name'),
						'email'=>$request->post('email'),
						'phone_code'=>$request->post('phone_code'),
						'mobile'=>$request->post('mobile'),
						'country_id'=>$request->post('country_id'),
						'state_id'=>$request->post('state_id'),
						'city_id'=>$request->post('city_id'),
						'address_line1'=>$request->post('address_1'),
						'address_line2'=>$request->post('address_2'),
						'pincode'=>$request->post('pincode'),
						'payment_type'=>$request->post('payment_type'),
						'coupon_id'=>$request->post('coupon_id'),
						'cart_data'=>Session::get('5ferns_cartuser'),
						'currency_id'=>Session::get('country_id')
					);
	
					$apiData=\App\Helpers\commonHelper::callAPI('POST','/guest-checkout',json_encode($data));

				}else{
	
					return response(array('message'=>'Cart is empty'),404);
				}
			}

			$resultData=json_decode($apiData->content,true);

			if($request->post('payment_type')=='2'){

				return response(array('message'=>'Order Placed successfully.','checkout_type'=>'cod','checkout_order_id'=>$resultData['order_id']),$apiData->status);
	
			}else{

				if($apiData->status==200){
				
					$apiData=\App\Helpers\commonHelper::callAPI('POST','/initiate-payment',json_encode(array('order_id'=>$resultData['order_id'])));
					$resultData=json_decode($apiData->content,true);
	
					if($apiData->status==200){

						if($resultData['payment_gateway']=='paypal'){
 

							return response(array('message'=>$resultData['message'],'type'=>$resultData['payment_gateway'],'link'=>$resultData['payment_link']),$apiData->status);
							

						}else{

							$json['checkout_order_id']=$resultData['order_id'];

							$json['payment_options']=[
								"key"=>env('RAZOR_KEY'),
								"amount"=>$resultData['order_amount'],
								"currency"=>"INR",
								"name"=>$resultData['userdata']['name'],
								"description"=>$resultData['userdata']['description'],
								"order_id"=>$resultData['razorpay_orderid'],
								"prefill"=>[
									"name"=>$resultData['userdata']['name'],
									"email"=>$resultData['userdata']['email'],
									"contact"=>$resultData['userdata']['contact']
								],
								"notes"=>[
									"address"=>$resultData['userdata']['address'],
									"merchant_order_id"=>$resultData['order_id'],
									"merchant_transactionid"=>$resultData['transaction_id'],
									"sales_id"=>$resultData['suborderid']
								],
								"theme"=>[
								]
							];

							$json['type']=$resultData['payment_gateway'];

						}

						return response($json,200);
	
					}else{
	
						return response(array('message'=>$resultData['message']),$apiData->status);
					}
	
				}else{
	
					return response(array('message'=>$resultData['message']),404);
				}
			}
		}
		
	}

	public function getSavedAddress(Request $request){

		$apiData=\App\Helpers\commonHelper::callAPI('userTokenget','/address-list');
		$resultData=json_decode($apiData->content,true);

		$result=[];

		if($apiData->status==200){

			$result=$resultData['result'];
		}

		$html=view('saved_address_list',compact('result'))->render();

		return response(array('messsages'=>'Address fetched successfully.','html'=>$html),200);
	}

	public function orderPlaced(Request $request,$orderId){

		$result=\App\Models\Sales::where('order_id',$orderId)->first();

		if($result){

			//delete cart
			if(Session::has('5ferns_user')){

				\App\Helpers\commonHelper::callAPI('userTokenget','/delete-complete-cart');

			}else{

				Session::forget('5ferns_cartuser');
			}
			
			return view('order_placed',compact('orderId'));

		}else{

			return redirect('/')->with('5fernsuser_error','Invalid Order id.');
		}

	}


	public function checkCouponCode(Request $request){


		if(!Session::has('5ferns_user')){
 
			return response(array('message'=>'Please First login to get the benefit of coupon code.'),403);
			
		}else{

			$apiData=\App\Helpers\commonHelper::callAPI('userTokenpost','/check-coupon-code',json_encode(array('coupon_code'=>$request->get('coupon_code'),'order_amount'=>$request->get('amountForCoupon'))));
			$resultData=json_decode($apiData->content,true);

			if($apiData->status==200){

				return response(array('message'=>$resultData['message'],'coupon_id'=>$resultData['coupon_id'],'discount_type'=>$resultData['discount_type'],'discount_amount'=>$resultData['discount_amount']),$apiData->status);
			
			}else{

				return response(array('message'=>$resultData['message']),$apiData->status);
			}

		}

	}

	public function checkPincode(Request $request){

		$apiData=\App\Helpers\commonHelper::callAPI('GET','/check-pincode?filter_codes='.$request->post('pincode'));
		$resultData=json_decode($apiData->content,true);

		return response(array('message'=>$resultData['message']),$apiData->status);

	}

	public function productWishlist(Request $request){

		if(!Session::has('5ferns_user')){

			Session::flash('5fernsuser_error','Please first login.');
			return response(array('message'=>'Please login first','login'=>false),200);

		}else{

			$apiData=\App\Helpers\commonHelper::callAPI('userTokenpost','/wishlist-product',json_encode(array('product_id'=>$request->post('product_id'))));
			$resultData=json_decode($apiData->content,true);

			if($apiData->status==200){

				Session::put('wishlist_user',$resultData['wishlistid']);
			}
			return response(array('message'=>$resultData['message'],'login'=>true),$apiData->status);

		}
	}

	public function deleteWishlistProduct(Request $request,$id){

		$apiData=\App\Helpers\commonHelper::callAPI('userTokenpost','/delete-wishlist-product',json_encode(array('wishlist_id'=>$id)));
		$resultData=json_decode($apiData->content,true);

		if($apiData->status==200){

			Session::flash('5fernsuser_success',$resultData['message']);

		}else{

			Session::flash('5fernsuser_erro',$resultData['message']);
		}

		return redirect('my-wishlists');

	}


	public function failOrder(Request $request,$orderId){

		$apiData=\App\Helpers\commonHelper::callAPI('userTokenpost','/fail-order',json_encode(array('order_id'=>$orderId)));
		$resultData=json_decode($apiData->content,true);

		return response(array('message'=>$resultData['message']),$apiData->status);		
	}


	public function paymentCancel(Request $request){
 
		$result=\App\Models\Transaction::where('paypal_token',$request->token)->where('payment_status','0')->first();

		if($result){
			
			$orderId=$result['order_id'];

			$apiData=\App\Helpers\commonHelper::callAPI('userTokenpost','/fail-order',json_encode(array('order_id'=>$result['order_id'])));
			$resultData=json_decode($apiData->content,true);

			return view('order_failed',compact('orderId'));

		}else{

			return redirect('/')->with('5fernsuser_error','Invalid Token');
		}
			
	}
	
	public function paymentSuccess(Request $request){
 
		$payment=\App\Models\Transaction::where('paypal_token',$request->token)->where('payment_status','0')->first();

		if($payment){

			$payment->paypal_payerid=$request->PayerID;
			$payment->payment_status='2';
			$payment->save();

			$orderId=$payment['order_id'];

			//delete cart
			if(Session::has('5ferns_user')){

				\App\Helpers\commonHelper::callAPI('userTokenget','/delete-complete-cart');

			}else{

				Session::forget('5ferns_cartuser');
			}

			$result=\App\Models\Sales::with('getsalesdetailchild')->where('sales.order_id',$orderId)->first()->toArray();

			if(!empty($result)){
				 
				// send Msg
				$content ="https://bulksmsapi.vispl.in/?username=fiveotp&password=five_1234&messageType=text&mobile=".$result['phone_code'].$result['mobile']."&senderId=FFERNS&ContentID=1707163756701539494&EntityID=1701163375929957226&message=Order Placed: Thank you for Shopping on FiveFerns. We have received an order with orderID: ".$result['order_id'].". We will notify you as soon as the order is Confirmed. - Team FiveFerns";				
				\App\Helpers\commonHelper::sendMsg($content);

				\Mail::send('email_templates.order_place', compact('result'), function($message) use ($result)
				{
					$message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
					$message->subject('Order Placed Successfully');
					$message->to($result['email']);
				});

			}
			
			return view('order_placed',compact('orderId'));

		}else{

			return redirect('/')->with('5fernsuser_error','Invalid Token');

		}

	}
	

    public function ondemandEnquiry(Request $request){


		$data=array(
			'name'=>$request->post('name'),
			'email'=>$request->post('email'),
			'mobile'=>$request->post('mobile'),
			'description'=>$request->post('description'),
			'product_id'=>$request->post('product_id'),
			'qty'=>$request->post('qty'),
		);

		$result=\App\Helpers\commonHelper::callAPI('POST','/ondemand-enquiry',json_encode($data));


        $resultData=json_decode($result->content,true);

        return response(array('message'=>$resultData['message']),$result->status);
        
    }

}
