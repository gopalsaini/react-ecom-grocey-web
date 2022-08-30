@extends('layouts/master')

@section('title')
	Update SEO Pages
@endsection


@section('content')

<section class="content">
	<div class="container-fluid">
		
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="card">
					<div class="header">
						<h2><i class="fa fa-th"></i> Update SEO For {{ $type }} Page </h2>
					</div>
					<div class="body">
						<form id="form" action="{{ url('admin/seo/home-page') }}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf
							
							<input type="hidden" name="id" value="{{ $result['id'] }}" required />

							<div class="row clearfix">

								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label for="inputName">Meta Title <label class="text-danger">*</label></label>
											<input type="text" name="meta_title"  class="form-control" placeholder="Meta Title" required />
										</div>
									</div>
								</div>

								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label for="inputName">Meta Keywords <label class="text-danger">*</label></label>
											<textarea class="form-control" name="meta_keywords" rows="4" placeholder="Meta Keywords"  cols="50"></textarea>
										</div>
									</div>
								</div>

								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label for="inputName">Meta Description <label class="text-danger">*</label></label>
											<textarea class="form-control" name="meta_description" rows="4" cols="50" placeholder="Meta Description"></textarea>
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