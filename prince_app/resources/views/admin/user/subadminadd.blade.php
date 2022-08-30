@extends('layouts/master')

@section('title')
	@if(!empty($result))
		Update
	@else
		Add
	@endif
	SubAdmin
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
                            <a class="btn-primary" href="{{ url('admin/subadmin/list')}}">
                                <i class="fa fa-list"></i> SubAdmin List 
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
						<h2><i class="fa fa-th"></i> @if(!empty($result)) Update @else Add @endif SubAdmin</h2>
					</div>
					<div class="body">
						<form id="form" action="{{ route('admin.subadmin.add') }}" method="post" enctype="multipart/form-data"  autocomplete="off">
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
										<label for="inputName">Email <label class="text-danger">*</label></label>
										<input  value="@if(!empty($result)){{ $result['email'] }}@endif" type="text" required class="form-control" placeholder="Enter Email" name="email" >
									</div>
								</div>
							</div>

							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Designation <label class="text-danger">*</label></label>
										<select class="form-control" name="designation_id" required >
											<option value="">Please select Designation</option>
											<option @if(!empty($result) && $result['designation_id']=='1'){{ 'selected' }}@endif value="1">Super Admin</option>
											<option @if(!empty($result) && $result['designation_id']=='2'){{ 'selected' }}@endif value="2">Customer Support</option>
											<option @if(!empty($result) && $result['designation_id']=='3'){{ 'selected' }}@endif value="3">Shipment Team</option>
											<option @if(!empty($result) && $result['designation_id']=='4'){{ 'selected' }}@endif value="4">Operations</option>
										</select>
									</div>
								</div>
							</div>

							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Password</label> @if(empty($result))<label class="text-danger">*</label>@endif
										<input  value="" type="text" @if(empty($result)){{ 'required' }}@endif class="form-control" placeholder="Enter Password" name="password" >
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

