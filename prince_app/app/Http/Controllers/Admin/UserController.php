<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sales;
use App\Models\Sales_detail;
use Validator;
use Hash;

class UserController extends Controller
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

	
	public function updateUser(Request $request,$id){
		
		
		$data=\App\Models\User::where('id',$id)
									->where('user_type','2')->first();
								
		if($data){

			return view('admin.user.add',compact('id','data'));
		
		}else{
			
			$request->session()->flash('5fernsadminerror','Something went wrong. Please try again.');
		}
		 
		return redirect()->back();
		
	}
	
	public function updateUserEnd(Request $request){
	
		$rules = [
            'name' => 'required|max:55|string|min:3'
		];
		
		$validator = Validator::make($request->all(), $rules);
		 
		if ($validator->fails()) {
			$message = [];
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('message'=>$message),403);
			
		}else{
			
			try{
				
				//chk unique Mobile
				$mobileResult=\App\Models\User::where([
										['mobile','=',$request->post('mobile')],
										['id','!=',$request->post('id')],
										])->first();
										
				if($mobileResult && $request->post('mobile')!=''){
					
					return response(array('message'=>'Mobile no is already exist with us. Please try another mobile no.'),403);
				
				}else{
			
					$user=User::find($request->post('id'));
					$user->name=$request->post('name');
					
					if($request->post('mobile')){
						
						$user->mobile=$request->post('mobile');
					}
					
					$user->save();
					
					return response(array('message'=>'User updated successfully.'),200);
					
				}
				
			}catch (\Exception $e){
				
				return response(array("error" 
						=> true, "message" => $e->getMessage()),403); 
			
			}
			
		}
	}
	
	public function userList(){
		
		$result=User::where('user_type','2')->orderBy('id','DESC')->get();

		return view('admin.user.list',compact('result'));
	}
	
	public function blockUser(Request $request){
		
		User::where('id',$request->post('id'))->update(['block_user'=>$request->post('status')]);
		
		if($request->post('status')=='1'){
			
			return response(array('message'=>'User Blocked successfully.'),200);
		}else{
			
			return response(array('message'=>'User unblocked successfully.'),200);
		}
		
	}
	
	public function addAddress(Request $request){
	
		$rules = [
            'id' => 'required|numeric',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'pincode' => 'required|numeric',
            'type' => 'required|numeric|numeric:1,2',
            'address_line1' => 'required',
		];
		
		$validator = Validator::make($request->all(), $rules);
		 
		if ($validator->fails()) {
			$message = [];
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('message'=>$message),403);
			
		}else{
			
			try{
				
				if((int) $request->post('id')>0){
						
					$address=\App\Models\Addressbook::find($request->post('id'));
					
				}else{
					
					$address=new \App\Models\Addressbook();
				
				}
				
					$address->state_id=$request->post('state_id');
					$address->city_id=$request->post('city_id');
					$address->pincode=$request->post('pincode');
					$address->type=$request->post('type');
					$address->address_line1=$request->post('address_line1');
					$address->address_line2=$request->post('address_line2');
					
					$address->save();
					
					if((int) $request->post('id')==0){
						
						return response(array('message'=>'Address added successfully.','reset'=>true),200);
					}else{
						
						return response(array('message'=>'Address updated successfully.','reset'=>false),200);
					
					}
				
			}catch (\Exception $e){
				
				return response(array("error" 
						=> true, "message" => $e->getMessage()),403); 
			
			}
			
		}
	}
	
	public function addressBookList(Request $request,$userId){
		
		$user=User::where('id',$userId)->where('user_type','2')->first();
		
		if($user){
			
			$result=\App\Models\Addressbook::where('user_id',$userId)->where('recyclebin_status','0')->get();
			return view('admin.user.address-book',compact('result','user'));
			
		}else{
			
			return redirect('admin/user/list')->with('5fernsadminerror',"Something went wrong. Please try again");
		}
		
	}
	
	public function deleteAddress(Request $request,$id){
		
		$address=\App\Models\Addressbook::where('id',$id)->find();
		
		if($address){
			
			$address->recyclebin_status='1';
			$address->recyclebin_datetime=date('Y-m-d H:i:s');
			$address->save();
				
			return redirect()->back()->with('5fernsadminsuccess',"Address deleted successfully.");
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror',"Something went wrong. Please try again");
		}
	}
	
	public function updateAddress(Request $request,$userId,$id){
		
		$result=\App\Models\Addressbook::where('id',$id)->where('user_id',$userId)->first();
		
		if($result){
			
			$user=User::where('id',$userId)->first();
			
			$countryId=\App\Helpers\commonHelper::getCountryidByStateId($result->state_id);
			
			$countries=\App\Models\Country::get();

			return view('admin.user.add_useraddress',compact('result','user','countries','countryId'));
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror',"Something went wrong. Please try again");
		}
	}
	
	public function getStateByCountryId(Request $request){
		
		$rules = [
            'country_id' => 'required|numeric',
			'selected_id'=>'required|numeric'
		];
		
		$validator = Validator::make($request->all(), $rules);
		 
		if ($validator->fails()) {
			$message = [];
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('message'=>$message),403);
			
		}else{
			
			try{
				$selectedId=$request->post('selected_id');
				
				$result=\App\Models\State::where('country_id',$request->post('country_id'))->get();
				
				$html='<option value="">--State--</option>';
				
				foreach($result as $value){
					
					$html.='<option value="'.$value['id'].'" '; if($selectedId==$value['id']){ $html.='selected'; } $html.='>'.ucfirst($value['name']).'</option>';
				}
				
				return response(array('message'=>'State fetched successfully.','result'=>$html),200);
				
			}catch (\Exception $e){
				
				return response(array("error" 
						=> true, "message" => $e->getMessage()),403); 
			
			}
		}
		
	}
	
	public function getCityByStateId(Request $request){
		
		$rules = [
            'state_id' => 'required|numeric',
			'selected_id'=>'required|numeric'
		];
		
		$validator = Validator::make($request->all(), $rules);
		 
		if ($validator->fails()) {
			$message = [];
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('message'=>$message),403);
			
		}else{
			
			try{
				
				$selectedId=$request->post('selected_id');
				
				$result=\App\Models\City::where('state_id',$request->post('state_id'))->get();
				
				$html='<option value="">--City--</option>';
				
				foreach($result as $value){
					
					$html.='<option value="'.$value['id'].'" '; if($selectedId==$value['id']){ $html.='selected'; } $html.='>'.ucfirst($value['name']).'</option>';
				}
				
				return response(array('message'=>'City fetched successfully.','result'=>$html),200);
				
			}catch (\Exception $e){
				
				return response(array("error" 
						=> true, "message" => $e->getMessage()),403); 
			
			}
		}
		
	}

	public function addSubAdmin(Request $request){

		if($request->isMethod('post')){
			
			$rules=[
				'id'=>'numeric|required',
				'name'=>'required',
				'email' => 'unique:users,email,' . $request->post('id'),
				'designation_id'=>'required|in:1,2,3,4'
			];

			if(($request->post('id') ==0)){

				$rules['password']='required';
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
				
				if((int) $request->post('id')>0){
					
					$user=User::find($request->post('id'));

				}else{
					
					$user=new User();
				
				}
				
				$user->name=$request->post('name');
				$user->email=$request->post('email');
				$user->reg_type='admin';
				$user->user_type='1';
				$user->status='1';
				$user->designation_id=$request->post('designation_id');

				if($request->post('password')){

					$user->password=Hash::make($request->post('password'));
				}
				
				$user->save();
				
				if((int) $request->post('id')==0){
					
					return response(array('message'=>'Subadmin Created successfully.','reset'=>true),200);
				}else{
					
					return response(array('message'=>'Subadmin updated successfully.','reset'=>false),200);
				
				} 
			}
			return response(array('message'=>'Data not found.'),403);
		}
		
		$result=[];
        return view('admin.user.subadminadd',compact('result'));
    }
	
	public function subadminList(){

		$result=User::where([
					['user_type','1'],
					['id','>','1'],
					['id','!=',\Auth::user()->id]
				])->orderBy('id','DESC')->get();
		
		return view('admin.user.subadminlist',compact('result'));
	}
	
	public function updateSubAdmin(Request $request,$id){
		
		$result=User::where([
			['user_type','1'],
			['id','>','1'],
			['id','!=',\Auth::user()->id],
			['id',$id]
		])->first();
		
		if($result){
			 
			return view('admin.user.subadminadd',compact('result'));
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function deleteSubAdmin(Request $request,$id){
		
		$result=Testimonial::find($id);
		
		if($result){
			
			Testimonial::where('id',$id)->update(['recyclebin_status'=>'1','recyclebin_datetime'=>date('Y-m-d H:i:s')]);
			
			return redirect()->back()->with('5fernsadminsuccess','Testimonial deleted successfully.');
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function changeStatus(Request $request){
		
		User::where('id',$request->post('id'))->update(['status'=>$request->post('status')]);

		return response(array('message'=>'User status changed successfully.'),200);
	}

	
	public function viewOrder(Request $request,$id){
		
		$result = Sales_detail::orderBy('id','DESC')
			->where('user_id',$id)
			->get();

		
		return view('admin.user.order_list',compact('result'));
	}
	
	

}
