<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\commonHelper;

use DB; 
use Validator;
use Razorpay\Api\Api;
use Hash;
use Srmklive\PayPal\Facades\Paypal;
use Srmklive\PayPal\Services\ExpressCheckout;

class PreLoginController extends Controller
{

   	
	public function sendOtp(Request $request){
		
		$rules = [
            'mobile' => 'required|numeric', 
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
				
				$Result=\App\Models\Otp::where([
										['mobile','=',$request->json()->get('mobile')]
										])->first();

				$otp=mt_rand(1000,9999);
				//$otp='1111';
				if($Result){
					
					\App\Models\Otp::where([
						['mobile','=',$request->json()->get('mobile')]
						])->update(['otp'=>$otp]);
				
				}else{
					
					\App\Models\Otp::insert(['mobile'=>$request->json()->get('mobile'),'otp'=>$otp]);
													
				}
				$msg =$otp;
				
				$smsApiResponce =  \App\Helpers\commonHelper::sendMsg($msg,$request->json()->get('mobile'));	
				//print_r($smsApiResponce); die;
				if($smsApiResponce['status']=="success"){

					return response(array('error'=>false,'message'=>'OTP sent successfully on your registered Mobile no.'),200);
				
				}else{

					return response(array('error'=>false,'message'=>'Something went wrong ! Please try again'),200);
				}
				// return response(array('message'=>'OTP sent successfully on your registered Mobile no.'),200);

				
			}catch (\Exception $e){
				
				return response(array('error'=>true,"message" => $e->getMessage()),403); 
			
			}
		}
		
	}
	
	
	 
    public function socialLogin(Request $request){
		
		$rules['name'] = 'required';
		$rules['email'] = 'required|email';
		
		$validator = Validator::make($request->json()->all(), $rules);
		
		if ($validator->fails()) {
			$message = [];
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('message'=>$message),403);
			
		}else{

			try{
				
				$userResult=\App\Models\User::where([
													['reg_type','=','google'],
													['email','=',$request->json()->get('email')]
													])->first();

				if(!$userResult){

					$userResult=new \App\Models\User();
					$userResult->name=$request->json()->get('name');
					$userResult->email=$request->json()->get('email');
					$userResult->reg_type='google';
					$userResult->user_type="2";
					$userResult->designation_id="0";
					$userResult->save();

				}
					
				return response(array('message'=>"Login successfully.","verify"=>true,"token"=>$userResult->createToken('authToken')->accessToken),200);
				
			}catch (\Exception $e){
				
				return response(array("message" => $e->getMessage()),403); 
			
			}
		}
	}
	
	public function verifyOtp(Request $request){
		
		$rules['mobile'] = 'required';
		$rules['otp'] = 'required|size:4';
		
		$validator = Validator::make($request->json()->all(), $rules);
		
		if ($validator->fails()) {
			$message = [];
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('error'=>true,'message'=>$message),403);
		}else{

			try{
				$userOtp=\App\Models\Otp::where([
										['mobile','=',$request->json()->get('mobile')],
										['otp','=',$request->json()->get('otp')],
										])->first();
				
				if(!$userOtp){
					
					return response(array('error'=>true,'message'=>"OTP doesn't exist. Please try again."),403);

				}else{

					\App\Models\Otp::where([
								['mobile','=',$request->json()->get('mobile')],
								])->delete();

					return response(array('error'=>false,"otpVerify"=>true,'message'=>"OTP matched successfully."),200);
					
				}
				
			}catch (\Exception $e){
				
				return response(array('error'=>true,"message" => $e->getMessage()),403); 
			
			}
		}
	}
	 
    public function LoginAndRegister(Request $request){
		
		$rules['mobile'] = 'required|numeric';
		
		$validator = Validator::make($request->json()->all(), $rules);
		
		if ($validator->fails()) {
			$message = [];
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('error'=>true,'message'=>$message),403);
		}else{

			try{
				
				$userResult=\App\Models\User::where([
										['mobile','=',$request->json()->get('mobile')],
										['user_type','=','2']
										])->first();

				
				if(!$userResult){
					
					$userResult = new \App\Models\User();
					$userResult->mobile=$request->json()->get('mobile');
					$userResult->reg_type='mobile';
					$userResult->status='1';
					$userResult->save();	
				
					return response(array('error'=>false,'message'=>"Login successfully.","token"=>$userResult->createToken('authToken')->accessToken),200);

				}else{
					
					$userResult->status='1';
					$userResult->save();	
				
					return response(array('error'=>false,'message'=>"Login successfully.","token"=>$userResult->createToken('authToken')->accessToken),200);
						
				}

			}catch (\Exception $e){
				
				return response(array('error'=>true,"message" => $e->getMessage()),403); 
			
			}
		}
	}
	
	public function categoryList(Request $request){

		try{

			$result=\App\Models\Category::where('status','1')->where('parent_id',null)->orderBy('short_order','ASC')->get();
			
			if(!empty($result)){
				
				$data = [];
				foreach($result as $value){
					$data[] = [
						'id'=>$value->id,
						'name'=>$value->name,
						'slug'=>$value->slug,
						'image'=>asset('uploads/category/'.$value->image),
						'description'=>$value->description,
						'meta_title'=>$value->meta_title,
						'meta_keywords'=>$value->meta_keywords,
						'meta_description'=>$value->meta_description,
					];
				}

				return response(array('error'=>false,"message" => 'Category fetched successfully.','result'=>$data),200); 
			}else{
				
				return response(array('error'=>true,"message" => 'Category not found.'),200); 
			}

		}catch (\Exception $e){
			
			return response(array('error'=>true,"message" => $e->getMessage()),200); 
		
		}
	}
	
	
	public function brandList(Request $request){

		try{

			$result=\App\Models\Brand::where('status','1')->where('home','1')->orderBy('short_order','ASC')->get();
			
			if(!empty($result)){
				
				$data = [];
				foreach($result as $value){
					$data[] = [
						'id'=>$value->id,
						'name'=>$value->name,
						'image'=>asset('uploads/brand/'.$value->image),
						'description'=>$value->description,
					];
				}

				return response(array('error'=>false,"message" => 'Brand fetched successfully.','result'=>$data),200); 
			}else{
				
				return response(array('error'=>true,"message" => 'Brand not found.'),200); 
			}

		}catch (\Exception $e){
			
			return response(array('error'=>true,"message" => $e->getMessage()),200); 
		
		}
	}
	
	
	public function SubCateList(Request $request){
	    
		$rules = [
            'cate_id' => 'required|numeric', 
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
    			
    			
    			$productResult=\App\Models\Product::where('category_id',$request->json()->get('cate_id'))->orderBy('id','DESC')->where('status','1')->get();
    			
                
    			$result=\App\Models\Category::where('status','1')->where('parent_id',$request->json()->get('cate_id'))->orderBy('short_order','ASC')->get();
			
    			if(!empty($result)){
    				
    				$data = [];
    				foreach($result as $value){
    					$data[] = [
    						'id'=>$value->id,
    						'name'=>$value->name,
    						'image'=>asset('uploads/category/'.$value->image),
    						'description'=>$value->description,
    						'meta_title'=>$value->meta_title,
    						'meta_keywords'=>$value->meta_keywords,
    						'meta_description'=>$value->meta_description,
    					];
    				}
    
    				return response(array('error'=>false,"message" => 'Category fetched successfully.','result'=>$data),200); 
    			}else{
    				
    				return response(array('error'=>true,"message" => 'Category not found.'),200); 
    			}
    			
    		}catch (\Exception $e){
    			
    			return response(array("message" => $e->getMessage()),403); 
    		
    		}
		}
		
	}
	
	
	public function allProduactList(Request $request){
	    
		$offset=$_GET['offset'];
		$limit=$_GET['limit'];
		
		try{
			
			$orderCol='variantproducts.id';
			$orderBy='DESC';

			if($request->json()->get('sort_order')=='0'){

				$orderCol='variantproducts.id';
				$orderBy='DESC';

			}elseif($request->json()->get('sort_order')=='1'){

				$orderCol='sale_price';
				$orderBy='ASC';

			}else if($request->json()->get('sort_order')=='2'){

				$orderCol='sale_price';
				$orderBy='DESC';

			}else if($request->json()->get('sort_order')=='3'){

				$orderCol='variantproducts.id';
				$orderBy='ASC';

			}else if($request->json()->get('sort_order')=='4'){

				$orderCol='variantproducts.id';
				$orderBy='DESC';

			}else if($request->json()->get('sort_order')=='5'){

				$orderCol='products.name';
				$orderBy='ASC';

			}else if($request->json()->get('sort_order')=='6'){

				$orderCol='products.name';
				$orderBy='DESC';
				
			}

			$minPrice=0;
			$maxPrice=1000000000000000000000000000000000000000000000000000;

			if($request->json()->get('price_id')=='1'){

				$minPrice=0;
				$maxPrice=1000;

			}else if($request->json()->get('price_id')=='2'){

				$minPrice=1001;
				$maxPrice=5000;

			}else if($request->json()->get('price_id')=='3'){

				$minPrice=5001;

			}

			$query=\App\Models\Product::where('status','1')->orderBy('id','DESC');
			
			
			if(isset($_GET['category']) && $_GET['category'] != '' && $_GET['category'] != 'undefined'){
				$query->where('category_id',\App\Helpers\commonHelper::getCategoryIdBySlug($_GET['category']))->orwhere('category_parent_id',\App\Helpers\commonHelper::getCategoryIdBySlug($_GET['category']));
			}
			
			$productResult = $query->offset($offset)->limit($limit)->get();
			
			$totalProduct = $query->count();
			
            
			if(!$productResult){
				
				return response(array("message" => 'Product not found.'),200); 

			}else{
				
				$result=[];
				
				foreach($productResult as $value){
					
					$imagesArray=explode(',',$value->images);
					
					$secondImage=Null;
					if(isset($imagesArray[1])){
						$secondImage=asset('uploads/products/'.$imagesArray[1]);
					}
					
					$result[]=[
						'id'=>$value->id,
						'products_qty'=>\App\Helpers\commonHelper::getVaraintNameById($value['variant_attribute_id']),
						'name'=>ucfirst($value['name']),
						'sale_price'=>number_format($value['sale_price'],2),
						'offer_price'=> number_format($value['sale_price'] - (\App\Helpers\commonHelper::getOfferProductPrice($value['sale_price'],$value['discount_type'],$value['discount_amount'])),2),
						'discount_type'=>$value['discount_type'],
						'discount_amount'=>(int) number_format($value['discount_amount'],2),
						'first_image'=>asset('uploads/products/'.$imagesArray[0]),
						'second_image'=>$secondImage,
						'slug'=>$value['slug']
					];
				}

				return response(array("message" => 'Product fetched successfully.','result'=>$result,'total'=>$totalProduct),200); 
				
			}
			
		}catch (\Exception $e){
			
			return response(array("message" => $e->getMessage()),200); 
		
		}
		
		
	}
	
	public function produactList(Request $request){
	    
		$rules = [
            'cate_id' => 'required|numeric', 
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
    			
    			
    			$productResult=\App\Models\Product::where('category_id',$request->json()->get('cate_id'))->orderBy('id','DESC')->where('status','1')->get();
    			
                
    			if(!$productResult){
    				
    				return response(array("message" => 'Product not found.'),403); 
    
    			}else{
    				
    				$result=[];
    				
    				foreach($productResult as $value){
    					
    					$imagesArray=explode(',',$value->images);
    					
    					$secondImage=Null;
    					if(isset($imagesArray[1])){
    						$secondImage=asset('uploads/products/'.$imagesArray[1]);
    					}
    					
    					$result[]=[
    						'id'=>$value->id,
    						'userQty'=>\App\Helpers\commonHelper::GetAddToCartQtyByUserId($value->id,$request->user()->id),
    						'products_qty'=>\App\Helpers\commonHelper::getVaraintNameById($value['variant_attribute_id']),
    						'name'=>ucfirst($value['name']),
    						'sale_price'=>number_format($value['sale_price'],2),
						    'offer_price'=> number_format($value['sale_price'] - (\App\Helpers\commonHelper::getOfferProductPrice($value['sale_price'],$value['discount_type'],$value['discount_amount'])),2),
						    'discount_type'=>$value['discount_type'],
						    'discount_amount'=>(int) number_format($value['discount_amount'],2),
    						'first_image'=>asset('uploads/products/'.$imagesArray[0]),
    						'second_image'=>$secondImage,
    						'slug'=>$value['slug']
    					];
    				}
    
    				return response(array("message" => 'Product fetched successfully.','result'=>$result),200); 
    				
    			}
    			
    		}catch (\Exception $e){
    			
    			return response(array("message" => $e->getMessage()),403); 
    		
    		}
		}
		
	}
	
	
	
	public function produactListByBrand(Request $request){
	    
		$rules = [
            'brand_id' => 'required|numeric', 
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
    			
    			
    			$productResult=\App\Models\Product::where('brand_id',$request->json()->get('brand_id'))->orderBy('id','DESC')->where('status','1')->get();
    			
                
    			if(!$productResult){
    				
    				return response(array("message" => 'Product not found.'),403); 
    
    			}else{
    				
    				$result=[];
    				
    				foreach($productResult as $value){
    					
    					$imagesArray=explode(',',$value->images);
    					
    					$secondImage=Null;
    					if(isset($imagesArray[1])){
    						$secondImage=asset('uploads/products/'.$imagesArray[1]);
    					}
    					
    					$result[]=[
    						'id'=>$value->id,
    						'userQty'=>\App\Helpers\commonHelper::GetAddToCartQtyByUserId($value->id,$request->user()->id),
    						'products_qty'=>\App\Helpers\commonHelper::getVaraintNameById($value['variant_attribute_id']),
    						'name'=>ucfirst($value['name']),
    						'sale_price'=>number_format($value['sale_price'],2),
						    'offer_price'=> number_format($value['sale_price'] - (\App\Helpers\commonHelper::getOfferProductPrice($value['sale_price'],$value['discount_type'],$value['discount_amount'])),2),
						    'discount_type'=>$value['discount_type'],
						    'discount_amount'=>(int) number_format($value['discount_amount'],2),
    						'first_image'=>asset('uploads/products/'.$imagesArray[0]),
    						'second_image'=>$secondImage,
    						'slug'=>$value['slug']
    					];
    				}
    
    				return response(array("message" => 'Product fetched successfully.','result'=>$result),200); 
    				
    			}
    			
    		}catch (\Exception $e){
    			
    			return response(array("message" => $e->getMessage()),403); 
    		
    		}
		}
		
	}
	
	public function dealsOfTheWeekProductList(Request $request){

		try{
			
			
			$productResult=\App\Models\Product::where('deals_oftheweek','1')->orderBy('id','DESC')->where('status','1')->get();
    			
                
			if(!$productResult){
				
				return response(array("message" => 'Product not found.'),403); 

			}else{
				
				$result=[];
				
				foreach($productResult as $value){
					
					$imagesArray=explode(',',$value->images);
					
					$secondImage=Null;
					if(isset($imagesArray[1])){
						$secondImage=asset('uploads/products/'.$imagesArray[1]);
					}
					
					$result[]=[
						'id'=>$value->id,
						'products_qty'=>\App\Helpers\commonHelper::getVaraintNameById($value['variant_attribute_id']),
						'name'=>ucfirst($value['name']),
						'sale_price'=>number_format($value['sale_price'],2),
						'offer_price'=> number_format($value['sale_price'] - (\App\Helpers\commonHelper::getOfferProductPrice($value['sale_price'],$value['discount_type'],$value['discount_amount'])),2),
						'discount_type'=>$value['discount_type'],
						'discount_amount'=>(int) number_format($value['discount_amount'],2),
						'first_image'=>asset('uploads/products/'.$imagesArray[0]),
						'second_image'=>$secondImage,
						'slug'=>$value['slug']
					];
				}

				return response(array("message" => 'Product fetched successfully.','result'=>$result),200); 
				
			}
			
		}catch (\Exception $e){
			
			return response(array("message" => $e->getMessage()),403); 
		
		}
		
	}

	public function productDetail(Request $request){
	    
		try{
			
			$slug = $_GET['slug'];
			
			$productResult=\App\Models\Product::where('slug',$slug)->where('status','1')->first();
			
			
			if(!$productResult){
				
				return response(array('error'=>true,"message" => 'Product not found.'),403); 

			}else{
					
				$imagesArray=explode(',',$productResult->images);
				
				
				$result=[
					'error'=>false,
					"message" => 'Product fetched successfully.',
					'id'=>$productResult->id,
					'products_unit'=>\App\Helpers\commonHelper::getVaraintNameById($productResult['variant_attribute_id']),
					'name'=>ucfirst($productResult['name']),
					'sale_price'=>number_format($productResult['sale_price'],2),
					'offer_price'=> number_format($productResult['sale_price'] - (\App\Helpers\commonHelper::getOfferProductPrice($productResult['sale_price'],$productResult['discount_type'],$productResult['discount_amount'])),2),
					'discount_type'=>$productResult['discount_type'],
					'discount_amount'=>(int) number_format($productResult['discount_amount'],2),
					'slug'=>$productResult['slug'],
					'stock'=>$productResult->stock,
					'short_description'=>$productResult->short_description,
					'description'=>$productResult->description,
					'meta_title'=>$productResult->meta_title,
					'meta_keywords'=>$productResult->meta_keywords,
					'meta_description'=>$productResult->meta_description,
					'image' => \App\Helpers\commonHelper::GetProductAllImages($imagesArray),
					'first_image'=>asset('uploads/products/'.$imagesArray[0]),
				];
				

				return response($result); 
				
			}
			
		}catch (\Exception $e){
			
			return response(array('error'=>true,"message" => $e->getMessage()),403); 
		
		}
		
	}
		
	
	public function searchProduct(Request $request){
 
		$text='';
		
		if(isset($_GET['text']) && $_GET['text']!=''){
			
			$text=$_GET['text'];
		}
		
		try{
				
			$query=\App\Models\Product::Select('products.name','variantproducts.slug')->where([
																	['products.status','=','1'],
																	['products.recyclebin_status','=','0'],
																	['variantproducts.status','=','1'],
																	['variantproducts.slug','!=',''],
																	['products.name','like','%'.$text.'%']
																	])->join('variantproducts','variantproducts.product_id','=','products.id')->groupBy('variantproducts.product_id')->limit(10);
			

			if($_GET['category_id']!='all'){

				$categoryIdResult=\App\Helpers\commonHelper::getAllCategoryTreeidsArray($_GET['category_id']);
				$categoryIdResult[]='15';

				$query->whereIn('products.category_id',$categoryIdResult);
			}

			$productResult=$query->get();

			if($productResult->count()==0){
				
				return response(array("message" => 'Product not found.'),404); 
			}else{
				
				$result=[];
				
				foreach($productResult as $product){
					
					$result[]=[
						'name'=>ucfirst($product->name),
						'slug'=>$product->slug
					];
				}
				return response(array("message" => 'Product data fetched successfully.','result'=>$result),200); 
				
			}
			
		}catch (\Exception $e){
			
			return response(array("message" => $e->getMessage()),403); 
		
		} 	 
		
	}
	
	
	public function sliderList(Request $request){
 
		try{
				
			$sliderResult=\App\Models\Slider::where([
													['status','=','1'],
													['recyclebin_status','=','0'],
													])->orderBy('sort_order','ASC')->get();
			
			if(!$sliderResult){
				
				return response(array('error'=>true,"message" => 'Result not found.'),404); 
			}else{
				
				$result=[];
				
				foreach($sliderResult as $slider){
					
					$result[]=[
						'image'=>asset('uploads/sliders/'.$slider->image),
						'cate_id'=>$slider->cate_id
					];
				}
				return response(array('error'=>false,"message" => 'Slider fetched successfully.','result'=>$result),200); 
				
			}
			
		}catch (\Exception $e){
			
			return response(array('error'=>true,"message" => $e->getMessage()),403); 
		
		} 	 
		
	}
	
	
	public function sliderCategoryProduactList(Request $request){
	    
		$rules = [
            'cate_id' => 'required|numeric', 
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
    			
    			
    			$subCateResult=\App\Models\Category::where('id',$request->json()->get('cate_id'))->orWhere('parent_id',$request->json()->get('cate_id'))->get();
        		$productResult=[];
        		
        		if($subCateResult){
        		    
        		    
        		   foreach($subCateResult as $cate){
        		     
        		     $productResult[]=\App\Models\Product::where('category_id',$cate->id)->orderBy('id','DESC')->where('status','1')->get(); 
        		     
        		   }
        		   
        		   
        		}else{
        		    
        		    $productResult[]=\App\Models\Product::where('category_id',$request->json()->get('cate_id'))->orderBy('id','DESC')->where('status','1')->get();
        		}
        			
        		
                    
        			if(!$productResult){
        				
        				return response(array('error'=>true,"message" => 'Product not found.'),200); 
        
        			}else{
        				
        				$result=[];
        				
        				
        					
        				foreach($productResult as $valueData){
        					
        					
        					foreach($valueData as $value){
            					$imagesArray=explode(',',$value['images']);
            					
            					$secondImage=Null;
            					if(isset($imagesArray[1])){
            						$secondImage=asset('uploads/products/'.$imagesArray[1]);
            					}
            					
            					$result[]=[
            						'id'=>$value->id,
            						'userQty'=>\App\Helpers\commonHelper::GetAddToCartQtyByUserId($value->id,$request->user()->id),
            						'products_qty'=>\App\Helpers\commonHelper::getVaraintNameById($value['variant_attribute_id']),
            						'name'=>ucfirst($value['name']),
            						'sale_price'=>number_format($value['sale_price'],2),
        						    'offer_price'=> number_format($value['sale_price'] - (\App\Helpers\commonHelper::getOfferProductPrice($value['sale_price'],$value['discount_type'],$value['discount_amount'])),2),
        						    'discount_type'=>$value['discount_type'],
        						    'discount_amount'=>(int) number_format($value['discount_amount'],2),
            						'first_image'=>asset('uploads/products/'.$imagesArray[0]),
            						'second_image'=>$secondImage,
            						'slug'=>$value['slug']
            					];
            				}
            			}
    				return response(array('error'=>false,"message" => 'Product fetched successfully.','result'=>$result),200); 
    				
    			}
    			
    		}catch (\Exception $e){
    			
    			return response(array('error'=>true,"message" => $e->getMessage()),200); 
    		
    		}
		}
		
	}
	
	
	public function countryList(Request $request){
 
		try{
				
			$countryResult=\App\Models\Country::orderBy('name','ASC')->get();
			
			if($countryResult->count()==0){
				
				return response(array("message" => 'Result not found.'),404); 
			}else{
				
				$result=[];
				
				foreach($countryResult as $country){
					
					$result[]=[
						'id'=>$country->id,
						'name'=>ucfirst($country->name)
					];
				}
				
				return response(array("message" => 'Country fetched successfully.','result'=>$result),200); 
				
			}
			
		}catch (\Exception $e){
			
			return response(array("message" => $e->getMessage()),403); 
		
		} 	 
		
	}
	
	public function stateList(Request $request){

		$rules = [ 
			'country_id'=>'required|exists:countries,id',
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
					
				$stateResult=\App\Models\State::where('country_id',$request->json()->get('country_id'))->get();
				
				if($stateResult->count()==0){
					
					return response(array('error'=>true,"message" => 'Result not found.'),200); 
				}else{
					
					$result=[];
					
					foreach($stateResult as $state){
						
						$result[]=[
							'id'=>$state->id,
							'name'=>ucfirst($state->name)
						];
					}
					
					return response(array('error'=>false,"message" => 'State fetched successfully.','result'=>$result),200); 
					
				}
				
			}catch (\Exception $e){
				
				return response(array('error'=>true,"message" => $e->getMessage()),200); 
			
			} 
		}	 
		
	}
	
	public function cityList(Request $request){

		$rules = [ 
			'state_id'=>'required|exists:cities,id',
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
					
				$cityResult=\App\Models\City::where('state_id',$request->json()->get('state_id'))->get();
				
				if($cityResult->count()==0){
					
					return response(array('error'=>true,"message" => 'Result not found.'),404); 
				}else{
					
					$result=[];
					
					foreach($cityResult as $city){
						
						$result[]=[
							'id'=>$city->id,
							'name'=>ucfirst($city->name)
						];
					}
					
					return response(array('error'=>false,"message" => 'City fetched successfully.','result'=>$result),200); 
					
				}
				
			}catch (\Exception $e){
				
				return response(array('error'=>true,"message" => $e->getMessage()),403); 
			
			}
		}	 
		
	}
	
	
	public function Information(Request $request, $id){
 
		try{
				
			$informationResult=\App\Models\Information::where('id',$id)->first();
			
			if(!$informationResult){
				
				return "Result not found"; 

			}else{

				return $informationResult->description;
			}
			
		}catch (\Exception $e){
			
			return response(array("message" => $e->getMessage()),403); 
		
		} 	 
		
	}
	
	public function latestOrders(Request $request){
		
		try{
			
			$result=\App\Models\Sales::Select('products.name','variantproducts.id','variantproducts.sale_price','variantproducts.discount_type','variantproducts.discount_amount','variantproducts.slug','variantproducts.images')
								->join('sales_details','sales.id','=','sales_details.sale_id')
								->join('variantproducts','variantproducts.id','=','sales_details.product_id')
								->join('products','products.id','=','variantproducts.product_id')->limit(4)->get();
		
		
			if($result->count()==0){
				
				return response(array("message" => 'Order Not found.'),404); 
			}else{
				
				$sales=[];
				foreach($result as $value){
					
					$imagesArray=explode(',',$value->images);
					
					$secondImage=Null;
					if(isset($imagesArray[1])){
						$secondImage=asset('uploads/products/'.$imagesArray[1]);
					}
					
					$sales[]=[
						'variant_productid'=>$value['id'],
						'name'=>ucfirst($value['name']),
						'sale_price'=>number_format($value['sale_price'],2),
						'discount_amount'=>round($value['discount_amount']),
						'offer_price'=>number_format(\App\Helpers\commonHelper::getOfferProductPrice($value['sale_price'],$value['discount_type'],$value['discount_amount']),2),
						'first_image'=>asset('uploads/products/'.$imagesArray[0]),
						'second_image'=>$secondImage,
						'slug'=>$value['slug']
					];
				}
				
				return response(array("message" => 'Recent Orders fetched successfully.','result'=>$sales),200); 
			}
		
		}catch (\Exception $e){
			
			return response(array("message" => $e->getMessage()),403); 
		
		} 
		
	}
	
	public function testimonialList(Request $request){
		
		try{
			
			$result=\App\Models\Testimonial::where('status','1')->where('recyclebin_status','0')->get();
		
		
			if($result->count()==0){
				
				return response(array('error'=>true,"message" => 'Testimonial Not found.'),200); 
			}else{
				
				$testimonial=[];
				foreach($result as $value){
					
					$testimonial[]=[
						'name'=>ucfirst($value['name']),
						'designation'=>ucfirst($value['name']),
						'description'=>$value['description'],
						'image'=>asset('uploads/testimonial/'.$value['image'])
					];
				}
				
				return response(array('error'=>false,"message" => 'Testimonials fetched successfully.','result'=>$testimonial),200); 
			}
		
		}catch (\Exception $e){
			
			return response(array('error'=>true,"message" => $e->getMessage()),200); 
		
		} 
		
	}
	
	public function topCategory(Request $request){
		
		try{
			
			$result=\App\Models\Category::where([
												['top_category','=','1'],
												['status','=','1'],
												])->get();
		
		
			if($result->count()==0){
				
				return response(array('error'=>true,"message" => 'Top Categoty Not found.'),404); 
			}else{
				
				$category=[];
				foreach($result as $value){
					
					$category[]=[
						'name'=>ucfirst($value['name']),
						'id'=>$value->id,
						'image'=>asset('uploads/category/'.$value->banner_image),
						'description'=>$value->description,
						'meta_title'=>$value->meta_title,
						'meta_keywords'=>$value->meta_keywords,
						'meta_description'=>$value->meta_description,
					];
				}
				
				return response(array('error'=>false,"message" => 'Category fetched successfully.','result'=>$category),200); 
			}
		
		}catch (\Exception $e){
			
			return response(array('error'=>true,"message" => $e->getMessage()),403); 
		
		} 
		
	}
	
	public function mustTryCategory(Request $request){
		
		try{
			
			$result=\App\Models\Category::where([
												['must_try_status','=','1'],
												['status','=','1'],
												])->get();
		
		
			if($result->count()==0){
				
				return response(array('error'=>true,"message" => 'Categoty Not found.'),404); 
			}else{
				
				$category=[];
				foreach($result as $value){
					
					$category[]=[
						'name'=>ucfirst($value['name']),
						'id'=>$value->id,
						'slug'=>$value->slug,
						'image'=>asset('uploads/category/'.$value->banner_image),
						'description'=>$value->description,
						'meta_title'=>$value->meta_title,
						'meta_keywords'=>$value->meta_keywords,
						'meta_description'=>$value->meta_description,
					];
				}
				
				return response(array('error'=>false,"message" => 'Category fetched successfully.','result'=>$category),200); 
			}
		
		}catch (\Exception $e){
			
			return response(array('error'=>true,"message" => $e->getMessage()),403); 
		
		} 
		
	}
	
	
	public function topSellingProduct(Request $request){
		
		try{

			
			$productResult=\App\Models\Product::where('top_selling','1')->orderBy('id','DESC')->where('status','1')->get();
    			
                
			if(!$productResult){
				
				return response(array("message" => 'Product not found.'),403); 

			}else{
				
				$result=[];
				
				foreach($productResult as $value){
					
					$imagesArray=explode(',',$value->images);
					
					$secondImage=Null;
					if(isset($imagesArray[1])){
						$secondImage=asset('uploads/products/'.$imagesArray[1]);
					}
					
					$result[]=[
						'id'=>$value->id,
						'products_qty'=>\App\Helpers\commonHelper::getVaraintNameById($value['variant_attribute_id']),
						'name'=>ucfirst($value['name']),
						'sale_price'=>number_format($value['sale_price'],2),
						'offer_price'=> number_format($value['sale_price'] - (\App\Helpers\commonHelper::getOfferProductPrice($value['sale_price'],$value['discount_type'],$value['discount_amount'])),2),
						'discount_type'=>$value['discount_type'],
						'discount_amount'=>(int) number_format($value['discount_amount'],2),
						'first_image'=>asset('uploads/products/'.$imagesArray[0]),
						'second_image'=>$secondImage,
						'slug'=>$value['slug']
					];
				}

				return response(array("message" => 'Product fetched successfully.','result'=>$result),200); 
				
			}
		
		}catch (\Exception $e){
			
			return response(array("message" => $e->getMessage()),403); 
		
		} 
		
	}
	
	public function dealsOfTheDay(Request $request){
		
		try{

			
			$productResult=\App\Models\Product::where('deals_oftheday','1')->orderBy('id','DESC')->where('status','1')->get();
    			
                
			if(!$productResult){
				
				return response(array("message" => 'Product not found.'),403); 

			}else{
				
				$result=[];
				
				foreach($productResult as $value){
					
					$imagesArray=explode(',',$value->images);
					
					$secondImage=Null;
					if(isset($imagesArray[1])){
						$secondImage=asset('uploads/products/'.$imagesArray[1]);
					}
					
					$result[]=[
						'id'=>$value->id,
						'products_qty'=>\App\Helpers\commonHelper::getVaraintNameById($value['variant_attribute_id']),
						'name'=>ucfirst($value['name']),
						'sale_price'=>number_format($value['sale_price'],2),
						'offer_price'=> number_format($value['sale_price'] - (\App\Helpers\commonHelper::getOfferProductPrice($value['sale_price'],$value['discount_type'],$value['discount_amount'])),2),
						'discount_type'=>$value['discount_type'],
						'discount_amount'=>(int) number_format($value['discount_amount'],2),
						'first_image'=>asset('uploads/products/'.$imagesArray[0]),
						'second_image'=>$secondImage,
						'slug'=>$value['slug']
					];
				}

				return response(array("message" => 'Product fetched successfully.','result'=>$result),200); 
				
			}
		
		}catch (\Exception $e){
			
			return response(array("message" => $e->getMessage()),403); 
		
		} 
		
	}
	
	
	public function variantAttributeList(Request $request){

		$productVariants=array(); $productAttributes=array();

		if(isset($_GET['category_id']) && (int) $_GET['category_id']>0){ 

			$categoryIdResult=\App\Helpers\commonHelper::getAllCategoryTreeidsArray($_GET['category_id']);
			$categoryIdResult[]=$_GET['category_id'];
			
			$productVariants=\App\Models\Product::select('variantproducts.variant_id')->whereIn('products.category_id',$categoryIdResult)->join('variantproducts','variantproducts.product_id','=','products.id')->where('variantproducts.status','1')->where('variantproducts.recyclebin_status','0')->where('products.status','1')->where('products.recyclebin_status','0')->pluck('variantproducts.variant_id')->toArray();
			$productVariants=array_unique($productVariants);

			$productAttributes=\App\Models\Product::select('variantproducts.variant_attributes')->whereIn('products.category_id',$categoryIdResult)->join('variantproducts','variantproducts.product_id','=','products.id')->where('variantproducts.status','1')->where('variantproducts.recyclebin_status','0')->where('products.status','1')->where('products.recyclebin_status','0')->pluck('variantproducts.variant_attributes')->toArray();
			$productAttributes=array_unique($productAttributes);
		}

		if(!empty($productAttributes)){

			$query=\App\Models\Variant::where('status','1')->orderBy('sort_order','ASC');

			if(!empty($productVariants)){

				$query->whereIn('id',$productVariants);
			}

			$variant=$query->get();

		}

		if(empty($productAttributes)){

			return response(array('message'=>'Variants not found.'),404);

		}else if($variant->count()>0){

			$result=[];

			foreach($variant as $value){

				$attributeQuery=\App\Models\Variant_attribute::where('status','1')->where('variant_id',$value->id)->orderBy('sort_order','ASC');

				if(!empty($productAttributes)){

					$attributeQuery->whereIn('id',$productAttributes);
				}
		
				$atributeResult=$attributeQuery->get();

				$result[]=array(
					'id'=>$value->id,
					'name'=>ucfirst($value->name),
					'attributes'=>$atributeResult
				);
			}

			return response(array('message'=>'Variants attributes fetched successfully.','result'=>$result),200);

		}else{

			return response(array('message'=>'Variants not found.'),404);
		}
	}

	public function initiatePayment(Request $request){

		$rules['order_id']='required|exists:sales,order_id';

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

			$result=\App\Models\Sales::with('getsalesdetailchild')->where('order_id',$request->json()->get('order_id'))->first();

			if(!$result || empty($result['getsalesdetailchild'])){

				return response(array("message" => "Invalid Order id."),404); 

			}else{

				// if($result->currency_id==1){

					$salesDetail=\App\Models\Sales_detail::select('id')->where('sale_id',$result->id)->pluck('id')->toArray();

					$transactionId=strtotime("now").rand(11,99);

					$payment=new \App\Models\Transaction();

					$payment->user_id=$result->user_id;
					$payment->order_id=$result->order_id;
					$payment->payment_by='1';
					$payment->currency_id=$result->currency_id;
					$payment->transaction_id=$transactionId;
					$payment->amount=$result->net_amount;
					$payment->payment_status='0';
					$payment->save();

					//create order on razor pay payment gateway
		
					$api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));

					$currencyResult=\App\Models\Currency_value::where('id',$result['currency_id'])->first();

					$order = $api->order->create(array(
						'receipt' => $result['order_id'],
						'amount' => $result['net_amount'] * 100,
						'currency' => $currencyResult['currency_code']
						)
					);

					$address=$result['address_line1'];

					if($result['address_line2']){

						$address.=' '.$result['address_line2'];

					}

					$json=[];
					$json['razorpay_orderid']=$order['id'];
					$json['suborderid']=implode(',',$salesDetail);

					$json['userdata']=[
						'name'=>$result['name'],
						'email'=>$result['email'],
						'contact'=>$result['mobile'],
						'address'=>$address,
						'description'=>'Order Checkout'
					]; 

					$json['order_amount']=$result['net_amount'];

					$json['order_id']=$result['order_id'];

					$json['transaction_id']=$transactionId;

					$json['payment_gateway']='razorpay';

					$json['message']="Order Initiated successfully."; 

					return response($json,200); 

				// }else{

				// 	try{

				// 		$data = [];
				// 		$data['items']=[];
				// 		foreach($result['getsalesdetailchild'] as $salesdetail){

				// 			$data['items'][]=[
				// 				'name'=>$salesdetail['product_name'],
				// 				'price'=>$salesdetail['amount'],
				// 				'qty'=>(float) $salesdetail['qty']
				// 			];
				// 		}
				
				// 		$data['invoice_id'] = $result['order_id'];
				// 		$data['invoice_description'] = "Order #{$result['order_id']} Bill";
				// 		$data['return_url'] = route('success.payment');
				// 		$data['cancel_url'] = route('cancel.payment'); 
				// 		$data['subtotal'] = (float) $result['subtotal'];
				// 		$data['shipping'] = (float) $result['shipping']; 
				// 		$data['total'] = (float) $result['net_amount'];
				// 		$data['shipping_discount'] = (float) $result['couponcode_amount'] + (float) $result['discount'];
 
				// 		$currencyResult=\App\Models\Currency_value::where('id',$result['currency_id'])->first();

				// 		$provider = new ExpressCheckout;
				 
				// 		$response = $provider->setExpressCheckout($data);
				// 		$response = $provider->setCurrency($currencyResult['currency_code']); 
				// 		$response = $provider->setExpressCheckout($data, true);
						
				// 		$transactionId=strtotime("now").rand(11,99);

				// 		$payment=new \App\Models\Transaction();
				// 		$payment->user_id=$result->user_id;
				// 		$payment->order_id=$result->order_id;
				// 		$payment->payment_by='2';
				//		$payment->currency_id=$result->currency_id;
				// 		$payment->transaction_id=$transactionId;
				// 		$payment->amount=$result->net_amount;
				// 		$payment->paypal_token=$response['TOKEN'];
				// 		$payment->payment_status='0'; 
				// 		$payment->save();
						
				// 		$json['message']="Order Initiated successfully."; 

				// 		$json['payment_link']=$response['paypal_link'];

				// 		$json['payment_gateway']='paypal';

				// 		return response($json,200); 

				// 	}catch (\Exception $e){

				// 		return response(['message'=>'Something went wrong. Please try again.'],403); 

				// 	} 
					
				// }
				
			}
		}
	}


	public function updatePayment(){ 

		$console=new \App\Models\PaymentConsole();

		$console->value=file_get_contents('php://input');

		$console->save();

		$inputData = json_decode(file_get_contents('php://input'),true);

		if(!empty($inputData)){

			try{

				if(isset($inputData['payload']['refund'])){

					$transaction=\App\Models\Transaction::where('transaction_id',$inputData['payload']['refund']['entity']['notes']['merchant_transactionid'])->first();

					$saleIdArray=explode(',',$inputData['payload']['refund']['entity']['notes']['sales_id']);

				}elseif(isset($inputData['payload']['payment'])){

					$transaction=\App\Models\Transaction::where('transaction_id',$inputData['payload']['payment']['entity']['notes']['merchant_transactionid'])->first();

					$saleIdArray=explode(',',$inputData['payload']['payment']['entity']['notes']['sales_id']);
				}
				
				if($transaction){

					$transaction->razorpay_order_id=$inputData['payload']['payment']['entity']['order_id'];
					$transaction->razorpay_paymentid=$inputData['payload']['payment']['entity']['id'];
					$transaction->method=$inputData['payload']['payment']['entity']['method'];
					$transaction->card_id=$inputData['payload']['payment']['entity']['card_id'];
					$transaction->bank=$inputData['payload']['payment']['entity']['bank'];
					$transaction->wallet=$inputData['payload']['payment']['entity']['wallet'];
					$transaction->vpa=$inputData['payload']['payment']['entity']['vpa'];
					$transaction->contact=$inputData['payload']['payment']['entity']['contact'];
					$transaction->description=$inputData['payload']['payment']['entity']['description'];
					$transaction->error_code=$inputData['payload']['payment']['entity']['error_code'];
					$transaction->error_description=$inputData['payload']['payment']['entity']['error_description'];
					$transaction->error_reason=$inputData['payload']['payment']['entity']['error_reason'];

					if(isset($inputData['payload']['payment']['entity']['acquirer_data']['bank_transaction_id'])){

						$transaction->bank_transaction_id=$inputData['payload']['payment']['entity']['acquirer_data']['bank_transaction_id'];

					}

					$transaction->payment_status='1';
					if($inputData['event']=='payment.captured'){
						$transaction->payment_status='2';
					}else if($inputData['event']=='payment.failed'){
						$transaction->payment_status='0';
					}else if($inputData['event']=='refund.failed'){
						$transaction->payment_status='8';
					}else if($inputData['event']=='refund.processed'){
						$transaction->payment_status='5';
					}
				
					$transaction->save();

					if(!empty($saleIdArray) && $saleIdArray[0]!=''){

						foreach($saleIdArray as $saleId){

							$saleDetail=\App\Models\Sales_detail::find($saleId);

							if($transaction->payment_status=='2'){
								$saleDetail->order_status="1";
							}
							
							$saleDetail->payment_status=$transaction->payment_status;

							$saleDetail->save();
						}
					}

					if($inputData['event']=='payment.captured'){

						$result=\App\Models\Sales::with('getsalesdetailchild')->where('sales.order_id',$inputData['payload']['payment']['entity']['notes']['merchant_order_id'])->first()->toArray();

						if(!empty($result)){

							// send Msg
							$content ="https://bulksmsapi.vispl.in/?username=fiveotp&password=five_1234&messageType=text&mobile=".$result['phone_code'].$result['mobile']."&senderId=FFERNS&ContentID=1707163756701539494&EntityID=1701163375929957226&message=Order Placed: Thank you for Shopping on FiveFerns. We have received an order with orderID: ".$inputData['payload']['payment']['entity']['notes']['merchant_order_id'].". We will notify you as soon as the order is Confirmed. - Team FiveFerns";				
							\App\Helpers\commonHelper::sendMsg($content);

							\Mail::send('email_templates.order_place', compact('result'), function($message) use ($result)
							{
								$message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
								$message->subject('Order Placed Successfully');
								$message->to($result['email']);
							});

						}
						
					}

				}

			}catch (\Exception $e){
							
				$console=new \App\Models\PaymentConsole();

				$console->value=$e->getMessage();
		
				$console->save();
			
			}

		}

		echo 'done';
	}

	public function checkPincode(Request $request){

		$getUrl = env('Delhivery_url').'/pin-codes/json/?token='.env('Delhivery_token').'&filter_codes='.$request->get('filter_codes').'';
		$apiresult = file_get_contents($getUrl);

		$postcode = json_decode($apiresult,true);

		if(empty($postcode['delivery_codes'])){
		 
			return response(array('message'=>"We are not available in this location."),404);
		
		}else{
			
			return response(array('message'=>"We are available in this location."),200);

		}

	}

	public function newsletterSubscribe(Request $request){
		
		$rules['email']='required|email';

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

				$checkExistEmail= \App\Models\Newsletter::where('email',$request->json()->get('email'))->first();

				if($checkExistEmail){

					return response(array('error'=>true,'message'=>"This Email-id is already registered with us."),403);

				}else{
					
					$couponResult = new \App\Models\Newsletter();

					$couponResult->email=$request->json()->get('email');

					$couponResult->save();

					return response(array('error'=>false,'message'=>"Newsletter Subscribed Successfully."),200);

				}

			}catch (\Exception $e){
				
				return response(array("error"=> true, "message" => $e->getMessage()),403); 
			
			}
			
		}
	}

	public function trackOrder(Request $request){
		
		$rules['order_id']='required';

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

				$checkExistOrder=\App\Models\Sales_detail::where('suborder_id',$request->json()->get('order_id'))->select('suborder_id', 'waybill_no', 'is_approve', 'order_status')->groupBy('sales_details.suborder_id')->get();
				   
				if(count($checkExistOrder)==0){
					
					$checkExistOrder1 = \App\Models\Sales_detail::Where('order_id',$request->json()->get('order_id'))->select('order_id','suborder_id', 'waybill_no','is_approve','order_status')->groupBy('sales_details.suborder_id')->get();
					
					if(count($checkExistOrder1)>0){

						return response(array('message'=>"Order fetch successfully",'result'=>$checkExistOrder1),200); 

					}else{

						return response(array('message'=>"Order not found",'result'=>""),403);
					}

				}else{

					return response(array('message'=>"Order fetch successfully",'result'=>$checkExistOrder),200);

				}

			}catch (\Exception $e){
				
				return response(array("error" 
						=> true, "message" => $e->getMessage()),403); 
			
			}
			
		}
	}

	public function changeDeliveryStatus(Request $request){

		$orderResult= \App\Models\Sales_detail::where('order_status','!=','9')->where('is_approve','1')->get();

		if($orderResult->count()>0){

			foreach($orderResult as $order){

				$waybill = $order->waybill_no;
				$getUrl = "https://track.delhivery.com/api/v1/packages/json/?waybill=$waybill&verbose=2&token=fac9a0852713fb923b9f74d58a32834d7772c81d";
				$apiresult = file_get_contents($getUrl);
				$track = json_decode($apiresult,true);
				$package_status = $track['ShipmentData'][0]['Shipment']['Status']['Status'];
				
				if($package_status=='Out for Delivery'){ $order_status = '10'; } 
				
				if($package_status=='Delivered'){ $order_status = '9'; }
	
				$order->order_status=$order_status;
				$order->save();
			}

		}

	}


	public function getFreeShippingAmount(Request $request){

		$result=\App\Models\Setting::where('id','2')->first();

		if($result){

			return response(array('message'=>'Frees shipping amount fetched successfully.','amount'=>$result->value));

		}else{

			return response(array('message'=>'Frees shipping amount fetched successfully.','amount'=>'0'));
			
		}

	}

	public function blogList(Request $request){
 
		try{
				
			$sliderResult=\App\Models\Blog::where('status','=','1')->orderBy('id','Desc')->get();
			
			if(!$sliderResult){
				
				return response(array('error'=>true,"message" => 'Result not found.'),404); 
			}else{
				
				$result=[];
				
				foreach($sliderResult as $blog){
					
					$result[]=[
						'image'=>$blog->image,
						'title'=>$blog->title,
						'short_desc'=>$blog->short_desc,
						'category_id'=>$blog->category_id,
						'slug'=>$blog->slug,
						'date'=>date('d M Y',strtotime($blog->created_at)),
					];
				}
				return response(array('error'=>false,"message" => 'Blog fetched successfully.','result'=>$result),200); 
				
			}
			
		}catch (\Exception $e){
			
			return response(array('error'=>true,"message" => $e->getMessage()),403); 
		
		} 	 
		
	}

	
	public function singleBlog(Request $request){
		
		$rules['slug']='required';

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

				$blog= \App\Models\Blog::where('slug',$request->json()->get('slug'))->where('status','1')->first();

				if(!$blog){

					return response(array('error'=>true,'message'=>"Blog not found"),403);

				}else{
					
					$result=[
						'image'=>$blog->image,
						'title'=>$blog->title,
						'description'=>$blog->description,
						'date'=>date('d M Y',strtotime($blog->created_at)),
					];

					return response(array('error'=>false,'message'=>"Data fetch success",'result'=>$result),200);

				}

			}catch (\Exception $e){
				
				return response(array("error"=> true, "message" => $e->getMessage()),403); 
			
			}
			
		}
	}

	
	
	public function submitContact(Request $request){
		
		$rules['name']='required';
		$rules['email']='required';
		$rules['mobile']='required';
		$rules['subject']='required';
		$rules['message']='required';

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

				$contact = new \App\Models\Contact();
				$contact->name = $request->json()->get('name');
				$contact->email = $request->json()->get('email');
				$contact->mobile = $request->json()->get('mobile');
				$contact->subject = $request->json()->get('subject');
				$contact->message = $request->json()->get('message');
				$contact->save();
			
				return response(array('error'=>false,'message'=>"Request sent successfully"),200);

			}catch (\Exception $e){
				
				return response(array("error"=> true, "message" => $e->getMessage()),403); 
			
			}
			
		}
	}
	
	
	
}