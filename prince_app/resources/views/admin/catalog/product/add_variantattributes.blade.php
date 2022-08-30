@extends('layouts/master')

@section('title')
	@if(!empty($result))
		Update
	@else
		Add
	@endif
	Variant Attributes
@endsection

@push('custom_css')
	<link rel="stylesheet" href="{{ asset('admin-assets/colorpicker/spectrum.css')}}" />
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
                            <a class="btn-primary" href="{{ url('admin/catalog/variant-attribute/list')}}">
                                <i class="fa fa-list"></i> Variant Attribute List
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
						<h2><i class="fa fa-th"></i> @if(!empty($result)) Update @else Add @endif Variant Attributes</h2>
					</div>
					<div class="body">
						<form id="form" action="{{ url('admin/catalog/variant-attribute/add') }}" method="post" enctype="multipart/form-data" autocomplete="off">
						@csrf
						<input  value="@if(!empty($result)){{ $result['id'] }}@else{{ '0' }}@endif" type="hidden" required class="form-control" name="id" />

						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Select Variant <label class="text-danger">*</label></label>
										<select class="form-control" name="variant_id" required >
											<option value="" disabled selected >--Select--</option>
											@if($variants)
											
												@foreach($variants as $vari)  
													<option value="{{ $vari['id'] }}" @if(!empty($result) && $result['variant_id']==$vari['id']) {{ 'selected' }}@endif>{{ ucfirst($vari['name']) }}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Enter Attribute Value <label class="text-danger">*</label></label>
										<input type="text" class="form-control" placeholder="Enter Attribute Value" name="title" value="@if(!empty($result)){{ ucfirst($result['title']) }}@endif"/>
									</div>
								</div>
							</div>
						</div>
						

						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Enter Attribute Value <label class="text-danger">*</label></label>
										<input id="full" name="color" >
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
		var colorcode="@if(!empty($result)) {{$result->color}} @else {{'#ffffff'}} @endif";
	</script>
	<script src="{{ asset('admin-assets/colorpicker/spectrum.js') }}" ></script>
	<script src="{{ asset('admin-assets/colorpicker/docs/docs.js') }}" ></script>
@endpush
