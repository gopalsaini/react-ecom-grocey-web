<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandController extends Controller
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
				'name'=>'string|required',
				'short_order'=>'required',
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
				
				
				$chkAlreadyExistName=Brand::where([
													['name','=',$request->post('name')],
													['id','!=',$request->post('id')]
													])->first();
				
				if($chkAlreadyExistName){
					
					return response(array('message'=>"Brand name already exist with this selected brand."),403);
					
				}else{
					
					
					if((int) $request->post('id')>0){
						
						$category=Brand::find($request->post('id'));
					}else{
						
						$category=new Brand();
					
					}

                    if($request->hasFile('image')){
						$imageData = $request->file('image');
						$image = strtotime(date('Y-m-d H:i:s')).'.'.$imageData->getClientOriginalExtension();
						$destinationPath = public_path('/uploads/brand');
						$img = \Image::make($imageData->getRealPath());
						$img->save($destinationPath.'/'.$image,60);
						
					    $category->image=$image;
					}
                    
					
					$category->name=$request->post('name');
					$category->short_order=$request->post('short_order');
					
					$category->save();
					
					if((int) $request->post('id')==0){
						
						return response(array('message'=>'Brand added successfully.','reset'=>true,'script'=>true),200);
					}else{
						
						return response(array('message'=>'Brand updated successfully.','reset'=>false),200);
					
					}
					
				}
			}
			return response(array('message'=>'Data not found.'),403);
		}
		
		$result=[];
        return view('admin.catalog.brand.add',compact('result'));
    }
	
	public function categoryList(){

		$result=Brand::orderBy('id','DESC')->get();
		
		return view('admin.catalog.brand.list',compact('result'));
	}
	
	public function updateCategory(Request $request,$id){
		
		$result=Brand::find($id);
		
		if($result){
			
			return view('admin.catalog.brand.add',compact('result'));
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function deleteCategory(Request $request,$id){

		$result=Brand::find($id);
		
		if($result){


			Brand::where('id',$id)->delete();
			
			return redirect()->back()->with('5fernsadminsuccess','Brand deleted successfully.');
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function changeStatus(Request $request){
		
		Brand::where('id',$request->post('id'))->update(['status'=>$request->post('status')]);
		
		
		return response(array('message'=>'Brand status changed successfully.'),200);
	}
	
	public function HomeStatus(Request $request){
		
		Brand::where('id',$request->post('id'))->update(['home'=>$request->post('status')]);
		
		
		return response(array('message'=>'Brand home status changed successfully.'),200);
	}
	
	

}
