<style>
	.table-bordered thead tr th {
		background:#fff;
	}
</style>
	
<form action="{{ url('admin/sales/update-orderstatus')}}" method="POST" onsubmit="return validate()">
	@csrf 
	<input type="hidden" name="type" value="{{ $type }}" required />
	<input type="hidden" name="order_id" value="{{ $orderId }}" required />
	
	<div class="row">
         <div class="col-lg-12">
            <!-- Radio buttons and checkbox -->
            <section class="panel panel-default">
                <div class="panel-heading" style="padding: 5px 0 15px;"><strong>
					<i class="fa fa-th"></i> View Sale Detail</strong>
				</div>
 
				<div class="panel-body">
					<div class="table-responsive">
						<section class="panel panel-default">
							<div class="panel-heading row" style="border: 1px solid #333;padding: 10px 15px;margin:0;">
								<div class="col-md-4"><b>Name</b> - {{ ucfirst($sales->name) }}</div>
								<div class="col-md-4"><b>Mobile No.</b> - {{ $sales->mobile }}</div>
								<div class="col-md-4"><b>Email-ID</b> - {{ $sales->email }}</div> 
								<div class="col-md-4"><b>Shipping Address</b> - {{ $sales->address_line1 }} {{ $sales->address_line2 }}</div>
								<div class="col-md-4" style='padding-bottom:15px;'><b>Pincode</b> - {{ $sales->pincode }}</div>
								<div class="col-md-4" style='padding-bottom:15px;'><b>Order Id</b> - {{ $sales->order_id }}</div>
								<hr>
								<div class="col-md-4"><b>Sub Total</b> -  {{ $sales->subtotal}}</div>       
								<div class="col-md-4"><b>Discount</b> -  {{ $sales->discount}}</div>       
								<div class="col-md-4"><b>Shipping</b> -  {{ $sales->shipping}}</div>       
								<div class="col-md-4"><b>Coupon Code Discount</b> -  {{ $sales->couponcode_amount}}</div>       
								<div class="col-md-12"><b>Final Amount</b> -  {{ $sales->net_amount}}</div> 
							</div>
						</section>
								
						<table id="" border="1" cellpadding="10" cellspacing="0" class="table table-striped table-bordered" style="width:100%;margin-top:20px;">
							<thead>
								<tr>
									@if($pageType=='pending') <th><input type="checkbox" id="select_all_h"/></th> @endif
									<th>Image</th>
									<th>Product Name</th>
									<th>Quantity</th>
									<th>Price</th>
									<th>Payment Status</th>
									@if($type=='reject')
									<th>Refund Amount</th>
									@endif
								</tr>
							</thead>
							<tbody>
								@foreach($salesDetail as $key=>$sales)
									<tr>
										@if($pageType=='pending')
										<td><input data-key="{{$key}}" type="checkbox" class="checkboxh refund" name="saleids[]" value="{{ $sales->id }}"/></td>@endif
										<td><img style="height:100px" src="{{ $sales->product_image }}"></td>
										<td>{{ ucfirst($sales->product_name) }}<br>

											@php echo \App\Helpers\commonHelper::getVaraintName($sales->variant_id,$sales->variant_attributes) @endphp
	
											@if($sales->custom_remark)
												<p>{{ $sales->custom_remark }}</p>
											@endif

										</td>
										<td>{{ $sales->qty }}</td>
										<td>{{  $sales->amount }}</td>
										<td>
											@if($sales->payment_status=='0' || $sales->payment_status=='1' || $sales->payment_status=='7' || $sales->payment_status=='8')
												<div class="badge col-red">Pending</div>
											@elseif($sales->payment_status=='3' || $sales->payment_status=='4' || $sales->payment_status=='6')
												<div class="badge col-orange">{{ \App\Helpers\commonHelper::getPaymentStatusName($sales->payment_status) }}</div>
											@elseif($sales->payment_status=='2' || $sales->payment_status=='5')
												<div class="badge col-green">{{ \App\Helpers\commonHelper::getPaymentStatusName($sales->payment_status) }}</div>
											@endif
										</td>
										@if($type=='reject')
										<td>
											<input type="text" class="form-control" placeholder="Enter Refund Amount" id="refund_amount{{$key}}" onkeypress="return /[0-9 ]/i.test(event.key)" name="refund_amount[]" />
										</td>
										@endif
									</tr> 
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
            </section>          
        </div>
    </div>
	@if($pageType=='pending')
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-hidden="true">Close</button>
			<button type="submit" class="btn btn-primary">Save changes</button>
		</div>
	@else
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-hidden="true">Close</button>
		</div>
	@endif
</form>

<script>
	$(document).ready(function(){
		$('#select_all_h').on('click',function(){
			if(this.checked){
				$('.checkboxh').each(function(){
					this.checked = true;
				});
			}else{
				$('.checkboxh').each(function(){
					this.checked = false;
				});
			}
		});
	
		$('.checkboxh').on('click',function(){
			if($('.checkboxh:checked').length == $('.checkboxh').length){
				$('#select_all_h').prop('checked',true);
			}else{
				$('#select_all_h').prop('checked',false);
			}
		});

		$('.refund').change(function(){
		
			if($(this).is(":checked")){

				$('#refund_amount'+$(this).data('key')).prop('required',true);

			}else{

				$('#refund_amount'+$(this).data('key')).prop('required',false);
			}
		});
	});

	function validate(){

		$('#preloader').css('display','block');

	}
</script>