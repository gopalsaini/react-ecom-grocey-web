<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\commonHelper;
use DB; 
use Validator;
use App\Models\Event;
use Hash;
use paytm\paytmchecksum\PaytmChecksum;

class PostLoginController extends Controller
{

    
	function userProfile(Request $request){
		
		
		$data=[
			'name'=>ucfirst($request->user()->name),
			'mobile'=>$request->user()->mobile,
			'email'=>$request->user()->email,
			'gender'=>$request->user()->gender,
		];
		
		
		return response(array('message'=>"Profile data fetched successfully..","data"=>$data),200);
		
		
	}
	
	public function addAddress(Request $request){

		$rules = [ 
			'id'=>'required|numeric',
			'name'=>'required',
			'mobile'=>'required',
			'email'=>'required',
			'address_line1'=>'required',
			'address_line2'=>'required',
			"city"=>'required',   
			"pincode"=>'required',      
		];   

		$validator = Validator::make($request->json()->all(), $rules);
		
		if ($validator->fails()){
			$message = "";
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('error'=>true,'message'=>$message),403);
			
		}else{
			
			try{
			    
		        $pincode = \App\Models\Pincode::where('pincode',$request->json()->get('pincode'))->first();
		        
		        if($pincode){
		
    				if($request->json()->get('id')>0){
    
    					$address= \App\Models\Addressbook::find($request->json()->get('id'));
    
    				}else{
    					
    					$address= new \App\Models\Addressbook();
    				}
    				
    
    				$address->user_id=$request->user()->id;
    				$address->name=$request->json()->get('name');
    				$address->mobile=$request->json()->get('mobile');
    				$address->email=$request->json()->get('email');
    				$address->address_line1=$request->json()->get('address_line1');
    				$address->address_line2=$request->json()->get('address_line2');
    				$address->city=$request->json()->get('city');
    				$address->pincode=$request->json()->get('pincode');
    				$address->save();
    				
    				if($request->json()->get('id')>0){
    					
    					return response(array('error'=>false,'message'=>'Address updated successfully.'),200);
    				}else{
    					
    					return response(array('error'=>false,'message'=>'Address added successfully.'),200);
    				}
    				
		        }else{
    					
					return response(array('error'=>true,'message'=>'Address pincode is not serviceable'),200);
				}	

			}catch (\Exception $e){
				
				return response(array('error'=>true,"message" => "Something went wrong.please try again"),403); 
				
			}
	   }
		 
	}

	public function updateProfile(Request $request){
	
		try{
			
				$user= \App\Models\User::find($request->user()->id);

				$user->name=$request->json()->get('name');
				$user->email=$request->json()->get('eamil');
				$user->gender=$request->json()->get('gender');
				$user->mobile=$request->json()->get('mobile');
				$user->save();
				
				return response(array('message'=>'Profile updated successfully.'),200);
				
			
		}catch (\Exception $e){
			
			return response(array("message" => "Something went wrong.please try again"),403); 
			
		}
	}
	
	public function deleteAddress(Request $request){

		$rules = [ 
			'id'=>'required|numeric'      
		];   

		$validator = Validator::make($request->json()->all(), $rules);
		
		if ($validator->fails()){
			$message = "";
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('error'=>true,'message'=>$message),200);
			
		}else{
			
			try{
				
				$address= \App\Models\Addressbook::where([
														['id','=',$request->json()->get('id')],						
														['user_id','=',$request->user()->id],					
														])->first();

				if(!$address){

					return response(array('error'=>true,"message" => "invalid address id."),200); 

				}else{
					
					\App\Models\Addressbook::where('id',$request->json()->get('id'))->delete();
					
					return response(array('error'=>false,"message" => "Address deleted successfully."),200); 
				}
				
			}catch (\Exception $e){
				
				return response(array('error'=>true,"message" => "Something went wrong.please try again"),200); 
				
			}
	   }
		 
	}
	
	public function addressList(Request $request){

		try{
				
			$address= \App\Models\Addressbook::where([						
													['user_id','=',$request->user()->id],						
													])->get();

			if(!$address){

				return response(array("message" => "Result not found."),404); 

			}else{
				
				$result=[];

				foreach($address as $raw){
				
					$result[]=[
						'id'=>$raw['id'],
						'name'=>ucfirst($raw['name']),
						'mobile'=>$raw['mobile'],
						'email'=>$raw['email'],
						'address_line1'=>$raw['address_line1'],
						'address_line2'=>$raw['address_line2'],
						'city'=>$raw['city'],
						'pincode'=>$raw['pincode'],
					];
				}
				
				return response(array("message" => "Address fetched successfully.","result"=>$result),200); 
			}
			
		}catch (\Exception $e){
			
			return response(array("message" => "Something went wrong.please try again"),403); 
			
		}
		 
	}
	
	public function order(Request $request){

		try{
				
			$sales= \App\Models\Sales_detail::select('sales_details.*')->where([
				['sales_details.user_id','=',$request->user()->id]
				])->join('sales','sales.id','=','sales_details.sale_id')->orderBy('sales_details.id','desc')->get()->toArray();

			if(!$sales){

				return response(array('error'=>true,"message" => "Result not found."),200); 

			}else{
				
				$result=[];

				foreach($sales as $raw){
				
					$result[]=[
						'id'=>$raw['id'],
						'product_name'=>ucfirst($raw['product_name']),
						'order_id'=>$raw['order_id'],
						'suborder_id'=>$raw['suborder_id'],
						'product_image'=>$raw['product_image'],
						'qty'=>$raw['qty'],
						'sub_total'=>round($raw['sub_total']),
						'amount'=>round($raw['amount']),
						'created_at'=>date('F d, Y', strtotime($raw['created_at'])),
						'order_status'=>\App\Helpers\commonHelper::getOrderStatusName($raw['order_status']),
						'payment_status'=>\App\Helpers\commonHelper::getPaymentStatusName($raw['payment_status']),
						'payment_statusid'=>$raw['payment_status'],
					];
				}
				
				return response(array('error'=>false,"message" => "Orders fetched successfully.","result"=>$result),200); 
			}
			
		}catch (\Exception $e){
			
			return response(array('error'=>true,"message" => "Something went wrong.please try again"),200); 
			
		}
		 
	}
	
	
	public function addToCart(Request $request){

		$rules = [ 
			'product_id'=>'required|exists:products,id',
			'qty'=>'required|numeric',
		];   

		$validator = Validator::make($request->json()->all(), $rules);
		 
		if ($validator->fails()) {
			$message = [];
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('error'=>true,'message'=>$message),200);
			
		}else{
			
			try{
			    
			    $cart= \App\Models\Addtocart::where('product_id','=',$request->json()->get('product_id'))->sum('qty');
			    
			    $product= \App\Models\Product::where('id','=',$request->json()->get('product_id'))->first();
				
				if($product->stock > $cart){
				    
				    $cart= \App\Models\Addtocart::where([['user_id','=',$request->user()->id],['product_id','=',$request->json()->get('product_id')]])->sum('qty');
				    
				    
    				if($product->user_limit > $cart){
    				
        				$cart= \App\Models\Addtocart::where([				
        														['user_id','=',$request->user()->id],						
        														['product_id','=',$request->json()->get('product_id')]						
        														])->first();
        
        				
        				if(!$cart ){
        				    
        				    if($request->json()->get('qty') > 0){
        				        
            					$cart=new \App\Models\Addtocart();
            					$cart->user_id=$request->user()->id;
            					$cart->product_id=$request->json()->get('product_id');
            					$cart->qty=$request->json()->get('qty');
            					$cart->save();
            					return response(array('error'=>false,"message" => "Product successfully added into cart."),200); 
        				    }
        
        				}else{
        				    
        				    if($request->json()->get('qty') == 0){
        				       
            					$cart= \App\Models\Addtocart::where([				
        														['user_id','=',$request->user()->id],						
        														['product_id','=',$request->json()->get('product_id')]						
        														])->delete();
            					
        				    }else{
        				        
        				        $cart->qty=$request->json()->get('qty');
            					$cart->save();
            					
        				    }
        
        					return response(array('error'=>false,"message" => "Cart updated successfully."),200); 
        				}
    				
    				}else{
    				    
    				    return response(array('error'=>true,"message" => "The maximum order quantity for this product is limited exceed"),200);
    				    
    				}
    				
				}else{
				    
				    return response(array('error'=>true,"message" => "This product is out of stock"),200);
				    
				}
    			
				
			}catch (\Exception $e){
				
				return response(array('error'=>true,"message" => "Something went wrong.please try again."),200); 
				
			}
	   }
		 
	}
	
	
	public function deleteCart(Request $request){

		$rules = [ 
			'id'=>'required|numeric|exists:addtocarts,id'
		];   

		$validator = Validator::make($request->json()->all(), $rules);
		
		if ($validator->fails()){
			$message = "";
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('message'=>$message),200);
			
		}else{
			
			try{
				
				\App\Models\Addtocart::where([
											['user_id','=',$request->user()->id],
											['id','=',$request->json()->get('id')]
											])->delete();
				
				return response(array("message" => "Cart Product Delete successfully."),200); 
				
			}catch (\Exception $e){
				
				return response(array("message" => "Something went wrong.please try again"),200); 
				
			}
	   }
		 
	}
	
	public function deleteCompleteCart(Request $request){

		try{
				
				
			\App\Models\Addtocart::where([
										['user_id','=',$request->user()->id]
										])->delete();
			
			return response(array("message" => "User Cart delete successfully."),200); 
			
		}catch (\Exception $e){
			
			return response(array("message" => "Something went wrong.please try again"),200); 
			
		}
		 
	}
	
	public function cartList(Request $request){

		try{ 	
				
			$result=\App\Models\Addtocart::select('addtocarts.id as cartid','products.name','addtocarts.qty','products.sale_price','products.id','products.discount_type','products.discount_amount','products.images','products.short_description')->where([
										['addtocarts.user_id','=',$request->user()->id]
										])->join('products','products.id','=','addtocarts.product_id')->get();
			
			if($result->count()==0){
				
				$data=[]; $subTotal=0; $totalShipping=0; $discount=0; $couponId=0; $couponAmount=0; $netAmount=0;
                return response(array('error'=>true,"message" => "Cart list is empty.","result"=>$data,'netamount'=>$netAmount,'discount'=>$discount,'shipping'=>0.00,'subTotal'=>$subTotal),200); 
				
			}else{
				
				$data=[]; $subTotal=0; $totalShipping=0; $discount=0; $couponId=0; $couponAmount=0; $netAmount=0;

				
				foreach($result as $value){
					
					$imagesArray=explode(',',$value->images);

					$data[]=array(
						'id'=>$value->cartid,
						'product_id'=>$value->id,
						'product_name'=>ucfirst($value->name),
						'qty'=>$value->qty,
						'sale_price'=>round($value->sale_price),
						'discount_amount'=>round(\App\Helpers\commonHelper::getOfferProductPrice($value->sale_price,$value->discount_type,$value->discount_amount)),
						'offer_price'=>round($value->sale_price - (\App\Helpers\commonHelper::getOfferProductPrice($value->sale_price,$value->discount_type,$value->discount_amount))),
						'image'=>asset('uploads/products/'.$imagesArray[0]),
						'short_description'=>$value->short_description,
					);
					
					$offerPrice=\App\Helpers\commonHelper::getOfferProductPrice($value->sale_price,$value->discount_type,$value->discount_amount);
 
					$shippingAmount=0.00;

					$subTotal+=($value['sale_price']*$value['qty']);

					$totalShipping+=($shippingAmount*$value['qty']);

					$discount+=(($offerPrice)*$value['qty']);
						
					
				}
				
				$netAmount=($subTotal)-$discount;
				
				return response(array('error'=>false,"message" => "Cart list data fetched successfully.","result"=>$data,'netamount'=>round($netAmount),'discount'=>round($discount),'shipping'=>0,'subTotal'=>round($subTotal)),200); 
				
			}

		}catch (\Exception $e){
			
			return response(array('error'=>true,"message" => "Something went wrong.please try again"),403); 
			
		}
		
	}


	public function TotalCartCountQty(Request $request){

		try{ 	
				
			$result=\App\Models\Addtocart::where('user_id','=',$request->user()->id)->get();
			
			if($result->count()==0){
				
				return response(array('error'=>false,"message" => "Total Cart .","total" => 0),200);
				
			}else{
				
				$Total=0;

				
				foreach($result as $value){
					

					$Total+=($value['qty']);
					
				}
				
				return response(array('error'=>false,"message" => "Total Cart .","total" => $Total),200);

				
			}

		}catch (\Exception $e){
			
			return response(array('error'=>true,"message" => "Something went wrong.please try again"),200); 
			
		}
		
	}

	
	public function checkout(Request $request){

        
		$rules['MID']='required';
		$rules['ORDER_ID']='required';
		$rules['CUST_ID']='required';
		$rules['TXN_AMOUNT']='required';
        $rules['address_id']='required|numeric';
		$rules['payment_type']='required|numeric|in:1,2';
		
		
		$validator = Validator::make($request->json()->all(), $rules);
		
		if ($validator->fails()){
			
			$message = "";
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('message'=>$message),403);
			
			
		}else{
			
			try{
			
			    
				$totalSales =  \App\Models\Sales::count();

				$sales=new \App\Models\Sales();
				
				$orderId=date("Y").str_pad(date("m"), 2, "0", STR_PAD_LEFT).str_pad(date("d"), 2, "0", STR_PAD_LEFT)."-FFP-".rand(11,99).str_pad(($totalSales+1), 3, "0", STR_PAD_LEFT);

				$sales->order_id=$orderId;
	
				$addressResult=\App\Models\Addressbook::where('id',$request->json()->get('address_id'))->first();

				$sales->user_id=$request->user()->id; 
				$sales->checkout_type='2';
				$sales->name=$addressResult->name;
				$sales->email=$addressResult->email;
				$sales->mobile=$addressResult->mobile;
				$sales->city_id=$addressResult->city;
				$sales->address_line1=$addressResult->address_line1;
				$sales->address_line2=$addressResult->address_line2;
				$sales->pincode=$addressResult->pincode;

				$cartData=\App\Models\Addtocart::where('user_id',$request->user()->id)->get();

				

				$sales->payment_type=$request->json()->get('payment_type');

				$subTotal=0; $totalShipping=0; $discount=0; $couponId=0; $couponAmount=0; $netAmount=0;

				if(!empty($cartData)){
					
					foreach($cartData as $cart){
						
						$productResult= \App\Models\Product::where('id',$cart['product_id'])->first();

						$offerPrice=\App\Helpers\commonHelper::getOfferProductPrice($productResult->sale_price,$productResult->discount_type,$productResult->discount_amount);
 
						$shippingAmount='0';

						$subTotal+=($productResult['sale_price']*$cart['qty']);

						$totalShipping+=($shippingAmount*$cart['qty']);

						$discount+=$offerPrice*$cart['qty'];
					}

					$netAmount=($subTotal)-$discount;

				}

				//calculation of coupon code amount

				if($request->json()->get('coupon_id')){

					$amountForCoupon=($subTotal-$discount);

					//send OTP on mail
					$couponData=\App\Models\Coupon::where('id',$request->json()->get('coupon_id'))->first();
					$couponResult=\App\Helpers\commonHelper::checkCouponCode($request->user()->id,$couponData['coupon_code'],$amountForCoupon);

					if($couponResult['status']==200){
				
						if($couponResult['discount_type']=='1'){

							$couponAmount=round((($amountForCoupon*$couponResult['discount_amount'])/100),2);
				
						}else if($couponResult['discount_type']=='2'){
				
							$couponAmount=round($couponResult['discount_amount'],2);
				
						}
						
						$couponId=$couponResult['coupon_id'];
						$netAmount-=$couponAmount;

					}else{

						return response(array("message" => $couponResult['message']),$couponResult['status']); 

					}
				}
				

				$sales->subtotal= $subTotal;
				$sales->shipping=$totalShipping;
				$sales->couponcode_id=$couponId;
				$sales->couponcode_amount=$couponAmount;
				$sales->discount=$discount;
				$sales->net_amount=$netAmount;
				
				$sales->save();

				$saleId=$sales->id;

				if(!empty($cartData)){

					foreach($cartData as $cart){

						$salesDetail=new \App\Models\Sales_detail();

						$productResult=\App\Models\Product::where('id',$cart['product_id'])->first();
						
						$imagesArray=explode(',',$productResult->images);

						$offerPrice=\App\Helpers\commonHelper::getOfferProductPrice($productResult->sale_price,$productResult->discount_type,$productResult->discount_amount);

						if($request->json()->get('type')=='2'){
							$salesDetail->user_id=$request->user()->id; 
						}
						
						$salesDetail->user_id=$request->user()->id; 
						$salesDetail->sale_id=$saleId;
						$salesDetail->order_id=$orderId;
						$salesDetail->product_id=$productResult->id;
						$salesDetail->product_name=$productResult->name;
						$salesDetail->product_image=asset('uploads/products/'.$imagesArray[0]);
						$salesDetail->qty=$cart['qty'];
						$salesDetail->remark=$cart['remark'];
						$salesDetail->sub_total=($productResult->sale_price*$cart['qty']);
						$salesDetail->discount=$offerPrice*$cart['qty'];
						$salesDetail->amount=(($productResult->sale_price)-$offerPrice)*$cart['qty'];
						$salesDetail->order_status='1';
						$salesDetail->payment_status='1';

						$salesDetail->save();

					}
				}
				
				$paytmParams = array();

                $paytmParams["MID"] = $request->json()->get('MID');
                $paytmParams["ORDERID"] = $request->json()->get('ORDER_ID');
                $paytmParams["CUSTID"] = $request->json()->get('CUST_ID');
                $paytmParams["TXNAMOUNT"] = $request->json()->get('TXN_AMOUNT');
				
				$paytmChecksum = PaytmChecksum::generateSignature($paytmParams, $request->json()->get('MID'));
	
                $transactionId=strtotime("now").rand(11,99);

				$payment=new \App\Models\Transaction();

				$payment->user_id=$request->user()->id;
				$payment->razorpay_paymentid=$request->json()->get('CUST_ID');
				$payment->order_id=$orderId;
				$payment->razorpay_order_id=$request->json()->get('ORDER_ID');
				$payment->amount=$request->json()->get('TXN_AMOUNT');;
				$payment->transaction_id=$transactionId;
				
				$payment->payment_status='0';
				$payment->save();
				
				return response(array("message" => "Checkout successfully.","order_id"=>$orderId,"paytmChecksum"=>$paytmChecksum),200); 
				
			}catch (\Exception $e){
				
				return response(array("message" => "Something went wrong.please try again"),403); 
				
			}
		}
		
	}

	

	public function getAddressById(Request $request){

		$address=\App\Models\Addressbook::where('id',$request->json()->get('id'))->first();

		if(!$address){

			return response(array('error'=>true,'message'=>'Invalid Address id'),200);

		}else{

			$result=[
			    'error'=>false,
			    'message'=>'Address fetched successfully.',
				'id'=>$address->id,
				'name'=>$address->name,
				'mobile'=>$address->mobile,
				'email'=>$address->email,
				'address_line1'=>$address->address_line1,
				'address_line2'=>$address->address_line2,
				'city'=>(string) $address->city,
				'pincode'=>$address->pincode,
			];

			return response($result);
		}

	}

	public function logout(Request $request){

		$request->user()->token()->revoke();

		return response(array('message'=>'Logout successfully.'),200);
	}

	public function wishlistProduct(Request $request){

		$rules['product_id']='numeric|required|exists:products,id';

		$validator = Validator::make($request->json()->all(), $rules);
		
		if ($validator->fails()){
			
			$message = "";
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('error'=>true,'message'=>$message),403);
			
			
		}else{
			
			try{

				$result=\App\Models\Product::where('id',$request->json()->get('product_id'))->where('status','1')->first();

				if($result){

					$checkWishlist=\App\Models\Wishlist::where([
																['product_id',$request->json()->get('product_id')],
																['user_id',$request->user()->id],
																])->first();

					if($checkWishlist){

						\App\Models\Wishlist::where('id',$checkWishlist->id)->delete();

						$wishlistResult=\App\Models\Wishlist::select('product_id')->where('user_id',$request->user()->id)->pluck('product_id')->toArray();

						return response(array('error'=>false,"message" => "Product successfully removed from wishlist.","wishlistid"=>$wishlistResult),200);

					}else{	
						
						$wishlist=new \App\Models\Wishlist();
						$wishlist->user_id=$request->user()->id;
						$wishlist->product_id=$request->json()->get('product_id');
						$wishlist->save();

						$wishlistResult=\App\Models\Wishlist::select('product_id')->where('user_id',$request->user()->id)->pluck('product_id')->toArray();

						return response(array('error'=>false,"message" => "Product Wishlisted successfully.","wishlistid"=>$wishlistResult),200); 
					}

				}else{

					return response(array('error'=>true,"message" => "Product Not Found. Please try again"),403); 

				}
			}catch (\Exception $e){
				
				return response(array('error'=>true,"message" => "Something went wrong.please try again"),403); 
				
			}
		}

	}

	public function wishlistProductList(Request $request){

		try{
			
			$result=\App\Models\Wishlist::Select('wishlists.id as wishlistid','products.name','products.id','products.sale_price','products.discount_type','products.discount_amount','products.slug','products.images')
								->join('products','products.id','=','wishlists.product_id')
								->where([
									['products.status','=','1'],
									['products.status','=','1'],
									['wishlists.user_id','=',$request->user()->id]
								])->get();
		
		
			if($result->count()==0){
				
				return response(array('error'=>true,"message" => 'Products Not Found.'),404); 

			}else{
				
				$products=[];
				foreach($result as $value){
					
					$imagesArray=explode(',',$value->images);

					$products[]=[
						'wishlistid'=>$value['wishlistid'],
						'variant_productid'=>$value['id'],
						'name'=>ucfirst($value['name']),
						'sale_price'=>$value['sale_price'],
						'discount_amount'=>$value['discount_amount'],
						'offer_price'=>\App\Helpers\commonHelper::getOfferProductPrice($value['sale_price'],$value['discount_type'],$value['discount_amount']),
						'first_image'=>asset('uploads/products/'.$imagesArray[0]),
						'slug'=>$value['slug']
					];
				}
				
				return response(array('error'=>false,"message" => 'Wishlist Product fetched successfully.','result'=>$products),200); 
			}
		
		}catch (\Exception $e){
			
			return response(array('error'=>true,"message" => $e->getMessage()),403); 
		
		} 

	}

	public function deleteWishlistProduct(Request $request){

		$rules['wishlist_id']='numeric|required|exists:wishlists,id';

		$validator = Validator::make($request->json()->all(), $rules);
		
		if ($validator->fails()){
			
			$message = "";
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('error'=>true,'message'=>$message),200);
			
			
		}else{

			try{

				\App\Models\Wishlist::where('id',$request->json()->get('wishlist_id'))->delete();

				return response(array('error'=>false,'message'=>'Product Successfully Removed From Saved Products.'),200);
			
			}catch (\Exception $e){
			
				return response(array('error'=>true,"message" => $e->getMessage()),403); 
			
			} 
		}

	}


	public function failedOrder(Request $request){

		$rules['order_id']='required|exists:sales_details,order_id';

		$validator = Validator::make($request->json()->all(), $rules);
		
		if ($validator->fails()){
			
			$message = "";
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('message'=>$message),403);
			
			
		}else{
			
			try{

				\App\Models\Sales_detail::where('order_id',$request->json()->get('order_id'))->update(array('order_status'=>'0','payment_status'=>'1'));	
				\App\Models\Transaction::where('order_id',$request->json()->get('order_id'))->update(array('payment_status'=>'1'));				

				return response(array("message" => "Order Failed successfully."),200); 

				
			}catch (\Exception $e){
				
				return response(array("message" => "Something went wrong.please try again"),403); 
				
			}
		}

	}


	public function checkCouponCode(Request $request){

		$rules['coupon_code']='required|exists:coupons,coupon_code';
		$rules['order_amount']='required|numeric';

		$validator = Validator::make($request->json()->all(), $rules);
		
		if ($validator->fails()){
			
			$message = "";
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('message'=>$message),403);
			
		}else{

			$couponResult=\App\Helpers\commonHelper::checkCouponCode($request->user()->id,$request->json()->get('coupon_code'),$request->json()->get('order_amount'));
		
			if($couponResult['status']==200){

				return response(array('message'=>$couponResult['message'],'coupon_id'=>$couponResult['coupon_id'],'discount_type'=>$couponResult['discount_type'],'discount_amount'=>$couponResult['discount_amount']),$couponResult['status']);

			}else{

				return response(array('message'=>$couponResult['message']),$couponResult['status']);
			}
		}
	}
	
	
	
	public function couponList(Request $request){

		$result=\App\Models\Sales::where('user_id',$request->user()->id)->get();

		if(count($result)>0){
		    
		    
            $result=\App\Models\Coupon::where([
                            			['status','=','1'],
                            			['start_date','<=',date('Y-m-d')],
                            			['end_date','>=',date('Y-m-d')]
                            			])->get();
            
			return response(array('message'=>'Coupon Code list.','result'=>$result));

		}else{
		    
		    $result=\App\Models\Coupon::where('coupon_code','NEWUSER')->get();

			return response(array('message'=>'Coupon code for new user.','result'=>$result));
			
		}

	}
	
	
	
	public function paytmChecksum(Request $request){
		
		$rules['MID'] = 'required';
		$rules['ORDERID'] = 'required';
		
		$validator = Validator::make($request->json()->all(), $rules);
		
		if ($validator->fails()) {
			$message = [];
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array("error"=>true,'message'=>$message),200);
			
		}else{

			try{
			    
			    $paytmParams = array();

                $paytmParams["MID"] = $request->json()->get('MID');
                $paytmParams["ORDERID"] = $request->json()->get('ORDERID');
				
				$paytmChecksum = PaytmChecksum::generateSignature($paytmParams, 'YOUR_MERCHANT_KEY');
	
                $transactionId=strtotime("now").rand(11,99);

				$payment=new \App\Models\Transaction();

				$payment->user_id=$request->user()->id;
				$payment->order_id=$request->json()->get('ORDERID');
				$payment->payment_by='1';
				$payment->transaction_id=$transactionId;
				$payment->payment_status='0';
				$payment->save();
					
				return response(array('message'=>"generateSignature Returns success.","error"=>false,"paytmChecksum"=>$paytmChecksum),200);
				
			}catch (\Exception $e){
				
				return response(array("error"=>true,"message" => $e->getMessage()),200); 
			
			}
		}
	}

	

}