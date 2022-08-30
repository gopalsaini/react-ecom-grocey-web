<style>
	.table-bordered thead tr th {
		background:#fff;
	}
</style>

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
								<div class="col-md-4"><b>Name</b> - {{ ucfirst($salesDetail->name) }}</div>
								<div class="col-md-4"><b>Mobile No.</b> - {{ $salesDetail->mobile }}</div>
								<div class="col-md-4"><b>Email-ID</b> - {{ $salesDetail->email }}</div> 
								<div class="col-md-4"><b>Shipping Address</b> - {{ $salesDetail->address_line1 }} {{ $salesDetail->address_line2 }}</div>
								<div class="col-md-4" style='padding-bottom:15px;'><b>Pincode</b> - {{ $salesDetail->pincode }}</div>
								<div class="col-md-4" style='padding-bottom:15px;'><b>Order Id</b> - {{ $salesDetail->order_id }}</div>
								<hr>
								<div class="col-md-4"><b>Sub Total</b> -  {{ \App\Helpers\commonHelper::getpriceIconByCountry($salesDetail->subtotal, $salesDetail->currency_id)}}</div>       
								<div class="col-md-4"><b>Discount</b> -  {{ \App\Helpers\commonHelper::getpriceIconByCountry($salesDetail->discount, $salesDetail->currency_id)}}</div>             
								<div class="col-md-4"><b>GST</b> -  {{ \App\Helpers\commonHelper::getpriceIconByCountry($salesDetail->gst, $salesDetail->currency_id)}}</div>             
								<div class="col-md-12"><b>Final Amount</b> -  {{ \App\Helpers\commonHelper::getpriceIconByCountry($salesDetail->nettotal, $salesDetail->currency_id)}}</div> 
							</div>
						</section>
								
						<table id="allUser" border="1" cellpadding="10" cellspacing="0" class="table table-striped table-bordered" style="width:100%;margin-top:20px;">
							<thead>
								<tr>
									<th>S.No</th>
									<th>Product Name</th>
									<th>Price</th>
									<th>Quantity</th>
									<th>Discount</th>
									<th>GST</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								@foreach($salesDetail['getsalesChildDetail'] as $key=>$sales)
									<tr> 
										<td>{{ $key+1 }}</td>
										<td>{{ ucfirst($sales->product_name) }}</td>
										<td>{{  \App\Helpers\commonHelper::getpriceIconByCountry($sales->price, $salesDetail->currency_id) }}</td>
										<td>{{  $sales->quantity }}</td>
										<td>{{  \App\Helpers\commonHelper::getpriceIconByCountry($sales->discount_amount, $salesDetail->currency_id) }}</td>
										<td>{{  \App\Helpers\commonHelper::getpriceIconByCountry($sales->gst_Amount, $salesDetail->currency_id) }}</td>
										<td>{{  \App\Helpers\commonHelper::getpriceIconByCountry($sales->net_total, $salesDetail->currency_id) }}</td>
									</tr> 
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
            </section>          
        </div>
    </div>
