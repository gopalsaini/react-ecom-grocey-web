<?php
namespace App\Helpers;
use Ixudra\Curl\Facades\Curl;
use Session;
use DB;

class commonHelper{
	
	public static function callAPI($method, $url, $data=array(),$files=array()){

        
		$url=env('APP_URL').'/public/api'.$url;

        if($method == 'GET'){

            return $response = Curl::to($url)
			->returnResponseObject()
            ->get();

        }elseif($method == 'PUT'){

            return $response = Curl::to($url)

            ->withData(['title'=>'Test', 'body'=>'body goes here', 'userId'=>1])
			->returnResponseObject()
            ->put();

        }elseif($method == 'DELETE'){

            return $response = Curl::to($url)

                ->delete();
        }elseif($method == 'patch'){

            return $response = Curl::to($url)

                ->withData(['title'=>'Test', 'body'=>'body goes here', 'userId'=>1])
				->returnResponseObject()
                ->patch();
        }elseif($method == 'POST'){

            return $response = Curl::to($url)
                ->withData($data)
				->returnResponseObject()
                ->post();
                
        }elseif($method == 'POSTFILE'){
			
            return $response = Curl::to($url)
                ->withData($data)
				->withFile($files['file_input'],$files['image_file'], $files['getMimeType'], $files['getClientOriginalName']) 
                ->post();
                
        }elseif($method == 'userTokenpost'){

            return $response = Curl::to($url)
                ->withData($data)
                ->withBearer(Session::get('5ferns_user'))
				->returnResponseObject()
                ->post();
                
        }elseif($method == 'userTokenget'){
            return $response = Curl::to($url)
            ->withBearer(Session::get('5ferns_user'))
			->returnResponseObject()
            ->get();
        }
        
    }

	public static function buildMenu($parent, $menu, $sub = NULL) {

        $html = "";

        if (isset($menu['parents'][$parent])){
            if (!empty($sub)) {
                $html .= "<ul id=" . $sub . " class='ml-menu'><li class=\"ml-menu\">" . $sub . "</li>\n";
            } else {
                $html .= "<ul class='list'>\n";
            }

            foreach ($menu['parents'][$parent] as $itemId) {
                
				$active=(request()->is($menu['items'][$itemId]['active_link'])) ? 'active' :'';

				$terget = null;
                if (!isset($menu['parents'][$itemId])) { //if condition is false only view menu
                    $html.= "<li class='".$active."' >\n  <a $terget title='" . $menu['items'][$itemId]['label'] . "' href='" . url($menu['items'][$itemId]['link']) . "'>\n <em class='" . $menu['items'][$itemId]['icon'] . " fa-fw'></em><span>" . $menu['items'][$itemId]['label'] . "</span></a>\n</li> \n";
				}
				
                if (isset($menu['parents'][$itemId])) { //if condition is true show with submenu
                    $html .= "<li class='" . $active . "'>\n  <a onclick='return false;' class='menu-toggle' href='#" . $menu['items'][$itemId]['label'] . "'> <em class='" . $menu['items'][$itemId]['icon'] . " fa-fw'></em><span>" . $menu['items'][$itemId]['label'] . "</span></a>\n";
                    $html .= self::buildMenu($itemId, $menu, $menu['items'][$itemId]['label']);
                    $html .= "</li> \n";
                }
				
            }
            $html .= "</ul> \n";
			
        }

        return $html;

    }

	public static function getSidebarMenu(){
		
		if(Session::has('fivefernsadminmenu')){

			$result=Session::get('fivefernsadminmenu');

			$menu = array(
				'items' => array(),
				'parents' => array()
			);
	
			foreach ($result as $v_menu) {
				$menu['items'][$v_menu['menu_id']] = $v_menu;
				$menu['parents'][$v_menu['parent']][] = $v_menu['menu_id'];
			}
	
			return  \App\Helpers\commonHelper::buildMenu(0, $menu);
		}

	}

	
	public static function getSubcategoryById($id){
		
		return \App\Models\Category::where('parent_id',$id)->where('status','1')->where('recyclebin_status','0')->get()->toArray();
	}
	
    public static function getCategoryTree($id){
		
		$category=[];
		
		$categoryResult=\App\Helpers\commonHelper::getSubcategoryById($id);
		
		if($categoryResult){
			
			foreach($categoryResult as $element){
					
				$childResult=\App\Helpers\commonHelper::getCategoryTree($element['id']);

				if($childResult){
					
					$element['child']=$childResult;
				}
				
				$category[]=$element;
			}
		}
		
		return $category;
	}
	
