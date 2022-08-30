@extends('layouts/master')

@section('title')
	@if(!empty($result))
		Update 
	@else
		Add 
	@endif
	Pincode
@endsection

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
                            <a class="btn-primary" href="{{ url('admin/pincode/list')}}">
                                <i class="fa fa-list"></i> Pincode List
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
						<h2><i class="fa fa-th"></i> @if(!empty($result)) Update @else Add @endif Pincode</h2>
					</div>
					<div class="body">
						<form id="form" action="{{ route('admin.pincode.add') }}" method="post" enctype="multipart/form-data" autocomplete="off">
						@csrf
						
						<input type="hidden" name="id" value="@if(!empty($result)){{$result['id']}}@else{{ 0 }}@endif"  required />
						
						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Enter Pin Code <label class="text-danger">*</label></label>
										<input type="tel" name="pincode" required onkeypress="return /[0-9 ]/i.test(event.key)" class="form-control" placeholder="Enter Pincode" value="@if(!empty($result)){{ $result['pincode'] }}@endif"/>
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
		function resetFormData(){
			
			$('.previewimages').html('');
		}
	</script>
@endpush