@extends('layouts/master')

@section('title')
	@if(!empty($data))
		Update
	@else
		Add
	@endif
	User
@endsection



@section('content')
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="card">
					<div class="header">
						<h2>@if(!empty($data)) Update @else Add @endif User</h2>
					</div>
					<div class="body">
						<form id="form" action="{{ url('admin/user/update-user') }}" method="post" enctype="multipart/form-data">
						@csrf
							<input type="hidden" name="id" value="@if(!empty($data)){{ $data['id'] }}@else{{ '0' }}@endif"  required />
							<div class="row clearfix">
								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<input  value="@if(!empty($data)){{ $data['name'] }}@endif" type="text" onkeypress="return /[A-Za-z ]/i.test(event.key)" required class="form-control" placeholder="Enter Name" name="name" >
										</div>
									</div>
								</div>
							</div>
							<div class="row clearfix">
								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<input  value="@if(!empty($data)){{ $data['mobile'] }}@endif" onkeypress="return /[0-9 ]/i.test(event.key)" type="tel" class="form-control" placeholder="Enter Mobile" name="mobile" >
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12 p-t-20 text-center">
								@if(empty($data))
									<button type="reset" class="btn btn-danger waves-effect">Reset</button>
								@endif
								<button style="background:#353c48;" type="submit" class="btn btn-primary waves-effect m-r-15" >Submit</button> 
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