	public static function getCategoryTreeids($id){
		
		$ids="";
		
		$categoryResult=\App\Helpers\commonHelper::getSubcategoryById($id);
		
		if($categoryResult){
			
			foreach($categoryResult as $element){
					
				$childResult=\App\Helpers\commonHelper::getCategoryTreeids($element['id']);

				if($childResult){
					
					$ids.=$childResult;

				}

				$ids.=$element['id'].',';
			}
		}
		
		return $ids;
	}

	public static function getCategoryTreeidsArray($id){

		$idsResult=\App\Helpers\commonHelper::getCategoryTreeids($id);
		
		$idArray=array();

		if(rtrim($idsResult,' , ')){

			$idArray=explode(',',rtrim($idsResult,' , '));
		}

		return $idArray;
		
	}

	public static function getAllCategoryTreeids($id){
		
		$ids="";
		
		$categoryResult=\App\Models\Category::where('parent_id',$id)->where('recyclebin_status','0')->get()->toArray();
		
		if($categoryResult){
			
			foreach($categoryResult as $element){
					
				$childResult=\App\Helpers\commonHelper::getAllCategoryTreeids($element['id']);

				if($childResult){
					
					$ids.=$childResult;

				}

				$ids.=$element['id'].',';
			}
		}
		
		return $ids;
	}

	public static function getAllCategoryTreeidsArray($id){

		$idsResult=\App\Helpers\commonHelper::getAllCategoryTreeids($id);
		
		$idArray=array();

		if(rtrim($idsResult,' , ')){

			$idArray=explode(',',rtrim($idsResult,' , '));
		}

		return $idArray;
		
	}
	
	public static function getCategoryTreeById($id){
		
		$name='';
		
		$result=\App\Models\Category::where('id',$id)->first();
		
		if(!empty($result)){
			
			if($result->parent_id>0){
				
				$name.=\App\Helpers\commonHelper::getCategoryTreeById($result->parent_id);
			}
			
			$name.=ucfirst($result->name).' > ';
			
		}
		
		return $name;
	}
	
	
	public static function getParentName($id){
		
		$nameResult=\App\Helpers\commonHelper::getCategoryTreeById($id);
		
		return rtrim($nameResult,' > ');
		
	}

	public static function getVariantName($id){
		
		$nameResult=\App\Models\Variant::where('id',$id)->first();
		if($nameResult){

			return $nameResult->name;

		}else{

			return 'N/A';
		}
		
		
	}

	public static function getParentCategoryTreeId($id){
		
		$ids='';
		
		$result=\App\Models\Category::where('id',$id)->first();
		
		if(!empty($result)){
			
			if($result->parent_id>0){
				
				$ids.=\App\Helpers\commonHelper::getParentCategoryTreeId($result->parent_id);
			}
			
			$ids.=$result->id.',';
			
		}
		
		return $ids;
	}
	
	public static function getParentId($id){
		
		$idResult=\App\Helpers\commonHelper::getParentCategoryTreeId($id);

		return rtrim($idResult,',');
	
	}
	
	
	public static function getAttributeByparentId($id){

		return \App\Models\Variant_attribute::where('variant_id',$id)->where('status','1')->orderBy('sort_order','ASC')->get();
		
	}
	
	public static function getOtp(){
		
        $otp = mt_rand(1000,9999);

        return $otp;
	}
	
	public static function sendMsg($msg,$mobile){
        
		
		// Account details
    	$apiKey = 'MzU0NjQxNTUzMTMzMzMzMTQ4NmU0YTc1Njc2ODQxNzA=';
    	
    	// Message details
    	$numbers = $mobile;
    	$sender = 'DRSTPG';
    	$message = urlencode($msg);
     
    	$numbers = $mobile;
     
    	// Prepare data for POST request
    	$data = 'apikey='.$apiKey.'&numbers='.$numbers."&sender=".$sender."&message=".$message;
        
    	// Send the GET request with cURL
    	$ch = curl_init('https://api.textlocal.in/send/?apikey=MzU0NjQxNTUzMTMzMzMzMTQ4NmU0YTc1Njc2ODQxNzA=&numbers='.$mobile.'&sender=DRSTPG&message='.$msg.'%20is%20your%20DOORSTEP%20GROCERY%20login%20code.%20code%20is%20valid%20for%2010%20minutes%20only,%20one-time%20use.%20Please%20DO%20NOT%20share%20this%20OTP%20with%20anyone.');
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$response = curl_exec($ch);
    	curl_close($ch);

    	
    	// Process your response here
    	return $response;
	}
	
