<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Testimonial;

class TestimonialController extends Controller
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
				'name'=>'required',
				'designation'=>'required',
				'description'=>'required'
			];
			
			if((int) $request->post('id')==0){
						
				$rules['image']='required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:width=70,height=70';
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
				 
				$image=$request->post('old_image');
				
				if($request->hasFile('image')){
					$imageData = $request->file('image');
					$image = strtotime(date('Y-m-d H:i:s')).'.'.$imageData->getClientOriginalExtension();
					$destinationPath = public_path('/uploads/testimonial');
					$imageData->move($destinationPath, $image);
				} 
				
				if((int) $request->post('id')>0){
					
					$testimonial=Testimonial::find($request->post('id'));
				}else{
					
					$testimonial=new Testimonial();
				
				}
				
				$testimonial->name=$request->post('name');
				$testimonial->designation=$request->post('designation');
				$testimonial->image=$image;
				$testimonial->description=$request->post('description');
				
				$testimonial->save();
				
				if((int) $request->post('id')==0){
					
					return response(array('message'=>'Testimonial added successfully.','reset'=>true),200);
				}else{
					
					return response(array('message'=>'Testimonial updated successfully.','reset'=>false),200);
				
				} 
			}
			return response(array('message'=>'Data not found.'),403);
		}
		
		$result=[];
        return view('admin.testimonial.add',compact('result'));
    }
	
	public function testimonialList(){

		$result=Testimonial::where('recyclebin_status','0')->orderBy('id','DESC')->get();
		
		return view('admin.testimonial.list',compact('result'));
	}
	
	public function updateTestimonial(Request $request,$id){
		
		$result=Testimonial::find($id);
		
		if($result){
			 
			return view('admin.testimonial.add',compact('result'));
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function deleteTestimonial(Request $request,$id){
		
		$result=Testimonial::find($id);
		
		if($result){
			
			Testimonial::where('id',$id)->update(['recyclebin_status'=>'1','recyclebin_datetime'=>date('Y-m-d H:i:s')]);
			
			return redirect()->back()->with('5fernsadminsuccess','Testimonial deleted successfully.');
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function changeStatus(Request $request){
		
		Testimonial::where('id',$request->post('id'))->update(['status'=>$request->post('status')]);

		return response(array('message'=>'Testimonial status changed successfully.'),200);
	}

}
