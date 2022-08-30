@extends('layouts/master')

@section('title')
	@if(!empty($result))
		Update
	@else
		Add
	@endif
	Coupon
@endsection

@push('custom_css')
	<link href="{{ asset('admin-assets/css/jquery-ui.css')}}" rel="stylesheet" />
@endpush

@section('content')
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="card">
					<div class="header">
						<h2><i class="fa fa-th"></i>  Go To</h2>
					</div>
					<div class="body">
						<div class="btn-group top-head-btn">
                            <a class="btn-primary" href="{{ url('admin/catalog/coupon/list')}}">
                                <i class="fa fa-list"></i> Coupon List 
							</a>
                        </div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="card">
					<div class="header">
						<h2><i class="fa fa-th"></i> @if(!empty($result)) Update @else Add @endif Coupon</h2>
					</div>
					<div class="body">
						<form id="form" action="{{ route('admin.coupon.add') }}" method="post" enctype="multipart/form-data"  autocomplete="off">
						@csrf
						<input type="hidden" name="id" value="@if(!empty($result)){{ $result['id'] }}@else{{ '0' }}@endif"  required />
						<div class="row clearfix">

							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Title <label class="text-danger">*</label></label>
										<input  value="@if(!empty($result)){{ $result['title'] }}@endif" type="title" required class="form-control" placeholder="Enter Title" name="title" >
									</div>
								</div>
							</div>
							
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Coupon Code <label class="text-danger">*</label></label>
										<input  value="@if(!empty($result)){{ $result['coupon_code'] }}@endif" type="text" required class="form-control" placeholder="Enter Coupon Code" name="coupon_code" >
									</div>
								</div>
							</div>
							
						</div>
						
						<div class="row clearfix">

							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Start Date <label class="text-danger">*</label></label>
										<input  value="@if(!empty($result)){{ date('m/d/Y',strtotime($result->start_date)) }}@endif" type="text" required class="form-control" id="from" placeholder="Enter Start Date" name="start_date" >
									</div>
								</div>
							</div>
							
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">End Date <label class="text-danger">*</label></label>
										<input  value="@if(!empty($result)){{ date('m/d/Y',strtotime($result->end_date)) }}@endif" type="text" required id="to" class="form-control" placeholder="Enter End Date" name="end_date" >
									</div>
								</div>
							</div>
							
						</div>
						
						<div class="row clearfix">

							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Total No. of Uses <label class="text-danger">*</label></label>
										<input type="tel" name="totalno_uses" required onkeypress="return isNumberKey(event)"  value="@if(!empty($result)){{ $result['totalno_uses'] }}@else{{'0'}}@endif" class="form-control" placeholder="Total No. of Uses" />
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Min Order Amount <label class="text-danger">*</label></label>
										<input type="tel" name="minorder_amount" required onkeypress="return isNumberKey(event)"  value="@if(!empty($result)){{ $result['minorder_amount'] }}@else{{'0'}}@endif" class="form-control" placeholder="Min Order Amount" />
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Discount Type <label class="text-danger">*</label></label>
										<select class="form-control" name="discount_type" required >
											<option value="1" @if(!empty($result) && $result['discount_type']=='1') selected @endif >%</option>
											<option value="2" @if(!empty($result) && $result['discount_type']=='2') selected @endif>Rs</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Discount Value <label class="text-danger">*</label></label>
										<input type="tel" name="discount_amount" required onkeypress="return isNumberKey(event)"  value="@if(!empty($result)){{ $result['discount_amount'] }}@else{{'0'}}@endif" class="form-control" placeholder="Enter Discount Price" />
									</div>
								</div>
							</div>

						</div>
						
						<div class="col-lg-12 p-t-20 text-center">
							@if(empty($result)) 
								<button type="reset" class="btn btn-danger waves-effect">Reset</button>
							@endif
							<button style="background:#353c48;" type="submit" class="btn btn-primary waves-effect m-r-15" >@if(!empty($result)) Update @else Submit @endif</button> 
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@push('custom_js')
	<script>
	  $( function() {
		var dateFormat = "dd/mm/yy",
		  from = $( "#from" )
			.datepicker({
			  changeMonth: true,
			  changeYear: true,
			  numberOfMonths: 1
			})
			.on( "change", function() {
			  to.datepicker( "option", "minDate", getDate( this ) );
			}),
		  to = $( "#to" ).datepicker({
			changeMonth: true,
			numberOfMonths: 1
		  })
		  .on( "change", function() {
			from.datepicker( "option", "maxDate", getDate( this ) );
		  });
	 
		function getDate( element ) {
		  var date;
		  try {
			date = $.datepicker.parseDate( dateFormat, element.value );
		  } catch( error ) {
			date = null;
		  }
	 
		  return date;
		}
	  } );
	</script>
@endpush