	public static function getOrderStatusName($id){
		
		$data=array(
			'0'=>'Failed',
			'1'=>'Pending',
			'2'=>'Confirmed',
			'3'=>'Cancelled',
			'4'=>'Order cancel by user',
			'5'=>'Rejected',
			'6'=>'Cancel request pending',
			'7'=>'Rejected',
			'8'=>'Order Returned',
			'9'=>'Delivered',
			'10'=>'Shipped'
		);
		
		return $data[$id];
	}
	
	
	public static function getPaymentStatusName($id){
		
		$data=array(
			'0'=>'Payment Unpaid',
			'1'=>'Failed',
			'2'=>'Payment Paid',
			'3'=>'Refund Initiated',
			'4'=>'Refund In Progress',
			'5'=>'Refund Completed',
			'6'=>'Payment Initiated',
			'7'=>'Payment Failed',
			'8'=>'Refund Failed',
		);
		
		return $data[$id];
	}

	public static function getDiscountType($id){
		
		$data=array(
			'1'=>'%',
			'2'=>'Rs',
		);
		
		return $data[$id];
	}
	
	
	public static function getStateNameById($id){
		
		$result=\App\Models\State::where('id',$id)->first();
		
		return ucfirst($result->name);
	}
	
	
	public static function getCityNameById($id){
		
		$result=\App\Models\City::where('id',$id)->first();
		
		if($result){

			return ucfirst($result->name);

		}else{

			return 'N/A';

		}
		
	}
	
	public static function getCountryidByStateId($id){
		
		$result=\App\Models\State::where('id',$id)->first();
		
		return $result->country_id;
	}
	
	public static function getCountryNameById($id){
		
		$result=\App\Models\Country::where('id',$id)->first();
		
		return ucfirst($result->name);
	}
	
	public static function getOfferProductPrice($salePrice,$discountType,$discountAmount){

		$discountAmount=$discountAmount;
		if($discountType=='1'){
			
			$discountAmount=round((($salePrice*$discountAmount)/100),2);
		}
		
		return $discountAmount;
	}

	public static function movecartDataWithUser(){

        if(Session::has('5ferns_cartuser') && Session::has('5ferns_user')){

			$cartData=Session::get('5ferns_cartuser');

			if(!empty($cartData)){

				foreach($cartData as $cart){

					\App\Helpers\commonHelper::callAPI('userTokenpost','/add-cart',json_encode(array('id'=>'0','product_id'=>$cart['product_id'],'qty'=>$cart['qty'],'remark'=>$cart['remark'],'add_type'=>'add')));

				}

				Session::forget('5ferns_cartuser');
			}

		}
    }

	public static function getShippingAmount($length,$breadth,$height,$weight,$countryId='101'){

		$dimansionWeight=($length*$breadth*$height)/4000;

		$actualWeight=max($dimansionWeight,$weight);

		$pointValue=ceil($actualWeight/500);

		if((int) $countryId==0){
			
			return "0";

		}else if($countryId=='101'){

			$settingResult=\App\Models\Setting::where('id','1')->first();

		}else{

			$settingResult=\App\Models\Setting::where('id','3')->first();
		}
		
		return $pointValue*(float) $settingResult->value;
	}

	public static function convert_number_to_words($number) {

        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . Self::convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . Self::convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = Self::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= Self::convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return ucfirst($string);
    }

	public function getVaraintName($variantIds,$variantAttributes){

		$attribute='';
		$variantArray=explode(',',$variantIds);
												
		if(!empty($variantArray) && $variantArray[0]!=''){
			
			$variantResult=\App\Models\Variant::whereIn('id',$variantArray)->where('status','1')->get();
			
			if(!empty($variantResult)){
					
				foreach($variantResult as $variant){
						
					$attributeArray=explode(',',$variantAttributes); 
					
					if(!empty($attributeArray) && $attributeArray[0]!=''){
							$attributeResult=\App\Models\Variant_attribute::whereIn('id',$attributeArray)->where('variant_id',$variant['id'])->where('status','1')->first();
							
						if($attributeResult){
							
							$attribute.='<label class="labelbold">'.$variant['name'].'</label>:  '.$attributeResult['title'].', ';
						}
						
					} 
				}
			}
		}
		
		return rtrim($attribute,' ,');
	}

