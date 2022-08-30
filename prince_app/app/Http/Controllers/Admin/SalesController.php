<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Sales_detail;
use Validator;
use Razorpay\Api\Api;
use Session;

class SalesController extends Controller
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

	
	
	public function salesList(Request $request,$type){

		if($type=='failed'){

			$query=Sales::select('sales.*')->where('sales_details.payment_status','1')->orderBy('sales.id','DESC')->join('sales_details','sales_details.sale_id','=','sales.id');
			$title='Failed Sales List';
			
		}else if($type=='pending'){

            
			$query=Sales::select('sales.*')->where('sales_details.order_status','1')->where('sales_details.payment_status','1')->orderBy('sales.id','DESC')->join('sales_details','sales_details.sale_id','=','sales.id');
			$title='Pending Sales List';
			
		}else if($type=='rejected'){
			
			$query=Sales::select('sales.*','sales_details.suborder_id')->where('sales_details.order_status','7')->orderBy('sales.id','DESC')->join('sales_details','sales_details.sale_id','=','sales.id');
			$title='Cancel By Admin Sales List';
			
		}else if($type=='confirmed'){

			$query=Sales::select('sales.*','sales_details.suborder_id','sales_details.waybill_no')->where('sales_details.order_status','2')->orderBy('sales.id','DESC')->join('sales_details','sales_details.sale_id','=','sales.id')->groupBy('sales_details.suborder_id');
			$title='Confirmed Sales List';
			
		}else if($type=='shipped'){
			
			$query=Sales::select('sales.*','sales_details.suborder_id','sales_details.waybill_no')->where('sales_details.order_status','10')->orderBy('sales.id','DESC')->join('sales_details','sales_details.sale_id','=','sales.id')->groupBy('sales_details.suborder_id');
			$title='Shipped Sales List';
			
		}else if($type=='delivered'){
			
			$query=Sales::select('sales.*','sales_details.suborder_id','sales_details.waybill_no')->where('sales_details.order_status','9')->orderBy('sales.id','DESC')->join('sales_details','sales_details.sale_id','=','sales.id')->groupBy('sales_details.suborder_id');
			$title='Delivered Sales List';
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. please try again.');
		}

// 		if($type!='failed'){

// 			$query->where('sales_details.payment_status','!=','1');
// 		}

		$result=$query->groupBy('sales_details.order_id')->get();
		
		return view('admin.sales.list',compact('result','title','type'));
	}
	
	public function getSalesDetail(Request $request){
		
		$rules = [
            'type' => 'required|in:approve,reject,view',
            'id' => 'required|numeric',
			'pageType'=>'required'
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
			
			$orderStatus=1;
			
			if($request->post('pageType')=='pending'){
				
				$orderStatus=1;
			}
			if($request->post('pageType')=='rejected'){
				
				$orderStatus=7;
			}
			if($request->post('pageType')=='confirmed'){
				
				$orderStatus=2;
			}
			if($request->post('pageType')=='shipped'){
				
				$orderStatus=10;
			}
			if($request->post('pageType')=='delivered'){
				
				$orderStatus=9;
			}
			
			$salesDetail=Sales_detail::Select('sales_details.*','products.id as ProductId')->join('products','products.id','=','sales_details.product_id')->where('sale_id',$request->post('id'))->where('order_status',$orderStatus)->get();
			
			if($salesDetail->count()==0){
			
				return response(array('message'=>'Orders not found.'),404);
				
			}else{
				
				$sales=Sales::where('id',$request->post('id'))->first();
				$type=$request->post('type');
				$pageType=$request->post('pageType');
				$orderId=$salesDetail[0]->order_id;
				$html=view('admin.sales.sales_detail_modal',compact('salesDetail','sales','type','pageType','orderId'))->render();
				
				return response(array('message'=>'Product detail fetched successfully.','html'=>$html),200);
			}
		}

	}
	
	public function updateOrderStatus(Request $request){
		
		$rules = [
            'type' => 'required|in:approve,reject',
            'saleids' => 'required',
			'order_id'=>'required|exists:sales,order_id'
		];

		if($request->post('type')=='reject'){

			$rules['refund_amount']='required';
		}

		$validator = Validator::make($request->all(), $rules);
		 
		if ($validator->fails()) {

			$message = [];
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return redirect('admin/sales/list/pending')->with('5fernsadminerror',$message);
			
		}else{
			
			$salesIds=$request->post('saleids');
			
			$salesResult=\App\Models\Sales::where('order_id',$request->post('order_id'))->first();

			if(!empty($salesIds) && $salesIds[0]!='' && $salesResult){
				
				// try{

					$salesResult->total_created_order=($salesResult->total_created_order+1);
					$salesResult->save();

					$orderStatus=1;

					if($request->post('type')=='approve'){
						
						$orderStatus=2;
					}

					if($request->post('type')=='reject'){
						
						$orderStatus=7;

					}


					foreach($salesIds as $key=>$sale){

						$suborderId=$salesResult->order_id.'_'.$salesResult->total_created_order;

						if($request->post('type')=='reject'){

							$refundAmount=$request->post('refund_amount')[$key];

							$transactionDetail=\App\Models\Transaction::where('order_id',$request->post('order_id'))->first();

							if($transactionDetail && isset($request->post('refund_amount')[$key])){

								$api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));

								$transactionId=strtotime("now").rand(11,99);

								$order = $api->refund->create(array(
										'payment_id' => $transactionDetail->razorpay_paymentid,
										'amount' => $refundAmount * 100,
										"notes"=>[
											"merchant_transactionid"=>$transactionId,
											"sales_id"=>$sale
										]
									)
								);

								if($order->status=='processed'){

									Sales_detail::where('id',$sale)->where('order_status','1')->update(['order_status'=>$orderStatus,'payment_status'=>'3','refund_amount'=>$refundAmount,'suborder_id'=>$suborderId]);
		
									$payment=new \App\Models\Transaction();
									$payment->user_id=$transactionDetail->user_id;
									$payment->order_id=$transactionDetail->order_id;
									$payment->razorpay_order_id=$transactionDetail->razorpay_order_id;
									$payment->razorpay_paymentid=$transactionDetail->razorpay_paymentid;
									$payment->transaction_id=$transactionId;
									$payment->amount=$refundAmount;
									$payment->description='Amount Refund';
									$payment->payment_status='3';
									$payment->save();

									// send Msg
									$content="https://bulksmsapi.vispl.in/?username=fiveotp&password=five_1234&messageType=text&mobile=".$salesResult['phone_code'].$salesResult['mobile']."&senderId=FFERNS&ContentID=1707163894952079118&EntityID=1701163375929957226&message=Refund issued: Refund for order ID ".$salesResult['order_id'].", suborderID - ".$suborderId." has been processed.  It will take 5-7 days to process the refund to the original account from which payment was made. For any questions regarding this, you can reach out to our helpline - +91-7302036153 or write to us at ordersupport@fiveferns.in. - Team FiveFerns";
									\App\Helpers\commonHelper::sendMsg($content);

								}

							}else{

								return redirect('admin/sales/list/pending')->with('5fernsadminerror','Someting went wrong. Please try again');
							}

						}else{

                           
                            $sales_detail = Sales_detail::where('id',$sale)->where('order_status','1')->first();
							$sales_detail->is_approve = '1';
							$sales_detail->order_status = $orderStatus;
							$sales_detail->suborder_id = $suborderId;
							$sales_detail->save();
						}
						
					}

					if($request->post('type')=='approve'){

						
				// 		$msg="Order Confirmed: Thank you for Shopping on FiveFerns. Your Order with Order ID ".$salesResult['order_id'].", suborderID - ".$suborderId." is confirmed. We will notify you as soon as the order is shipped.";
				// 		$content="https://bulksmsapi.vispl.in/?username=fiveotp&password=five_1234&messageType=text&mobile=".$salesResult['phone_code'].$salesResult['mobile']."&senderId=FFERNS&ContentID=1707163894945368248&EntityID=1701163375929957226&message=".$msg."- Team FiveFerns";
				// 		\App\Helpers\commonHelper::sendMsg($content);

				// 		//send Mail
				// 		$orderResult=\App\Models\Sales::with(['getsalesdetailchild'=>function($query) use($suborderId){
				// 			$query->where('suborder_id',$suborderId);
				// 			$query->where('is_approve','1');
				// 		}])->where('sales.order_id',$salesResult['order_id'])->first()->toArray();


				// 		\Mail::send('email_templates.order_confirm', compact('orderResult','msg'), function($message) use ($orderResult){

				// 			$message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
				// 			$message->subject('Order is ready for shipping');
				// 			$message->to($orderResult['email']);
				// 		});

					}
					
					return redirect('admin/sales/list/pending')->with('5fernsadminsuccess','Order status changed successfully.');

				// }catch(\Exception $e){
					
				// 	return redirect('admin/sales/list/pending')->with('5fernsadminerror',$e->getMessage());
				// }
				
			}else{
				
				return redirect('admin/sales/list/pending')->with('5fernsadminerror','Something went wrong. Please try again.');
			}
			
		}
		
	}

	public function createOrderOnDelhivery($delhivery){

		$data='format=json&data={
		   "pickup_location": {
		   "pin": "248007",
		   "add": "Kotra Santor Dehradun premnagar, Dehradun, Uttarakhand, 248007, India.",
		   "phone": "9410396153",
		   "state": "Uttarakhand",
		   "city": "Dehradun",
		   "country": "India",
		   "name": "SF FIVE 0106195"
			},
			"shipments": [{
		   "return_name": "SF FIVE 0106195",
		   "return_pin": "248007",
		   "return_city": "Dehradun",
		   "return_phone": "9410396153",
		   "return_add": "Kotra Santor Dehradun premnagar, Dehradun, Uttarakhand, 248007, India.",
		   "return_state": "Uttarakhand",
		   "return_country": "India",
		   "order": "'.$delhivery['order_id'].'",
		   "phone": "'.$delhivery['shipping_mobile'].'",
		   "products_desc": "'.implode(',',$delhivery['product_name']).'",
		   "cod_amount": "'.$delhivery['amount'].'",
		   "name": "'.$delhivery['shipping_name'].'",
		   "country": "'.$delhivery['shipping_country'].'",
		   "seller_inv_date": "",
		   "order_date": "'.date('Y-m-d H:i:s').'",
		   "total_amount": "'.$delhivery['amount'].'",
		   "seller_add": "",
		   "seller_cst": "",
		   "add": "'.$delhivery['shipping_address'].'",
		   "seller_gst_tin": "05AMIPT1901F1ZB",
		   "hsn_code": "100820",
		   "consignee_gst_tin": "05AMIPT1901F1ZB",
		   "seller_name": "",
		   "seller_inv": "",
		   "seller_tin": "",
		   "pin": "'.$delhivery['shipping_pincode'].'",
		   "quantity": "1",
		   "payment_mode": "Prepaid",
		   "state": "Uttarakhand",
		   "city": "Dehradun",
		   "client": "SF FIVE 0106195"
			}]
		   }';
		
		   $url= 'https://track.delhivery.com/api/cmu/create.json';
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
			// OPTIONS:
			 curl_setopt($curl, CURLOPT_URL, $url);
			 curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Authorization: Token fac9a0852713fb923b9f74d58a32834d7772c81d',
			'Content-Type: application/json',
			 ));
			 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			 curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		  
			 // EXECUTE:
			 $result = curl_exec($curl);
   
			 if(!$result){die("Connection Failure");}
			 curl_close($curl);
   
		   $result = json_decode($result,true);

		   if(!empty($result) && !empty($result['packages'])){

				foreach($delhivery as $saleId){

					Sales_detail::where('suborder_id',$result['packages'][0]['refnum'])->update(['waybill_no'=>$result['packages'][0]['waybill']]);
				}
		   }

	}

	public function downloadPackagingSlip(Request $request,$id){

		$url="https://track.delhivery.com/api/p/packing_slip?wbns=$id";
		$curl = curl_init();
		// OPTIONS:
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Authorization: Token fac9a0852713fb923b9f74d58a32834d7772c81d',
			'Content-Type: application/json',
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	
		// EXECUTE:
		$slip = json_decode(curl_exec($curl),true);
		
		curl_close($curl);

		if(!empty($slip['packages'])){

			return view('admin.sales.packing_slip',compact('slip'));

		}else{

			return redirect('admin/sales/list/confirmed')->with('5fernsadminerror','Something went wrong. Please try again.');

		}
		
	}


	public function orderInvoice(Request $request,$id){

		$result=\App\Models\Sales_detail::select('sales.name','sales.address_line1','sales.address_line2','sales.city_id','sales.pincode','sales_details.*')->where('sales.id',$id)->where('sales_details.is_approve','1')->join('sales','sales_details.sale_id','=','sales.id')->get();

		if($result->count()==0){

			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');

		}else{

			return view('admin.sales.order_invoice',compact('result'));

		}
		
	}
	
	
	public function ondemandEnquiry(Request $request){

		$result=\App\Models\OndemandEnquiry::select('ondemand_enquiries.*','variantproducts.slug','products.name as pname')
		->join('variantproducts', 'ondemand_enquiries.product_id', '=', 'variantproducts.id')
		->join('products', 'variantproducts.product_id', '=', 'products.id')
		->get();

		return view('admin.sales.enquery',compact('result'));
		
	}


	public function orderReady(Request $request){

		$result=\App\Models\Sales::with(['getsalesdetailchild'=>function($query) use($request){
			$query->where('suborder_id',$request->post('suborder_id'));
			$query->where('is_approve','1');
		}])->where('sales.id',$request->post('sale_id'))->first()->toArray();

		if(!empty($result) && empty($result['getsalesdetailchild'])){

			return response(array('message'=>"Something went wrong. Please try again."),404);

		}else{

			
			$salesIds=$request->post('sale_id');

			$salesResult=\App\Models\Sales::where('id',$request->post('sale_id'))->first();
			
			if(!empty($salesIds) && $salesIds[0]!='' && $salesResult){

				if($request->post('type') == 'shipped'){
					$order_status = '10';
					$subject = 'Order Shipped';
					Sales_detail::where('sale_id',$salesResult->id)->where('suborder_id',$request->post('suborder_id'))->where('order_status','2')->update(['order_status'=>$order_status,'is_approve'=>'1']);

				}elseif($request->post('type') == 'delivered'){ 

					$order_status = '9';
					$subject = 'Order Delivered';

					Sales_detail::where('sale_id',$salesResult->id)->where('suborder_id',$request->post('suborder_id'))->where('order_status','10')->update(['order_status'=>$order_status,'is_approve'=>'1']);

				} 	

			}else{

				return response(array('message'=>'Something went wrong. Please try again.'),200);
			}
			
			return response(array('message'=>"Order ready message sent successfully."),200);

		}

	}

	public function createMannualOrder(Request $request){

		if($request->ajax()){

			$rules['name']='required';
			$rules['email']='email|required';
			$rules['phone_code']='required|numeric';
			$rules['mobile']='required';
			$rules['country_id']='required|numeric';
			$rules['state_id']='required|numeric';
			$rules['city_id']='required|numeric';
			$rules['currency_id']='required|numeric';
			$rules['address_line1']='required|string';

			if($request->post('country_id')=='101'){

				$rules['pincode']="required|digits:6";
	
			}else{
	
				$rules['pincode']="required|digits:5";
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
 
				$totalProduct=count($_POST['product_name']);

				if(count($_POST['product_price'])==$totalProduct && count($_POST['product_qty'])==$totalProduct && count($_POST['product_discount'])==$totalProduct && count($_POST['product_gst'])==$totalProduct){

					$mannualOrder=new \App\Models\Mannual_order();

					$mannualOrder->order_id=rand(1111,9999);
					$mannualOrder->currency_id=$request->post('currency_id');
					$mannualOrder->name=$request->post('name');
					$mannualOrder->phone_code=$request->post('phone_code');
					$mannualOrder->mobile=$request->post('mobile');
					$mannualOrder->email=$request->post('email');
					$mannualOrder->address_line1=$request->post('address_line1');
					$mannualOrder->address_line2=$request->post('address_line2');
					$mannualOrder->pincode=$request->post('pincode');
					$mannualOrder->country_id=$request->post('country_id');
					$mannualOrder->country_id=$request->post('country_id');
					$mannualOrder->city_id=$request->post('city_id');
					$mannualOrder->save();

					$totalSubtotal=0;$totalDiscount=0;$totalGst=0;$totalNetTotal=0;
					
					for($i=0;$i<$totalProduct;$i++){

						$mannualOrderDetail=new \App\Models\Mannualorder_detail();
						$mannualOrderDetail->parent_id=$mannualOrder->id;
						$mannualOrderDetail->order_id=$mannualOrder->order_id;
						$mannualOrderDetail->product_name=$_POST['product_name'][$i];
						$mannualOrderDetail->price=$_POST['product_price'][$i];
						$mannualOrderDetail->quantity=$_POST['product_qty'][$i];
						$mannualOrderDetail->subtotal=($_POST['product_price'][$i]*$_POST['product_qty'][$i]);

						//Product Discount
						$productDiscount=round((($mannualOrderDetail->subtotal*$_POST['product_discount'][$i])/100),2);
	
						//GST Discount
						$gstAmount=round((($mannualOrderDetail->subtotal*$_POST['product_gst'][$i])/100),2);

						$mannualOrderDetail->discount_ratio=$_POST['product_gst'][$i];
						$mannualOrderDetail->discount_amount=$productDiscount;
						$mannualOrderDetail->gst_ratio=$_POST['product_gst'][$i];
						$mannualOrderDetail->gst_amount=$gstAmount;
						$mannualOrderDetail->net_total=($mannualOrderDetail->subtotal-$productDiscount+$gstAmount);
						$mannualOrderDetail->save();

						$totalSubtotal+=$mannualOrderDetail->subtotal;
						$totalDiscount+=$productDiscount;
						$totalGst+=$gstAmount;
						$totalNetTotal+=$mannualOrderDetail->net_total;

					}

					$mannualOrder->subtotal=$totalSubtotal;
					$mannualOrder->discount=$totalDiscount;
					$mannualOrder->gst=$totalGst;
					$mannualOrder->nettotal=$totalNetTotal;
					$mannualOrder->save();
	
					return response(array('message'=>'Order created successfully.','script'=>true),200);

				}else{

					return response(array('message'=>'Something went wrong. Please try again.'),403);
				}

			}
			
		}

		$country=\App\Models\Country::select('id','name','phonecode')->get();
		$currency=\App\Models\Currency_value::get();

		return view('admin.sales.mannual_orders.create',compact('country','currency'));
	}

	public function mannualOrdersList(Request $request){

		$result=\App\Models\Mannual_order::orderBy('id','desc')->get();

		return view('admin.sales.mannual_orders.list',compact('result'));
	}

	public function getMannualSalesDetail(Request $request){
		
		$rules = [
            'id' => 'required|numeric'
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
			 
			$salesDetail=\App\Models\Mannual_order::with('getsalesChildDetail')->where('id',$request->post('id'))->first();
														 	
			if($salesDetail){
			
				return response(array('message'=>'Orders not found.'),404);
				
			}else{
				
				$html=view('admin.sales.mannual_orders.sales_detail_modal',compact('salesDetail'))->render();
				
				return response(array('message'=>'Product detail fetched successfully.','html'=>$html),200);
			}
		}

	}

	public function mannualOrderInvoice(Request $request,$id){

		$result=\App\Models\Mannual_order::with('getsalesChildDetail')->where('id',$id)->first();

		if($result->count()>0){

			return view('admin.sales.mannual_orders.order_invoice',compact('result'));

		}else{

			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}

}
