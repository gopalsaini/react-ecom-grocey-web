<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function add(Request $request){
			
		if($request->isMethod('post')){
			
			$rules=[
				'id'=>'numeric|required',
				'category_id'=>'numeric|required',
				'title'=>'required|unique:blogs,title,'.$request->post('id'),
				'short_desc'=>'required',
				'description'=>'required'
			];
			
			if((int) $request->post('id')==0){
						
				$rules['image']='required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
			}
			
			$validator = \Validator::make($request->all(), $rules);
			
			if ($validator->fails()){
				$message = "";
				$messages_l = json_decode(json_encode($validator->messages()), true);
				foreach ($messages_l as $msg) {
					$message= $msg[0];
					break;
				}
				
				return response(array('message'=>$message),403);
				
			}else{
            
                if((int) $request->post('id')>0){

                    $Blog=\App\Models\Blog::find($request->post('id'));

                }else{
                    
                    $Blog=new \App\Models\Blog();
                
                }

                if($request->hasFile('image')){
                    $imageData = $request->file('image');
                    $image = strtotime(date('Y-m-d H:i:s')).'.'.$imageData->getClientOriginalExtension();
                    $destinationPath = public_path('/uploads/blog');
					$img = \Image::make($imageData->getRealPath());
                    $img->save($destinationPath.'/'.$image,60);

                    $Blog->image=asset('/uploads/blog/'.$image);
                } 
                $Blog->category_id=$request->category_id;
                $Blog->title=$request->title;
                $Blog->short_desc=$request->short_desc;
                $Blog->description=$request->description;
                $Blog->slug=\Str::slug($request->title);
                $Blog->save();
                
                if((int) $request->post('id')==0){
                    
                    return response(array('message'=>'Blog added successfully.','reset'=>true),200);
                }else{
                    
                    return response(array('message'=>'Blog updated successfully.','reset'=>false),200);
                
                }

			}
			return response(array('message'=>'Data not found.'),403);
		}

		$category=\App\Models\BlogCategory::orderBy('name','ASC')->get();

		$dataarray = array(); 

		$dataarray['0'] = "-- Select --";
		 
		if($category){

			foreach($category as $key => $par){

				$dataarray[$par->id] = $par->name;
			}
		}
		$result=[];
        return view('admin.blog.add',compact('result','dataarray'));
    }



    public function blogList(Request $request){
		
		if ($request->ajax()) {
			
			$columns = \Schema::getColumnListing('blogs');
			
			$limit = $request->input('length');
			$start = $request->input('start');
			$order = $columns[$request->input('order.0.column')];
			$dir = $request->input('order.0.dir');

			$query=\App\Models\Blog::orderBy($order,$dir);

			$blog=$query->offset($start)->limit($limit)->get();
			
			$totalData = \App\Models\Blog::count();
			$totalFiltered=$query->count();

			$draw = intval($request->input('draw'));  
			$recordsTotal = intval($totalData);
			$recordsFiltered = intval($totalFiltered);

			return \DataTables::of($blog)
			->setOffset($start)
			->addColumn('title', function($blog){
				return $blog->title;
		    })
			->addColumn('category', function($blog){
				return \App\Helpers\commonHelper::getCategoryNameById($blog->category_id);
		    })
			
			->addColumn('image', function($blog){
				return '<img src="'.$blog->image.'" style="width: 51px;"/>';
		    })
			
			->addColumn('status', function($blog){
				if($blog->status=='1'){ 
					$checked = "checked"; 
				}else{
					$checked = " "; 
				}

				return $status = '<div class="switch mt-3">
											<label>
												<input type="checkbox" class="-change" data-id="'.$blog->id.'" '.$checked.'>
											<span class="lever switch-col-red layout-switch"></span>
											</label>
										</div>';
		    })
			->addColumn('action', function($blog){
				$msg = "' Are you sure to delete this blog?'";
				return $action = '<a href="'.route('admin.blog.update', ['id' => $blog->id] ).'" title="Update blog" class="btn btn-tbl-edit"><i class="fas fa-pencil-alt"></i></a>
				<a href="'.route('admin.blog.delete', ['id' => $blog->id] ).'" title="Delete blog" class="btn btn-tbl-delete" onclick="return confirm('.$msg.');"><i class="fas fa-times"></i></a>';
					
		    })  
			 
		    ->escapeColumns([])	
			->setTotalRecords($totalData)
			->with('draw','recordsTotal','recordsFiltered')   
		    ->make(true);
 
        }

        return view('admin.blog.list');
	}

    public function blogStatus(Request $request){
		
		\App\Models\Blog::where('id',$request->post('id'))->update(['status'=>$request->post('status')]);
		
		return response(array('message'=>'Blog status changed successfully.'),200);
	}

    
    public function blogDelete(Request $request, $id)
    {
		$checkResult=\App\Models\Blog::find($id);
		
		if($checkResult){

			\App\Models\Blog::where('id', $id)->delete();
			$request->session()->flash('success','Blog deleted successfully.');
		}else{
			$request->session()->flash('error','Something went wrong. Please try again.');
		}
		
		return redirect()->route('admin.blog.list');

    }

    public function blogUpdate($id){
		
		$result=\App\Models\Blog::find($id);

		if(!$result){

			$request->session()->flash('error','Something went wrong.please try again.');
			return redirect()->back();
		}

		$category=\App\Models\BlogCategory::orderBy('name','ASC')->get();

		$dataarray = array(); 

		$dataarray['0'] = "-- Select --";
		 
		if($category){

			foreach($category as $key => $par){

				$dataarray[$par->id] = $par->name;
			}
		}
		return view('admin.blog.add')->with(compact('result','dataarray'));
	}
	
	
}