	public function getPriceByCountry($price){

		$countryId='1';

		if(Session::has('country_id')){

			$countryId=Session::get('country_id');
		}

		$result=\App\Models\Currency_value::where('id',$countryId)->first();

		$firstIcon='Rs.';
		$SecondIcon='';
		if($result){

			$price=round(($price*$result['value']),2);
			$firstIcon=$result['first_icon'];
			$SecondIcon=$result['second_icon'];
		}

		return $firstIcon.' '.number_format($price,2).' '.$SecondIcon;

	}

	public function getPriceAmountByCountryId($price,$countryId){


		$result=\App\Models\Currency_value::where('id',$countryId)->first();

		if($result){

			$price=round(($price*$result['value']),2);
		}

		return $price;

	}

	public function getpriceIconByCountry($price,$countryId){
		
		$result=\App\Models\Currency_value::where('id',$countryId)->first();
		
		if($result){
			
			$firstIcon=$result['first_icon'];
			$SecondIcon=$result['second_icon']; 
			
			return $firstIcon.' '.number_format($price,2).' '.$SecondIcon;
		}
		
	}

	public function checkCouponCode($userId,$couponCode,$orderAmount){


		$couponResult=\App\Models\Coupon::where([
			['coupon_code','=',$couponCode],
			['recyclebin_status','=','0'],
			['status','=','1'],
			['start_date','<=',date('Y-m-d')],
			['end_date','>=',date('Y-m-d')]
			])->first();

		if(!$couponResult){

			return ['message'=>'Invalid Coupon code','status'=>'403'];

		}else if($couponResult && $orderAmount<$couponResult['minorder_amount']){

			return ['message'=>"Order amount must be greater than to ".$couponResult['minorder_amount'],'status'=>'403'];

		}else{

			$totalUsesCoupon=\App\Models\Sales::where([
							['sales.user_id',$userId],
							['sales.couponcode_id',$couponResult->id],
							['sales_details.order_status','!=','0']
							])->join('sales_details','sales_details.sale_id','=','sales.id')->groupBy('sales.order_id')->count();

			if($totalUsesCoupon>=$couponResult['totalno_uses']){

				return ['message'=>"This coupon limit has been exceed.",'status'=>'403'];

			}else{

				return ['message'=>"Coupon Code Applied Successfully.","coupon_id"=>$couponResult->id,"discount_type"=>$couponResult->discount_type,"discount_amount"=>$couponResult['discount_amount'],'status'=>'200']; 

			}

		}
	}

	
	public static function getCategoryNameById($id){
		
		$result=\App\Models\Category::where('id',$id)->first();
		if(!empty($result)){

			return ucfirst($result->name);

		}else{

			return "N/A";
		}
		
	}

	
	public static function getVaraintDisplayLayoutName($id){
		
		$data=array(
			'1'=>'Dropdown swatch',
			'2'=>'visual swatch',
			'3'=>'Text swatch',
		);
		
		return $data[$id];
	}
	
	public function getVaraintNameById($variantIds){

		$attributeResult=\App\Models\Variant_attribute::where('id',$variantIds)->first();
		if($attributeResult){
		    
		    $Variant=\App\Models\Variant::where('id',$attributeResult->variant_id)->first();
		    
		    return $Variant->name.': '.$attributeResult->title;
		    
		}else{
		    return 0;
		}
		
	}
	
	public static function GetProductAllImages($images){

        if($images){
            
            $Proimages=[];
            foreach ($images as $key => $value) {
                $Proimages[]=[ 'img'=>asset('uploads/products/'.$value)];
            }
    
            return $Proimages;
            
        }else{
            
            return "N/A";
        }
        
    }
    
    
	
	public static function GetAddToCartQtyByUserId($proId, $userId){

        $cartData = \App\Models\Addtocart::where([
										['user_id','=',$userId],
										['product_id','=',$proId],
										])->first();
        if($cartData){
            
            return $cartData->qty;
            
        }else{
            
            return 0;
        }
        
    }

	public static function getCategoryIdBySlug($id){
		
		$ids='0';
		
		$result=\App\Models\Category::where('slug',$id)->first();
		
		if(!empty($result)){
			
			
			$ids = $result->id;
			
		}
		
		return $ids;
	}

}


?>