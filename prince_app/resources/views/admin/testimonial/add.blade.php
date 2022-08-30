@extends('layouts/master')

@section('title')
	@if(!empty($result))
		Update
	@else
		Add
	@endif
	Testimonial
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
                            <a class="btn-primary" href="{{ url('admin/testimonial/list')}}">
                                <i class="fa fa-list"></i> Testimonial List 
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
						<h2><i class="fa fa-th"></i> @if(!empty($result)) Update @else Add @endif Testimonial</h2>
					</div>
					<div class="body">
						<form id="form" action="{{ route('admin.testimonial.add') }}" method="post" enctype="multipart/form-data"  autocomplete="off">
						@csrf
						<input type="hidden" name="id" value="@if(!empty($result)){{ $result['id'] }}@else{{ '0' }}@endif"  required />
						<div class="row clearfix">

							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Name <label class="text-danger">*</label></label>
										<input  value="@if(!empty($result)){{ $result['name'] }}@endif" type="text" required class="form-control" placeholder="Enter Name" name="name" >
									</div>
								</div>
							</div>
							
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Designation <label class="text-danger">*</label></label>
										<input  value="@if(!empty($result)){{ $result['designation'] }}@endif" type="text" required class="form-control" placeholder="Enter Designation" name="designation" >
									</div>
								</div>
							</div>
							
						</div>
						
						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Image <label class="text-danger">*</label></label>
										<input type="file" id="uploadImage" class="form-control"  name="image" @if(!$result) required @endif  data-type="single" data-image-preview="testimonial" accept="image/*"   >
										<p style="color:red;width:100%">Size must be 70*70</p>
									</div>
								</div>
								
								<div class="form-group previewimages col-md-6" id="testimonial">
									@if($result)
										<img src="{{ asset('uploads/testimonial/'.$result->image) }}" style="width: 100px;border:1px solid #222;margin-right: 13px" />
										<input type="hidden" name="old_image" value="{{ $result->image }}" />
									@endif
								</div>
							</div>
						</div>
						
						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Description <label class="text-danger">*</label></label>
										<textarea class="form-control" name="description" placeholder="Description">@if(!empty($result)){{ $result['description'] }}@endif</textarea>
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

