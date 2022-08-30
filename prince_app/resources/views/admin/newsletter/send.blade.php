@extends('layouts/master')

@section('title')
	News subscribe
@endsection

@push('custom_css')
 <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

 	<style>
		.fs-wrap, .fs-dropdown{
			width:100%;
			font-weight:400
		}
	</style>
@endpush

@section('content')

<section class="content">
	<div class="container-fluid">
		
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="card">
					<div class="header">
						<h2><i class="fa fa-th"></i> Newsletter Email </h2>
					</div>
					<div class="body">
						<form id="form" action="{{ route('admin.newsletter.send') }}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf
							
							<div class="row clearfix">

								<div class="col-sm-6">
									<div class="form-group">
										<label for="inputName" style="display:block">Select Emails <label class="text-danger">*</label></label>
										<select class="selectbox" class="" name="emails[]" multiple>
											<option value="">--Select Email--</option>
											@foreach($result as $email)
												<option selected value="{{ $email['email']}}" >{{ $email['email'] }}</option>
											@endforeach
										</select>
									</div>
								</div>

								<div class="col-sm-6">
									<div class="form-group">
										<div class="form-line">
											<label for="inputName">Subject <label class="text-danger">*</label></label>
											<input type="text" name="subject"  class="form-control" placeholder="Enter Subject" required />
										</div>
									</div>
								</div>

								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label for="inputName">Description <label class="text-danger">*</label></label>
											<textarea class="form-control" name="message" rows="4"  id="summernote" cols="50"></textarea>
										</div>
									</div>
								</div>

							</div>
							
							<div class="col-lg-12 p-t-20 text-center">
								
								<button style="background:#353c48;" type="submit" class="btn btn-primary waves-effect m-r-15" >Send</button> 
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

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
	
	<script>

		$(document).ready(function() {

			$('.selectbox').fSelect({
				placeholder: 'Select Email',
			});

			$('#summernote').summernote({
				placeholder: 'Enter Description',
				tabsize: 2,
				height: 200,
			});

		});

	</script>
@endpush