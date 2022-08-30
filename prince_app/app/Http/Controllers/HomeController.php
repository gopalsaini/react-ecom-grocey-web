<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use Mail;
use Validator;
use Newsletter;
use DB;

class HomeController extends Controller
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
	 
    public function index(Request $request){

		$slider=\App\Helpers\commonHelper::callAPI('GET','/slider-list');

		$testimonial=\App\Helpers\commonHelper::callAPI('GET','/testimonial-list');
		
		$topCategory=\App\Helpers\commonHelper::callAPI('GET','/toprated-category');
		
		$topSelling=\App\Helpers\commonHelper::callAPI('GET','/topselling-product');
		
        $dealsoftheday=\App\Helpers\commonHelper::callAPI('GET','/dealsoftheday-product');

        $wishlist=[];

        if(Session::has('wishlist_user')){

            $wishlist=Session::get('wishlist_user');
        }

        if(Session::has('country_id')){

            if($request->isMethod('post')){
                Session::put('country_id', $request->post('currency'));
                return redirect()->back();
            }

        }else{

            Session::put('country_id','1');

        }

        $seoResult=\App\Models\Seo::where('id','1')->first();

        return view('home',compact('slider','testimonial','topCategory','topSelling','wishlist','dealsoftheday','seoResult'));
    }
	
	public function searchproduct(Request $request){

		$search=\App\Helpers\commonHelper::callAPI('GET','/search-product?text='.$request->get('term').'&category_id='.$request->get('category_id'));
    
		if($search->status==200){

			$searchArray=json_decode($search->content,true);
			
			foreach($searchArray['result'] as $data){
				
				$results[] = ['value' => $data['name'], 'link' => url('product-detail/'.$data['slug']),'label'=>$data['name']];
			}
			
		}else{

            $results[] = ['value' => 'no', 'label' =>'Results Not Found'];
        }
	
		return response()->json($results);  

	}

    public function register(){

        $country=\App\Models\Country::select('phonecode')->get();

        return view('register',compact('country'));
    }

    public function forgotpassword(){
        return view('forgot_password');
    }

    public function trackOrder(){ 
        return view('track_order');
    }

    public function getState(Request $request){

        $country_id=$request->get('country_id');

        $option="<option value='' selected >--Select--</option>";

        if($country_id>0){

            $stateResult=\App\Models\State::where('country_id',$country_id)->get();

            foreach($stateResult as $state){

                $option.="<option value='".$state['id']."'>".ucfirst($state['name'])."</option>";
            }
        }

        return response(array('message'=>'state fetched successfully.','html'=>$option));
    }
    
	
    public function getCity(Request $request){

        $stateId=$request->get('state_id');

        $option="<option value='' selected >--Select--</option>";

        if($stateId>0){

            $cityResult=\App\Models\City::where('state_id',$stateId)->get();

            foreach($cityResult as $city){
    
                $option.="<option value='".$city['id']."'>".ucfirst($city['name'])."</option>";
            }

        }

        return response(array('message'=>'City fetched successfully.','html'=>$option));
    }

    public function termsCondition(Request $request){

        $apiData=\App\Helpers\commonHelper::callAPI('GET','/get-information-pages-data/1');
        $result=json_decode($apiData->content,true);
        $result=$result['result'];

        $meta=[
            'title'=>'Terms & Conditions',
            'keywords'=>'Terms & Conditions',
            'description'=>'Terms & Conditions',
        ];
        
        return view('information',compact('result','meta'));

    }

    public function privacyPolicy(Request $request){

        $apiData=\App\Helpers\commonHelper::callAPI('GET','/get-information-pages-data/2');
        $result=json_decode($apiData->content,true);
        $result=$result['result'];

        $meta=[
            'title'=>'Privacy Policy',
            'keywords'=>'Privacy Policy',
            'description'=>'Privacy Policy',
        ];

        return view('information',compact('result','meta'));

    }

    public function aboutUs(Request $request){

        $apiData=\App\Helpers\commonHelper::callAPI('GET','/get-information-pages-data/3');
        $result=json_decode($apiData->content,true);
        $result=$result['result'];

        $meta=[
            'title'=>'About us',
            'keywords'=>'About us',
            'description'=>'About us',
        ];

        return view('information',compact('result','meta'));

    }

    public function returnRefundPolicy(Request $request){

        $apiData=\App\Helpers\commonHelper::callAPI('GET','/get-information-pages-data/4');
        $result=json_decode($apiData->content,true);
        $result=$result['result'];

        $meta=[
            'title'=>'Return & Refund Policy',
            'keywords'=>'Return & Refund Policy',
            'description'=>'Return & Refund Policy',
        ];

        return view('information',compact('result','meta'));

    }

    public function cancellationPolicy(Request $request){

        $apiData=\App\Helpers\commonHelper::callAPI('GET','/get-information-pages-data/6');
        $result=json_decode($apiData->content,true);
        $result=$result['result'];

        $meta=[
            'title'=>'Cancellation Policy',
            'keywords'=>'Cancellation Policy',
            'description'=>'Cancellation Policy',
        ];

        return view('information',compact('result','meta'));

    }

    public function shippingPolicy(Request $request){

        $apiData=\App\Helpers\commonHelper::callAPI('GET','/get-information-pages-data/5');
        $result=json_decode($apiData->content,true);
        $result=$result['result'];

        $meta=[
            'title'=>'About us',
            'keywords'=>'About us',
            'description'=>'About us',
        ];

        return view('information',compact('result','meta'));


    }
	
	public function subscribeNewsletter(Request $request){

        $data=array(
            'email'=>$request->post('email'),
        );
        
        $apiData=\App\Helpers\commonHelper::callAPI('POST','/newsletter-subscribe',json_encode($data));
        $resultData=json_decode($apiData->content,true);
        
        return response(array('message'=>$resultData['message']),$apiData->status);

    }

    public function userTrackOrder(Request $request){
       
        $data=array(
            'order_id'=>$request->post('order_id'),
        );

        $result=\App\Helpers\commonHelper::callAPI('POST','/track-order',json_encode($data));       
        $resultData=json_decode($result->content,true);
        
        if($result->status==200){

            $order = $resultData['result'];
           
            $html=view('order_track_url',compact('order'))->render();

            return response(array('message'=>$resultData['message'],'html'=>$html),$result->status);

        }else{

            return response(array('message'=>$resultData['message']),$result->status);
        }
        
 
    }
	
}
