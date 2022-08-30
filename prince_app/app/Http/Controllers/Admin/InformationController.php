<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Information;

class InformationController extends Controller
{
    
    public function termCondition(){
		$result=Information::where('id','1')->first();
		return view('admin.information.add',compact('result'));
	}

    public function privacyPolicy(){
		$result=Information::where('id','2')->first();
		return view('admin.information.add',compact('result'));
	}

    public function aboutUs(){
		$result=Information::where('id','3')->first();
		return view('admin.information.add',compact('result'));
	}

    public function returnPolicy(){
		$result=Information::where('id','4')->first();
		return view('admin.information.add',compact('result'));
	}

    public function shippingPolicy(){
		$result=Information::where('id','5')->first();
		return view('admin.information.add',compact('result'));
	
    }

    public function cancellationPolicy(){
		$result=Information::where('id','6')->first();
		return view('admin.information.add',compact('result'));
	}

    public function UpdateDetail(Request $request){

		if($request->isMethod('post')){
			
			$rules=[
				'id'=>'numeric|required|in:1,2,3,4,5,6',
				'description'=>'required'
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
							
							$Information=Information::find($request->post('id'));
						}else{
							
							$Information=new Information();
						
						}
						
						$Information->description=$request->post('description');
						
						$Information->save();
						
						if((int) $request->post('id')>0){
							
							return response(array('message'=>'Information updated successfully.','reset'=>false),200);
						}
					}catch (\Exception $e){
				
						return response(array("message" => $e->getMessage()),403); 
					
					}
			}
			return response(array('message'=>'Data not found.'),403);
		}
		
        return view('admin.information.add');
    }
}
