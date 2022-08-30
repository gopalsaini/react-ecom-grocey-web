@extends('layouts/master')

@section('title')
	Currency Values
@endsection


@section('content')

<section class="content">
	<div class="container-fluid">
		
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="card">
					<div class="header">
						<h2><i class="fa fa-th"></i> Currency Price Update </h2>
					</div>
					<div class="body">
						<form id="form" action="{{ url('admin/settings/currency') }}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf

							<div class="row clearfix">

								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label for="inputName">INR <label class="text-danger">*</label></label>
											<input type="tel" name="name[]" value="{{$currency[0]->value}}"  class="form-control" required />
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label for="inputName">USD <label class="text-danger">*</label></label>
											<input type="tel" name="name[]" value="{{$currency[1]->value}}"  class="form-control"  required />
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label for="inputName">EUR <label class="text-danger">*</label></label>
											<input type="tel" name="name[]" value="{{$currency[2]->value}}"  class="form-control"  required />
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label for="inputName">GBP <label class="text-danger">*</label></label>
											<input type="tel" name="name[]" value="{{$currency[3]->value}}"  class="form-control" required />
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label for="inputName">AUD <label class="text-danger">*</label></label>
											<input type="tel" name="name[]" value="{{$currency[4]->value}}"  class="form-control"  required />
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label for="inputName">AED <label class="text-danger">*</label></label>
											<input type="tel" name="name[]" value="{{$currency[5]->value}}"  class="form-control" required />
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