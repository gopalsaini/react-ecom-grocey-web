<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Coupon;

class SettingController extends Controller
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
	 
	 
    public function updatePrice(Request $request){


		if($request->ajax()){

			$rules=[
				'delivery_shipping_charge'=>'numeric|required',
				'free_shipping'=>'numeric|required',
				'delivery_international_shipping_charge'=>'numeric|required',
			];

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

				\App\Models\Setting::where('id','1')->update(['value'=>$request->post('delivery_shipping_charge')]);

				\App\Models\Setting::where('id','2')->update(['value'=>$request->post('free_shipping')]);

				\App\Models\Setting::where('id','3')->update(['value'=>$request->post('delivery_international_shipping_charge')]);

				return response(array('message'=>"Price updated successfully."),200);
			}
		}

		$result=\App\Models\Setting::where(function($query){
								$query->Where('id','1');
								$query->orWhere('id','2');
								$query->orWhere('id','3');
							})->get();

		return view('admin.settings.update_price',compact('result'));		

	}

    public function updateCurrency(Request $request){

		if($request->ajax()){

			$rules=[
				'name'=>'required',
			];

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

				foreach($request->name as $key=>$name){

					\App\Models\Currency_value::where('id',$key+1)->update(['value'=>$name]);
				}
				
				return response(array('message'=>'Updated successfully'),200);
			}
		}

		$currency =\App\Models\Currency_value::all();
		
		return view('admin.settings.currency_price',compact('currency'));		

	}

}
