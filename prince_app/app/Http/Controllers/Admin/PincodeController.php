<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use \App\Models\Pincode;

class PincodeController extends Controller{
	
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
	 
	 
    public function add(Request $request){

		if($request->isMethod('post')){
			
			$rules=[
				'id'=>'numeric|required',
				'pincode'=>'numeric|required'
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
				
				try{
					if((int) $request->post('id')>0){
						
						$slider=Pincode::find($request->post('id'));
					}else{
						
						$slider=new Pincode();
					
					}
					
					
					$slider->pincode=$request->post('pincode');
					$slider->save();
					
					if((int) $request->post('id')>0){
						
						return response(array('message'=>'Pincode updated successfully.','reset'=>false),200);
					}else{
						
						return response(array('message'=>'Pincode added successfully.','reset'=>true,'script'=>true),200);
					
					}
				}catch (\Exception $e){
			
					return response(array("message" => $e->getMessage()),403); 
				
				}
			}
			
			return response(array('message'=>'Data not found.'),403);
		}
		
		$result=[];
        return view('admin.pincode.add',compact('result'));
    }
	
	public function sliderList(){
		
		$result=Pincode::orderBy('id','DESC')->get();
		
		return view('admin.pincode.list',compact('result'));
	}
	
	public function changeStatus(Request $request){
		
		Pincode::where('id',$request->post('id'))->update(['status'=>$request->post('status')]);
		
		return response(array('message'=>'Pincode status changed successfully.'),200);
	}
	
	
	public function updateSlider(Request $request,$id){
		
		$result=Pincode::find($id);
		
		if($result){
			
			
			return view('admin.pincode.add',compact('result'));
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function deleteSlider(Request $request,$id){
		
		$result=Pincode::find($id);
		
		if($result){
			
			Pincode::where('id',$id)->delete();
			
			return redirect()->back()->with('5fernsadminsuccess','Pincode deleted successfully.');
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
}
