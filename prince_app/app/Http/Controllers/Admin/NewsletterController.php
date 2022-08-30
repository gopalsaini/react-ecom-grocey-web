<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Mail;

class NewsletterController extends Controller{

    public function sendNewsLetter(Request $request) {
        
        if($request->isMethod('post')){
			
			$rules=[
				'emails'=>'required',
				'subject'=>'required',
				'message'=>'required'
			];
			
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
				
				try{

					return response(array('message'=>'Email Sent successfully'),200);

				}catch (\Exception $e){
			
					return response(array("message" => $e->getMessage()),403); 
				
				} finally {
					
					
					$tos = $request->post('emails');
					$content = $request->post('message');
					$subject = $request->post('subject');
					
					foreach($tos as $to){

						Mail::send('email_templates.newsletter',compact('content'), function($message) use($to,$subject){
							$message->to($to);
							$message->subject($subject);
							$message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
						}); 
					} 
					
				}
			}
			return response(array('message'=>'Data not found.'),403);
		}
        

		$result=\App\Models\Newsletter::get();

		return view('admin.newsletter.send',compact('result'));
	}
    
}
