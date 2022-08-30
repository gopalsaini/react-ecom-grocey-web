@extends('layouts/master')

@section('title')
	@if(!empty($result))
		Update Variant
	@else
		Add Variant
	@endif
	Product
@endsection

@push('custom_css')
	<link href="{{ asset('admin-assets/dragimage/dist/image-uploader.min.css')}}" rel="stylesheet"> 
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
                            <a class="btn-primary" href="{{ url('admin/catalog/product/variant-productlist/'.$product_id)}}">
                                <i class="fa fa-list"></i> Variant Product List 
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
						<h2><i class="fa fa-th"></i> @if(!empty($result)) Update @else Add @endif Variant Product For {{ ucfirst($parentProduct->name) }}</h2>
					</div>
					<div class="body">
						<form id="form" action="{{ route('admin.product.addvariant',[$product_id]) }}" method="post" enctype="multipart/form-data" autocomplete="off">
						@csrf
						<input  value="{{ $product_id }}" type="hidden" required class="form-control" name="product_id" />
						<input  value="@if(!empty($result)){{ $result['id'] }}@else{{ '0' }}@endif" type="hidden" required class="form-control" name="id" />

						@if($variants)
							<div class="row clearfix">
								@foreach($variants as $vari)
								 
									@php 
										$attributes=\App\Helpers\commonHelper::getAttributeByparentId($vari->id);

										$selectedAttribute=[];
										
										if($result){
											
											$selectedAttribute=explode(',',$result->variant_attributes);
										}
										
									@endphp
									
									<div class="col-sm-6">
										<div class="form-group">
											<div class="form-line">
												<label for="inputName">{{ ucfirst($vari->name) }} <label class="text-danger">*</label></label>
												<select class="form-control" name="variant_attributes[]" required >
													<option value="" disabled selected>--Select--</option>
													@if($attributes)
													
														@foreach($attributes as $att) 
														
															<option value="{{ $att['id'] }}" @if(in_array($att->id,$selectedAttribute)) selected @endif>{{ ucfirst($att['title']) }}</option>
														@endforeach
													@endif
												</select>
											</div>
										</div>
									</div>
								@endforeach
							</div>
						@endif
						
						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">SKU <label class="text-danger">*</label></label>
										<input type="text" name="sku_id" required class="form-control" placeholder="Enter SKU" value="@if(!empty($result)){{ $result['sku_id'] }}@endif"/>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row clearfix">

							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Discount Type <label class="text-danger">*</label></label>
										<select class="form-control" name="discount_type" required >
											<option value="1" @if(!empty($result) && $result['discount_type']=='1') selected @endif >%</option>
											<option value="2" @if(!empty($result) && $result['discount_type']=='2') selected @endif>Rs</option>
										</select>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Discount Value <label class="text-danger">*</label></label>
										<input type="tel" name="discount_amount" required onkeypress="return isNumberKey(event)"  value="@if(!empty($result)){{ $result['discount_amount'] }}@else{{'0'}}@endif" class="form-control" placeholder="Enter Discount Price" />
									</div>
								</div>
							</div>

						</div>
						
						<div class="row clearfix">
						
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Stock (Qty) <label class="text-danger">*</label></label>
										<input type="tel" name="stock" onkeypress="return /[0-9 ]/i.test(event.key)" value="@if(!empty($result)){{ $result['stock'] }}@else{{'0'}}@endif" class="form-control" placeholder="Enter Stock (Qty)" required />
									</div>
								</div>
							</div>
							
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Sale Price (Rs) <label class="text-danger">*</label></label>
										<input type="tel" name="sale_price" value="@if(!empty($result)){{ $result['sale_price'] }}@endif" onkeypress="return isNumberKey(event)"  class="form-control" placeholder="Enter sale price" required />
									</div>
								</div>
							</div>

						</div>
						
						<div class="row clearfix">
						
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Package Length (in cm) <label class="text-danger">*</label></label>
										<input type="tel" name="package_length" value="@if(!empty($result)){{ $result['package_length'] }}@endif" onkeypress="return isNumberKey(event)"  class="form-control" placeholder="Enter Package Length (in cm)" required />
									</div>
								</div>
							</div>
							
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Package Breadth (in cm) <label class="text-danger">*</label></label>
										<input type="tel" name="package_breadth" value="@if(!empty($result)){{ $result['package_breadth'] }}@endif" onkeypress="return isNumberKey(event)"  class="form-control" placeholder="Enter Package Breadth (in cm)" required />
									</div>
								</div>
							</div>
						</div>
						
						<div class="row clearfix">
						
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Package Height (in cm) <label class="text-danger">*</label></label>
										<input type="tel" name="package_height"  value="@if(!empty($result)){{ $result['package_height'] }}@endif" onkeypress="return isNumberKey(event)"  class="form-control" placeholder="Enter Package Height (in cm)" required />
									</div>
								</div>
							</div>
							
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Package Weight (in kg) <label class="text-danger">*</label></label>
										<input type="tel" name="package_weight" value="@if(!empty($result)){{ $result['package_weight'] }}@endif" onkeypress="return isNumberKey(event)"  class="form-control" placeholder="Enter Package Weight (in kg)" required />
									</div>
								</div>
							</div>

						</div>
						
						
						<div class="row clearfix">
						
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Package Label <label class="text-danger">*</label></label>
										<input type="tel" name="package_label"  value="@if(!empty($result)){{ $result['package_label'] }}@endif"  class="form-control" placeholder="Enter Package Label" required />
									</div>
								</div>
							</div>
							
						</div>
						
						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Images</label>
										<div class="input-images" style="padding-top: .5rem;"></div>
										<!--<p style="color:red;width:100%">Size must be 800*800</p>-->
									</div>
									<p>
								</div>
							</div>
						</div>

						<div class="row clearfix">
						
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Meta Title <label class="text-danger">*</label></label>
										<input type="text" name="meta_title"  class="form-control" placeholder="Meta Title" required value="@if(!empty($result)){{ $result['meta_title'] }}@endif" />
									</div>
								</div>
							</div>

							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Meta Keywords <label class="text-danger">*</label></label>
										<textarea class="form-control" name="meta_keywords" rows="4" placeholder="Meta Keywords"  cols="50">@if(!empty($result)){{ $result['meta_keywords'] }}@endif</textarea>
									</div>
								</div>
							</div>

							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Meta Description <label class="text-danger">*</label></label>
										<textarea class="form-control" name="meta_description" rows="4" cols="50" placeholder="Meta Description">@if(!empty($result)){{ $result['meta_description'] }}@endif</textarea>
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

