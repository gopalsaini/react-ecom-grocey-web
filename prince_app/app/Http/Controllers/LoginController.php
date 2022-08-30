<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use Mail;
use Validator;
use Newsletter;
use Socialite;

class LoginController extends Controller
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

    public function login(){

        $country=\App\Models\Country::select('phonecode')->get();

        return view('login',compact('country'));
    }
	
	
	public function loginWithEmail(Request $request){
       
        $data=array(
            'type'=>'email',
            'type_value'=>$request->post('email'),
            'password'=>$request->post('password')
        );

        $result=\App\Helpers\commonHelper::callAPI('POST','/login',json_encode($data));
        $resultData=json_decode($result->content,true);

        if($result->status==200 && $resultData['verify']){

            Session::put('5ferns_user',$resultData['token']);

            \App\Helpers\commonHelper::movecartDataWithUser();

            Session::put('wishlist_user',$resultData['wishlistid']);
            
            return response(array('message'=>$resultData['message'],'verfiy'=>$resultData['verify']),$result->status);

        }else if($result->status==200 && !$resultData['verify']){

            $passresult['msg']=$resultData['message'];
            $passresult['type']='email';
            $passresult['phone_code']='0';
            $passresult['type_value']=$request->post('email');
            $passresult['showmsg']='verifymsg';
            $html=view('registration_verify',compact('passresult'))->render();

            return response(array('message'=>$resultData['message'],'html'=>$html),$result->status);

        }else{

            return response(array('message'=>$resultData['message']),$result->status);
        }
        
    }
	
	public function loginWithMobile(Request $request){
       
        $otp=$request->post('otp1');
        $otp.=$request->post('otp2');
        $otp.=$request->post('otp3');
        $otp.=$request->post('otp4');

        $data=array(
            'type'=>'mobile',
            'type_value'=>$request->post('mobile'),
            'phone_code'=>$request->post('phone_code'),
            'otp'=>$otp
        );

        $result=\App\Helpers\commonHelper::callAPI('POST','/login',json_encode($data));
        $resultData=json_decode($result->content,true);

        if($result->status==200 && $resultData['verify']){

            Session::put('5ferns_user',$resultData['token']);

            \App\Helpers\commonHelper::movecartDataWithUser();

            Session::put('wishlist_user',$resultData['wishlistid']);

            return response(array('message'=>$resultData['message'],'verify'=>$resultData['verify']),$result->status);

        }else{

            return response(array('message'=>$resultData['message']),$result->status);

        }
        
    }

    public function getOtp(Request $request){

        if($request->post('type')=='mobile'){

            $data=array(
                'phone_code'=>$request->post('phone_code'),
                'mobile'=>$request->post('type_value')
            );

            $result=\App\Helpers\commonHelper::callAPI('POST','/mobile-getotp',json_encode($data));

            $resultData=json_decode($result->content,true);

            return response(array('message'=>$resultData['message']),$result->status);

        }else if($request->post('type')=='email'){

            $data=array(
                'type'=>'email',
                'email'=>$request->post('type_value')
            );

            $result=\App\Helpers\commonHelper::callAPI('POST','/mail-getotp',json_encode($data));

            $resultData=json_decode($result->content,true);

            return response(array('message'=>$resultData['message']),$result->status);

        }else{

            return response(array('message'=>'Something went wrong. Please try again.'),403);
        }

    }

    public function registerByEmail(Request $request){

        $data=array(
            'name'=>$request->post('name'),
            'email'=>$request->post('email'),
            'password'=>$request->post('password')
        );

        $result=\App\Helpers\commonHelper::callAPI('POST','/emailuser-registration',json_encode($data));

        $resultData=json_decode($result->content,true);

        if($result->status==200){

            $passresult['msg']=$resultData['message'];
            $passresult['type']='email';
            $passresult['phone_code']='0';
            $passresult['type_value']=$request->post('email');
            $passresult['showmsg']='verifymsg';
            $html=view('registration_verify',compact('passresult'))->render();

            return response(array('message'=>$resultData['message'],'html'=>$html),200); 

        }else{

            return response(array('message'=>$resultData['message']),$result->status);

        }
        
    }

    public function registerByMobile(Request $request){

        $data=array(
            'name'=>$request->post('name'),
            'phone_code'=>$request->post('phone_code'),
            'mobile'=>$request->post('mobile')
        );

        $result=\App\Helpers\commonHelper::callAPI('POST','/mobileuser-registration',json_encode($data));

        $resultData=json_decode($result->content,true);

        if($result->status==200){

            $passresult['msg']=$resultData['message'];
            $passresult['type']='mobile';
            $passresult['phone_code']=$request->post('phone_code');
            $passresult['type_value']=$request->post('mobile');
            $passresult['showmsg']='verifymsg';
            $html=view('registration_verify',compact('passresult'))->render();

            return response(array('message'=>$resultData['message'],'html'=>$html),200); 

        }else{

            return response(array('message'=>$resultData['message']),$result->status);

        }
        
    }

    public function verifyAccount(Request $request){

        $otp=$request->post('otp1');
        $otp.=$request->post('otp2');
        $otp.=$request->post('otp3');
        $otp.=$request->post('otp4');

        $data=array(
            'type'=>$request->post('type'),
            'type_value'=>$request->post('type_value'),
            'otp'=>$otp
        );

        $result=\App\Helpers\commonHelper::callAPI('POST','/validate-otp',json_encode($data));

        $resultData=json_decode($result->content,true);

        if($result->status==200){
            
            Session::put('5ferns_user',$resultData['token']);

            \App\Helpers\commonHelper::movecartDataWithUser();

            Session::put('wishlist_user',$resultData['wishlistid']);

            return response(array('message'=>$resultData['message'],'token'=>$resultData['token']),$result->status);

        }else{

            return response(array('message'=>$resultData['message']),$result->status);
        }
        

    }

    public function redirectToGoogle() {

        return Socialite::driver('google')->redirect();

    }

    public function handleGoogleCallback(){

        try {

            $user = Socialite::driver('google')->user();

            $result=\App\Helpers\commonHelper::callAPI('POST','/social-login',json_encode(array('type'=>'google','social_id'=>$user->id,'name'=>$user->name,'email'=>$user->email)));
            $resultData=json_decode($result->content,true);

            if($result->status==200){

                Session::put('5ferns_user',$resultData['token']);

                \App\Helpers\commonHelper::movecartDataWithUser();
    
                Session::put('wishlist_user',$resultData['wishlistid']);
                
                return redirect('myprofile')->with('5fernsuser_success',$resultData['message']);

            }else{

                return redirect('login')->with('5fernsuser_error',$resultData['message']);
            }

        } catch (Exception $e) {

            dd($e->getMessage());

        }

    }


    public function redirectToFacebook() {

        return Socialite::driver('facebook')->redirect();

    }


    public function handleFacebookCallback(){

        try {

            $user = Socialite::driver('facebook')->user();

            //echo "<pre>";
            //print_r($user);
            //die;
            $result=\App\Helpers\commonHelper::callAPI('POST','/social-login',json_encode(array('type'=>'facebook','social_id'=>$user->id,'name'=>$user->name,'email'=>$user->email)));
            $resultData=json_decode($result->content,true);
    
            if($result->status==200){

                Session::put('5ferns_user',$resultData['token']);

                \App\Helpers\commonHelper::movecartDataWithUser();
    
                Session::put('wishlist_user',$resultData['wishlistid']);
              
                return redirect()->to('myprofile')->with('5fernsuser_success',$resultData['message']);
                //return response(array('message'=>$resultData['message'],'verfiy'=>$resultData['verify']),$result->status);

            }else{

                return redirect('login')->with('5fernsuser_error',$resultData['message']);
            }

        } catch (Exception $e) {

            dd($e->getMessage());

        }

    }
	
}
