<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use Validator;
use DB;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){

		  $this->middleware('Userauth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */ 

    public function profile(){
      $active_tab = '1';
      $profileData=\App\Helpers\commonHelper::callAPI('userTokenget','/user-profile');

      $resultData=json_decode($profileData->content,true);
      return view('profile/profile')->with(compact('active_tab','resultData'));
      
    }

    public function myOrders(){
      $active_tab = '2';
      $orderData=\App\Helpers\commonHelper::callAPI('userTokenget','/order');
      $resultData=json_decode($orderData->content,true);
      return view('profile/order')->with(compact('active_tab','resultData'));
      
    }

    public function orderInvoice(Request $request,$id){

      $result=\App\Models\Sales_detail::select('sales.name','sales.country_id','sales.state_id','sales.address_line1','sales.address_line2','sales.city_id','sales.pincode','sales_details.*')->where('sales_details.id',$id)
                          ->where('sales_details.payment_status','2')
                          ->orWhere('sales_details.payment_status','9')
                          ->orWhere('sales_details.payment_status','10')
                          ->join('sales','sales_details.sale_id','=','sales.id');

      $result=$result->first();

      if(!$result){

        return redirect('my-orders')->with('5fernsuser_error','Something went Wrong. Please try again.');

      }else{


        $taxPercentage=0;

        $variantResult=\App\Models\Variantproduct::where('id',$result['product_id'])->first(); 
        
        if($variantResult){

          $productData = \App\Models\Product::where('id',$variantResult['product_id'])->first();

          if($productData){

            $taxPercentage=$productData->tax_ratio;
          }
        }

        $unitPrice=$result->amount;
        $netPrice=round(($result->amount*$result->qty),2);
        $grandTotal=round(($result->amount*$result->qty),2);

        $taxApply=true;
        $cgsg=false;
        $igst=false;
        $taxAmount=0;

        if($result->country_id=='101'){

          $unitPrice=round((($result->amount*100)/(100+$taxPercentage)),2);

          $netPrice=($unitPrice*$result->qty);

          if($result->state_id=='4016'){

            $cgsg=true;
            $taxAmount=round((($grandTotal-$netPrice)/2),2);

          }else{

            $igst=true;
            $taxAmount=round((($grandTotal-$netPrice)/2),2);
          }

        }else{

          $taxApply=false;

        }

        return view('profile.order_invoice',compact('result','unitPrice','netPrice','taxApply','cgsg','igst','taxAmount','grandTotal'));

      }

    }

    public function myAddressBook(){

      $active_tab = '3';

      $country=\App\Models\Country::select('id','name','phonecode')->get();
      
      return view('profile/address',compact('country','active_tab'));
      
    }

    public function myWishlist(Request $request){

      $active_tab = '4';
      $wishlist=\App\Helpers\commonHelper::callAPI('userTokenget','/wishlist-product-list');
      
      return view('profile/wishlist',compact('active_tab','wishlist'));

    }

    public function getSavedAddress(Request $request){

      $apiData=\App\Helpers\commonHelper::callAPI('userTokenget','/address-list');
      $resultData=json_decode($apiData->content,true);
  
      $result=[];
  
      if($apiData->status==200){
  
        $result=$resultData['result'];
      }

  
      $html=view('profile/address_book',compact('result'))->render();
  
      return response(array('messsages'=>'Address fetched successfully.','html'=>$html),200);
    }

    public function addAddress(Request $request){

      $data=array(
        'id'=>$request->post('id'),
        'name'=>$request->post('name'),
        'mobile'=>$request->post('mobile'),
        'email'=>$request->post('email'),
        'phone_code'=>$request->post('phone_code'),
        'address_line1'=>$request->post('address_line1'),
        'address_line2'=>$request->post('address_line2'),
        'country_id'=>$request->post('country_id'),
        'state_id'=>$request->post('state_id'),
        'city_id'=>$request->post('city_id'),
        'pincode'=>$request->post('pincode'),
        'type'=>$request->post('type_id')
      );

      $apiData=\App\Helpers\commonHelper::callAPI('userTokenpost','/add-address',json_encode($data));
			$resultData=json_decode($apiData->content,true);

      return response(array('message'=>$resultData['message']),$apiData->status);
    }
    
    public function updateProfile(Request $request){
      
      $data=array(
        'name'=>$request->post('name'),
        'gender'=>$request->post('gender')
      );

      $apiData=\App\Helpers\commonHelper::callAPI('userTokenpost','/update-profile',json_encode($data));
			$resultData=json_decode($apiData->content,true);

      return response(array('message'=>$resultData['message']),$apiData->status);
    }
    
    public function updatePassword(Request $request){
      
      $data=array(
        'old_password'=>$request->post('old_password'),
        'password'=>$request->post('password'),
        'confirm_password'=>$request->post('confirm_password')
      );

      $apiData=\App\Helpers\commonHelper::callAPI('userTokenpost','/change-password',json_encode($data));
			$resultData=json_decode($apiData->content,true);

      return response(array('message'=>$resultData['message']),$apiData->status);
    }

    public function removeAddress(Request $request){
      
      $address_id = $request->address_id;
      $data=array(
        'id'=>$address_id
      );

      $apiData=\App\Helpers\commonHelper::callAPI('userTokenpost','/delete-address',json_encode($data));
			$resultData=json_decode($apiData->content,true);

      return redirect('my-address-book')->with('5fernsuser_success',$resultData['message']);
    }


    public function updateAddress(Request $request){

      $result=[];

      if((int) $request->address_id>0){

        $apiData=\App\Helpers\commonHelper::callAPI('userTokenget','/get-address-by-id?id='.$request->address_id);
        $resultData=json_decode($apiData->content,true);
    
        $result=[];
    
        if($apiData->status==200){
    
          $result=$resultData['result'];
        }

      }
      
      $country=\App\Models\Country::select('id','name','phonecode')->get()->toArray();

      $html=view('profile/update_address',compact('result','country'))->render();
  
      return response(array('messsages'=>'Address fetched successfully.','html'=>$html),200);

    }

    public function logout(Request $request){

      Session::forget('5ferns_user');

      Session::forget('wishlist_user');

      $apiData=\App\Helpers\commonHelper::callAPI('userTokenget','/logout');

      return redirect('/')->with('5fernsuser_success','Logout successfully.');

    }
	
}
