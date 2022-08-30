<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use \App\Models\Seo;

class SeoController extends Controller
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

	
	
	public function homePage(Request $request){

        if($request->ajax()){

            $rules=[
				'id'=>'numeric|required|in:1',
                'meta_title'=>'required',
				'meta_keywords'=>'required',
                'meta_description'=>'required'
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
                        
                        $seo=Seo::find($request->post('id'));
                    }else{
                        
                        $seo=new Seo();
                    
                    }
                    
                    $seo->meta_title=$request->post('meta_title');
                    $seo->meta_keywords=$request->post('meta_keywords');
                    $seo->meta_description=$request->post('meta_description');
                    $seo->save();
                    
                    if((int) $request->post('id')>0){
                        
                        return response(array('message'=>'SEO Data updated successfully.','reset'=>false),200);
                    }

                }catch (\Exception $e){
            
                    return response(array("message" => $e->getMessage()),403); 
                
                }

            }
        }

        $result=Seo::where('id','1')->first();
        $type="Home";
		return view('admin.seo.seo_pages',compact('result','type'));
	}

}
