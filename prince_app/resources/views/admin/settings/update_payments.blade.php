@extends('layouts/master')

@section('title')
	Update Price Values
@endsection


@section('content')

<section class="content">
	<div class="container-fluid">
		
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="card">
					<div class="header">
						<h2><i class="fa fa-th"></i> Update Price Values </h2>
					</div>
					<div class="body">
						<form id="form" action="{{ url('admin/settings/update-price') }}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf

							<div class="row clearfix">

								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label for="inputName">Delivery Domestic Shipping Charge <label class="text-danger">*</label></label>
											<input type="tel" onkeypress="return /[0-9 ]/i.test(event.key)" name="delivery_shipping_charge"  class="form-control" placeholder="Delivery Domestic Shipping Charge" required value="@if($result){{ $result[0]['value']}}@endif"/>
										</div>
									</div>
								</div>

								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label for="inputName">Delivery International Shipping Charge <label class="text-danger">*</label></label>
											<input type="tel" onkeypress="return /[0-9 ]/i.test(event.key)" name="delivery_international_shipping_charge"  class="form-control" placeholder="Delivery International Shipping Charge" required value="@if($result){{ $result[2]['value']}}@endif"/>
										</div>
									</div>
								</div>

								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label for="inputName">Free Shipping <label class="text-danger">*</label></label>
											<input type="tel" onkeypress="return /[0-9 ]/i.test(event.key)" name="free_shipping"  class="form-control" placeholder="Free Shipping" required value="@if($result){{ $result[1]['value']}}@endif"/>
										</div>
									</div>
								</div>

							</div>
							
							<div class="col-lg-12 p-t-20 text-center">
								
								<button style="background:#353c48;" type="submit" class="btn btn-primary waves-effect m-r-15" >Update</button> 
						
							</div>
							
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection