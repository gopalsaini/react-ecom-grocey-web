<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Coupon;

class CouponController extends Controller
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
	 
	 
    public function add(Request $request){
			
		if($request->isMethod('post')){
			
			$rules=[
				'id'=>'numeric|required',
				'title'=>'required',
				'coupon_code'=>'required',
				'start_date'=>'required',
				'end_date'=>'required',
				'totalno_uses'=>'required|numeric',
				'minorder_amount'=>'required|numeric',
				'discount_type'=>'required',
				'discount_amount'=>'required'
			];

			if($request->post('discount_type')=='1'){

				$rules['discount_amount']='required|numeric|max:100|min:0';

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
				
				// check unique coupon title
				$titleResult=Coupon::where('title',$request->post('title'))->where('recyclebin_status','0')->where('id','!=',$request->post('id'))->first();
				
				$codeResult=Coupon::where('coupon_code',$request->post('coupon_code'))->where('recyclebin_status','0')->where('id','!=',$request->post('id'))->first();
				
				if($titleResult){
					
					return response(array('message'=>'Coupon title already exist'),403);
					
				}else if($codeResult){
					
					return response(array('message'=>'Coupon code already exist'),403);
					
				}else{
					
					if((int) $request->post('id')>0){
					
						$coupon=Coupon::find($request->post('id'));

						if($coupon->totalno_uses>$request->post('totalno_uses')){

							return response(array('message'=>'Total No uses value must be greater than to previous value.'),403);
						}
						
					}else{
						
						$coupon=new Coupon();
					
					}
					
					$coupon->title=$request->post('title');
					$coupon->coupon_code=$request->post('coupon_code');
					$coupon->start_date=date('Y-m-d',strtotime($request->post('start_date')));
					$coupon->end_date=date('Y-m-d',strtotime($request->post('end_date')));
					$coupon->totalno_uses=$request->post('totalno_uses');
					$coupon->minorder_amount=$request->post('minorder_amount');
					$coupon->discount_type=$request->post('discount_type');
					$coupon->discount_amount=$request->post('discount_amount');
					
					$coupon->save();
					
					if((int) $request->post('id')==0){
						
						return response(array('message'=>'Coupon added successfully.','reset'=>true),200);
					}else{
						
						return response(array('message'=>'Coupon updated successfully.','reset'=>false),200);
					
					}
				}
				
			}
			return response(array('message'=>'Data not found.'),403);
		}
		
		$result=[];
        return view('admin.catalog.coupon.add',compact('result'));
    }
	
	public function couponList(){

		$result=Coupon::where('recyclebin_status','0')->orderBy('id','DESC')->get();
		
		return view('admin.catalog.coupon.list',compact('result'));
	}
	
	public function updateCoupon(Request $request,$id){
		
		$result=Coupon::find($id);
		
		if($result){

			return view('admin.catalog.coupon.add',compact('result'));
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function deleteCoupon(Request $request,$id){
		
		$result=Coupon::find($id);
		
		if($result){
			
			Coupon::where('id',$id)->update(['recyclebin_status'=>'1','recyclebin_datetime'=>date('Y-m-d H:i:s')]);
			
			return redirect()->back()->with('5fernsadminsuccess','Coupon deleted successfully.');
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function changeStatus(Request $request){
		
		Coupon::where('id',$request->post('id'))->update(['status'=>$request->post('status')]);
		
		return response(array('message'=>'Coupon status changed successfully.'),200);
	}

}
