<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use \App\Models\Slider;

class SliderController extends Controller{
	
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
				'cate_id'=>'required',
				'sort_order'=>'numeric|required'
			];
			 
			if((int) $request->post('id')==0){
						
				$rules['image']='required|image|mimes:jpeg,png,jpg,gif,svg';
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
				
				try{
					if((int) $request->post('id')>0){
						
						$slider=Slider::find($request->post('id'));
					}else{
						
						$slider=new Slider();
					
					}
					
					$image=$request->post('old_image');
					
					if($request->hasFile('image')){
						$imageData = $request->file('image');
						$image = strtotime(date('Y-m-d H:i:s')).'.'.$imageData->getClientOriginalExtension();
						$destinationPath = public_path('/uploads/sliders');
						$img = \Image::make($imageData->getRealPath());
						$img->save($destinationPath.'/'.$image,60);
					} 
					
					$slider->cate_id=$request->post('cate_id');
					$slider->sort_order=$request->post('sort_order');
					$slider->image=$image;
					
					$slider->save();
					
					if((int) $request->post('id')>0){
						
						return response(array('message'=>'Slider updated successfully.','reset'=>false),200);
					}else{
						
						return response(array('message'=>'Slider added successfully.','reset'=>true,'script'=>true),200);
					
					}
				}catch (\Exception $e){
			
					return response(array("message" => $e->getMessage()),403); 
				
				}
			}
			
			return response(array('message'=>'Data not found.'),403);
		}
		
		$category=\App\Models\Category::where([
									['status','1'],
									])->orderBy('name','ASC')->get();
		$result=[];
        return view('admin.slider.add',compact('result','category'));
    }
	
	public function sliderList(){
		
		$result=Slider::where('recyclebin_status','0')->orderBy('id','DESC')->get();
		
		return view('admin.slider.list',compact('result'));
	}
	
	public function changeStatus(Request $request){
		
		Slider::where('id',$request->post('id'))->update(['status'=>$request->post('status')]);
		
		return response(array('message'=>'Slider status changed successfully.'),200);
	}
	
	
	public function updateSlider(Request $request,$id){
		
		$result=Slider::find($id);
		
		if($result){
			
			$category=\App\Models\Category::where([
									['status','1'],
									])->orderBy('name','ASC')->get();
									
			return view('admin.slider.add',compact('result','category'));
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function deleteSlider(Request $request,$id){
		
		$result=Slider::find($id);
		
		if($result){
			
			Slider::where('id',$id)->update(['recyclebin_status'=>'1','recyclebin_datetime'=>date('Y-m-d H:i:s')]);
			
			return redirect()->back()->with('5fernsadminsuccess','Slider deleted successfully.');
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
}
