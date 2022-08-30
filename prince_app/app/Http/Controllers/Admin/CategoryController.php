<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
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
				'meta_title'=>'string|required',
				'meta_keywords'=>'string|required',
				'meta_description'=>'string|required',
				'short_order'=>'required',
			];
			
			if((int) $request->post('id')==0){
						
				$rules['image']='required|image|mimes:jpeg,png,jpg,gif,svg';
				$rules['banner_image']='required|image|mimes:jpeg,png,jpg,gif,svg';
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
				
				$parent=Category::find($request->post('parent_id'));
				
				$chkAlreadyExistName=Category::where([
													['recyclebin_status','=','0'],
													['name','=',$request->post('name')],
													['parent_id','=',$request->post('parent_id')],
													['id','!=',$request->post('id')]
													])->first();
				
				if(!$parent && $request->post('parent_id')!=''){
					
					return response(array('message'=>"Parent category doesn't exist."),403);
				
				}else if($chkAlreadyExistName){
					
					return response(array('message'=>"Category name already exist with this selected category."),403);
					
				}else{
					
					
					
					
					
					
					if((int) $request->post('id')>0){
						
						$category=Category::find($request->post('id'));
					}else{
						
						$category=new Category();
					
					}

                    if($request->hasFile('image')){
						$imageData = $request->file('image');
						$image = strtotime(date('Y-m-d H:i:s')).'.'.$imageData->getClientOriginalExtension();
						$destinationPath = public_path('/uploads/category');
						$img = \Image::make($imageData->getRealPath());
						$img->save($destinationPath.'/'.$image,60);
						
					    $category->image=$image;
					}
                    if($request->hasFile('banner_image')){
						$imageData = $request->file('banner_image');
						$image2 = strtotime(date('Y-m-d H:i:s')).'.'.$imageData->getClientOriginalExtension();
						$destinationPath = public_path('/uploads/category');
						$img = \Image::make($imageData->getRealPath());
						$img->save($destinationPath.'/'.$image2,60);
						$category->banner_image=$image2;
					}
					
					$parentIds=\App\Helpers\commonHelper::getParentId($request->post('parent_id'));
					$parentCategoryResult=\App\Models\Category::whereIn('id',explode(',',$parentIds))->orderBy('id','ASC')->get();
					
					$slug="";
					if($parentCategoryResult->count()>0){

						foreach($parentCategoryResult as $parentCategory){

							$slug.=Str::slug($parentCategory->name).'-';
						}
					}

					$slug.=Str::slug($request->post('name'));

					$category->name=$request->post('name');
					$category->short_order=$request->post('short_order');
					$category->slug=strtolower($slug);
					$category->parent_id=$request->post('parent_id');
					$category->description=$request->post('description');
					$category->meta_title=$request->post('meta_title');
					$category->meta_keywords=$request->post('meta_keywords');
					$category->meta_description=$request->post('meta_description');
					
					$category->save();
					
					if((int) $request->post('id')==0){
						
						return response(array('message'=>'Category added successfully.','reset'=>true,'script'=>true),200);
					}else{
						
						return response(array('message'=>'Category updated successfully.','reset'=>false),200);
					
					}
					
				}
			}
			return response(array('message'=>'Data not found.'),403);
		}
		
		$category=Category::where([
									['status','1'],
									['recyclebin_status','0']
									])->orderBy('name','ASC')->get();
		$result=[];
        return view('admin.catalog.category.add',compact('category','result'));
    }
	
	public function categoryList(){

		$result=Category::where('recyclebin_status','0')->orderBy('id','DESC')->get();
		
		return view('admin.catalog.category.list',compact('result'));
	}
	
	public function updateCategory(Request $request,$id){
		
		$result=Category::find($id);
		
		if($result){
			
			$category=Category::where('recyclebin_status','0')->orderBy('name','ASC')->get();
			return view('admin.catalog.category.add',compact('category','result'));
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function deleteCategory(Request $request,$id){

		$result=Category::find($id);
		
		if($result){

			$childId=\App\Helpers\commonHelper::getAllCategoryTreeidsArray($id);

			if(!empty($childId)){

				foreach($childId as $child){

					Category::where('id',$child)->delete();
				}
			}

			Category::where('id',$id)->delete();
			
			return redirect()->back()->with('5fernsadminsuccess','Category deleted successfully.');
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function changeStatus(Request $request){
		
		Category::where('id',$request->post('id'))->update(['status'=>$request->post('status')]);
		
		$childCategoryResult=\App\Helpers\commonHelper::getCategoryTreeids($request->post('id'));
		
		if($childCategoryResult){
			
			foreach($childCategoryResult as $child){
				
				Category::where('id',$child)->update(['status'=>$request->post('status')]);
			}
			
		}
		
		return response(array('message'=>'Category status changed successfully.'),200);
	}
	
	public function selectTopCategory(Request $request){
		
		$checkParentCategory=Category::where('id',$request->post('id'))->where('parent_id',Null)->first();
		
		if(!$checkParentCategory){
			
			return response(array('message'=>"Yon can't make featured for this category becuase selected category is not parent."),403);
			
		}else{
			
			Category::where('id',$request->post('id'))->update(['top_category'=>$request->post('status')]);
			
			return response(array('message'=>'Top Category status changed successfully.'),200);
		}
	}
	
	public function MustCategory(Request $request){
		
		
			
			Category::where('id',$request->post('id'))->update(['must_try_status'=>$request->post('status')]);
			
			return response(array('message'=>'Must Category status changed successfully.'),200);
		
	}
	
	
		
	public function selectSubCategory(Request $request){

		$states = \DB::table('categories')->where('parent_id',$request->country)->where('status','1')->get();
        
		$output ='';
		if($states){

			$msg = "Select Sub Category";
			
			$output.= '<option value="">'.$msg.'</option>';

			foreach($states as $category){
				
				$cate = ucfirst($category->name);
				
				if($category->id == $request->subcategory){
				    $selected = 'selected';
				}else{
				    $selected = '';
				}
				$output.= '<option value="'.$category->id.'" '.$selected.'>'.$cate.'</option>';
			}

		}else{
			
			$msg = "Data Not Available";
			$output.= '<option value="">'.$msg.'</option>';
		}
		return Response($output);
	}
	
	

}