<script type="text/javascript" src="{{ asset('admin-assets/dragimage/dist/image-uploader.min.js')}}"></script>
<script>  

	let preloaded = [
		@if(!empty($result->images))
			
			@php $images = explode(',',$result->images);  @endphp 
				@foreach($images as $key=>$image)
					{id: "{{$image}}", src: "{{ asset('uploads/products')}}/{{$image}}"},
				@endforeach
		@endif
		
	];

	$('.input-images').imageUploader({
		extensions: ['.jpg', '.jpeg', '.png', '.gif', '.svg'],
		mimes: ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'],
		preloaded: preloaded,
		imagesInputName: 'uploadfile',
		preloadedInputName: 'images',
		maxSize: 2 * 1024 * 1024,
		maxFiles: 10
	});

@if(!empty($event->image))

	var input_id = $('input[type=file]')[0]['id'];
	$('#'+input_id).change(function(){
		confirm("do you want to add images ?");
		var file = $(this)[0].files;
	    var form_data = new FormData();

		for (i = 0; i < file.length; i++) {
			form_data.append('file[]', file[i]);
		}
		form_data.append('file_length', file.length);
		form_data.append('id', "@if($event) {{ $event->id }} @else 0 @endif");
		form_data.append('sid', "@if($event) {{ $event->id }} @else 0 @endif");

		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: "{{route('admin.product.addproduct')}}",
			data: form_data,
			dataType:'json',
			type: 'post',
			beforeSend: function(){
				$('#preloader').css('display','block');
			},
			error:function(xhr,textStatus){
				location.reload();
				window.scrollTo({top: 0, behavior: 'smooth'});
				$('#preloader').css('display','none');
			},
			success: function(data){
				if(data.error){
					alert(data.error);
				}else{
					location.reload();
					window.scrollTo({top: 0, behavior: 'smooth'});
					$('#preloader').css('display','none');
				}
			},
			cache:false,
			contentType:false,
			processData:false,
			timeout: 5000
		});
	});
@endif
	
	$(function() {
		$( ".uploaded" ).sortable({
			update: function() {
			}
		});
	});


	function resetFormData(){ 
		$('.image-uploader').removeClass('has-files');
		$('.uploaded').html('');
	}
</script>
@endpush
