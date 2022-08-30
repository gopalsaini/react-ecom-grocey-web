@extends('layouts/master')

@section('title')
	@if(!empty($result))
		Update
	@else
		Add
	@endif
	Variants
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
                            <a class="btn-primary" href="{{ url('admin/catalog/variant/list')}}">
                                <i class="fa fa-list"></i> Variant List
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
						<h2><i class="fa fa-th"></i> @if(!empty($result)) Update @else Add @endif Variant</h2>
					</div>
					<div class="body">
						<form id="form" action="{{ url('admin/catalog/variant/add') }}" method="post" enctype="multipart/form-data" autocomplete="off">

							@csrf
							<input  value="@if(!empty($result)){{ $result['id'] }}@else{{ '0' }}@endif" type="hidden" required class="form-control" name="id" />

							<div class="row clearfix">
								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label for="inputName">Enter Variant Name <label class="text-danger">*</label></label>
											<input type="text" class="form-control" placeholder="Enter Variant Name" name="name" value="@if(!empty($result)){{ ucfirst($result['name']) }}@endif" required />
										</div>
									</div>
								</div>
							</div>
							
							<div class="row clearfix">
								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label for="inputName">Select Display Layout <label class="text-danger">*</label></label>
											<select class="form-control display_layout" name="display_layout" required >
												<option value=""  >--Select Display Layout--</option>
												<option value="1" @if(!empty($result) && $result['display_layout']== '1') {{ 'selected' }}@endif>Dropdown swatch</option>
												<option value="2" @if(!empty($result) && $result['display_layout']== '2') {{ 'selected' }}@endif>Visual swatch</option>
												<option value="3" @if(!empty($result) && $result['display_layout']== '3') {{ 'selected' }}@endif>Text swatch</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							
							<div class="row clearfix">
								<div class="col-sm-12">
									<div class="form-group">
										<div class="form-line">
											<label for="inputName">Enter Sort Order <label class="text-danger">*</label></label>
											<input type="tel" class="form-control" placeholder="Enter Sort Order Ex. 1,2,3..." name="variant_sort_order" value="@if(!empty($result)){{ ucfirst($result['sort_order']) }}@endif"/>
										</div>
									</div>
								</div>
							</div>	
							
							@if(!empty($variantResult))
								@foreach($variantResult as $key=>$attr)
									<div class="row clearfix after-add-more row">

										<div class="col-sm-4">  
											<label for="inputName">Enter Attribute Value <label class="text-danger">*</label></label>
											<input type="text" class="form-control addmorebox" placeholder="Enter Attribute Value" name="attribute_value[]" value="{{ $attr['title'] }}" required />
										</div>

					
										<div class="col-sm-2">  
											<label for="inputName">Enter Sort Order <label class="text-danger">*</label></label>
											<input type="text" class="form-control addmorebox" placeholder="Enter Sort Order" name="sort_order[]" value="{{ $attr['sort_order'] }}" required />
										</div>
										
										<div class="col-sm-1 colordiv"> 
											<label for="inputName" style="margin-top:0 !important;">Enter Color <label class="text-danger" >*</label></label><br>
											<span class="colorpicker" style="float:left;margin-top:6px">
												<input type="text" class="form-control hap-ch" placeholder="Enter Color" name="color[]" value="{{ $attr['color'] }}" required />
											</span>
										</div>

										<div class="col-sm-2">  
											<label for="inputName" style="margin:0">Status <label class="text-danger">*</label></label>
											<div class="switch mt-3">
												<label style="margin:0">
													<input type="checkbox" class="variantstatus"  @if($attr['status']=='1'){{ 'checked' }}@endif value="1" name="status[{{$key}}]">
													<span class="lever switch-col-red layout-switch"></span>
												</label>
											</div>
										</div>
										
										
										<div class="col-md-2 change d-flex align-items-center m-0"> 
											<input type="hidden" name="attribute_id[]" value="{{ $attr['id'] }}" required />
											@if($key==0)
												<button style="background:#353c48;" type="button" class="btn btn-primary waves-effect m-r-15 add-more" >Add more</button> 
											@endif
										</div>
										
									</div>
								@endforeach
							@else
								<div class="row clearfix after-add-more row">

									<div class="col-sm-4">  
										<label for="inputName">Enter Attribute Value <label class="text-danger">*</label></label>
										<input type="text" class="form-control addmorebox" placeholder="Enter Attribute Value" name="attribute_value[]" value="" required />
									</div>


									<div class="col-sm-2">  
										<label for="inputName"  style='margin:0;'>Enter Sort Order <label class="text-danger">*</label></label>
										<input type="text" class="form-control addmorebox" placeholder="Enter Sort Order" name="sort_order[]" value="" required />
									</div>

									<div class="col-sm-1 colordiv"> 
										<label for="inputName" style="margin-top:0 !important;">Enter Color <label class="text-danger" >*</label></label><br>
										<span class="colorpicker" style="float:left;margin-top:6px">
											<input type="text" class="form-control hap-ch" placeholder="Enter Color" name="color[]" value="#000000" required />
										</span>
									</div>

									<div class="col-sm-2">  
										<label for="inputName" style="margin:0">Status <label class="text-danger">*</label></label>
										<div class="switch mt-3">
											<label style="margin:0">
												<input type="checkbox" class="variantstatus"  checked value="1" name="status[0]">
												<span class="lever switch-col-red layout-switch"></span>
											</label>
										</div>
									</div>

									<div class="col-md-2 change d-flex align-items-center m-0">
										<input type='hidden' name='attribute_id[]' value='0' required />
										<button style="background:#353c48;" type="button" class="btn btn-primary waves-effect m-r-15 add-more" >Add more</button> 
									</div>
								</div>
							@endif

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

		var i=0;

		$(document).ready(function() {
			
			$("body").on("click",".add-more",function(){ 
				i++;

				var html = $(".after-add-more").first().clone();
			
				$(html).find(".change").html("<input type='hidden' name='attribute_id[]' value='0' required /><button style='background:#353c48;' type='button' class='btn btn-danger waves-effect m-r-15 remove'>Remove</button>");
				$(html).find(".colorpicker").html('<input type="text" class="form-control hap-ch" placeholder="Enter Color" name="color[]" value="#000000" required />');
			
				$(html).find(".variantstatus").attr('name','status['+i+']');

				$(html).find('.addmorebox').val('');
				
				$(".after-add-more").last().after(html);
				
				$(".hap-ch").spectrum();
			
			});

			$("body").on("click",".remove",function(){ 
				$(this).parents(".after-add-more").remove();
			});

			$('.display_layout').change(function(){

				if($(this).val()=='2'){

					$('.colordiv').css('display','block');

				}else{

					$('.colordiv').css('display','none');
				}
			});

			$('.display_layout').trigger('change');
		});

	</script>
	 
	<script>
		var colorcode="";
 
		function resetFormData(){
			location.reload();
		}
	</script>
	<script src="{{ asset('admin-assets/colorpicker/spectrum.js') }}" ></script>
	<script src="{{ asset('admin-assets/colorpicker/docs/docs.js') }}" ></script> 
@endpush
