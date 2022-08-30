@extends('layouts/master')

@section('title')
	@if(!empty($result))
		Update
	@else
		Add
	@endif
	Address
@endsection

@section('content')
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="card">
					<div class="header">
						<h2><i class="fa fa-th"></i> Go To</h2>
					</div>
					<div class="body">
						<div class="btn-group top-head-btn">
                            <a class="btn-primary" href="{{ url('admin/user/address-book/'.$user->id)}}">
                                <i class="fa fa-list"></i> Address Book List of {{ ucfirst($user->name) }}
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
						<h2><i class="fa fa-th"></i> @if(!empty($result)) Update @else Add @endif Address</h2>
					</div>
					<div class="body">
						<form id="form" action="{{ url('admin/user/add-address') }}" method="post" enctype="multipart/form-data" autocomplete="off">
						@csrf
						<input  value="@if(!empty($result)){{ $result['id'] }}@else{{ '0' }}@endif" type="hidden" required class="form-control" name="id" />
						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Select Country <label class="text-danger">*</label></label>
										<select class="form-control" name="country_id" required id="country_id">
											<option value="" disabled selected >--Select--</option>
											@if($countries)
											
												@foreach($countries as $country)  
													<option value="{{ $country['id'] }}" @if(!empty($result) && $countryId==$country['id']) {{ 'selected' }}@endif>{{ ucfirst($country['name']) }}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="row clearfix">
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Select State <label class="text-danger">*</label></label>
										<select class="form-control" name="state_id" required id="state_id">
											<option value="" disabled selected >--Select--</option>

										</select>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Select City <label class="text-danger">*</label></label>
										<select class="form-control" name="city_id" required id="city_id">
											<option value="" disabled selected >--Select--</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row clearfix">
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Enter Pincode <label class="text-danger">*</label></label>
										<input type="text" class="form-control" placeholder="Enter Attribute Value" name="pincode" value="@if(!empty($result)){{ $result['pincode'] }}@endif"/>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Select Type <label class="text-danger">*</label></label>
										<select class="form-control" name="type" required >
											<option value="">--Type--</option>
											<option value="1" @if(!empty($result) && $result['type']=='1') {{ 'selected' }}@endif>Home</option>
											<option value="2" @if(!empty($result) && $result['type']=='2') {{ 'selected' }}@endif>Office</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Address-I <label class="text-danger">*</label></label>
										<textarea class="form-control" placeholder="Address -I" name="address_line1" required >@if(!empty($result)){{ $result['address_line1'] }}@endif</textarea>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Address -II</label>
										<textarea class="form-control" placeholder="Address -II" name="address_line2" >@if(!empty($result)){{ $result['address_line2'] }}@endif</textarea>
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
	
		var selectedId=0;
		$(document).ready(function(){
			@if($result)
				 $("#country_id").trigger('change');
			@endif
		 });
		 
        $("#country_id").change(function() {

			@if($result)
				 selectedId={{ $result['state_id'] }}
			@endif
			
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ route('admin.getstates-bycountryid') }}",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
                data: {
                    'country_id': $(this).val(),
					'selected_id': selectedId
                },
                beforeSend:function(){
                    $('#preloader').css('display','block');
                },
                error:function(xhr,textStatus){
					
                    if(xhr && xhr.responseJSON.message){
						sweetAlertMsg('error', xhr.status + ': ' + xhr.responseJSON.message);
					}else{
						sweetAlertMsg('error', xhr.status + ': ' + xhr.statusText);
					}
                    $('#preloader').css('display','none');
                },
                success: function(data){
					$('#state_id').html(data.result);
					
					@if($result)
						 $("#state_id").trigger('change');
					@endif
					$('#preloader').css('display','none');
                }
            });
		});
		
		$('#state_id').change(function(){
			
            @if($result)
				 selectedId={{ $result['city_id'] }}
			@endif

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ route('admin.getcity-bystateid') }}",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
                data: {
                    'state_id': $(this).val(),
					'selected_id':selectedId
                },
                beforeSend:function(){
                    $('#preloader').css('display','block');
                },
                error:function(xhr,textStatus){
					
                    if(xhr && xhr.responseJSON.message){
						sweetAlertMsg('error', xhr.status + ': ' + xhr.responseJSON.message);
					}else{
						sweetAlertMsg('error', xhr.status + ': ' + xhr.statusText);
					}
                    $('#preloader').css('display','none');
                },
                success: function(data){
					$('#city_id').html(data.result);
					$('#preloader').css('display','none');
                }
            });
		});
		
		
    </script>                                           
@endpush


