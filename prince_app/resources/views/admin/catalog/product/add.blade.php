@extends('layouts/master')

@section('title')
	@if(!empty($result))
		Update
	@else
		Add
	@endif
	Product
@endsection

@push('custom_css')
 <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
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
                            <a class="btn-primary" href="{{ url('admin/catalog/product/list')}}">
                                <i class="fa fa-list"></i> Product List 
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
						<h2><i class="fa fa-th"></i> @if(!empty($result)) Update @else Add @endif Product</h2>
					</div>
					<div class="body">
						<form id="form" action="{{ route('admin.product.addproduct') }}" method="post" enctype="multipart/form-data"  autocomplete="off">
						@csrf
						<input  value="@if(!empty($result)){{ $result['id'] }}@else{{ '0' }}@endif" type="hidden" required class="form-control" name="id" />
						
						<div class="row clearfix">
							
							<div class="col-sm-4">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Product Title</label>
										<input  value="@if(!empty($result)){{ $result['name'] }}@endif" type="text" required class="form-control" placeholder="Enter Product Title" name="name" >
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">SKU <label class="text-danger">*</label></label>
										<input type="text" name="sku_id" required class="form-control" placeholder="Enter SKU" value="@if(!empty($result)){{ $result['sku_id'] }}@endif"/>
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Select Brands <label class="text-danger">*</label></label>
										<select class="form-control" name="brand_id" id="brand"  required >
											<option  selected value="">--Select--</option>
											@if(!empty($brands))
												@foreach($brands as $raw)
													<option value="{{ $raw['id'] }}" @if(!empty($result) && $raw['id']==$result['brand_id']) {{ 'selected' }} @endif>{{ $raw['name'] }}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row clearfix">
							<div class="col-sm-4">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Category <label class="text-danger">*</label></label>
										<select class="form-control" name="category_id" id="category"  required >
											<option  selected value="">--Select--</option>
											@if(!empty($category))
												@foreach($category as $raw)
													<option value="{{ $raw['id'] }}" @if(!empty($result) && $raw['id']==$result['category_parent_id']) {{ 'selected' }} @endif>{{ $raw['name'] }}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Sub Category <label class="text-danger">*</label></label>
										<select class="form-control" name="subcategory_id"  id="cateData"  required >
											<option  selected value="">--Select--</option>
											
										</select>
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Select Variants Attribute <label class="text-danger">*</label></label>
										<select class="form-control" name="variant_attribute_id" required >
											<option  selected value="">--Select--</option>
											@if(!empty($variants))
												@foreach($variants as $raw)
													<option value="{{ $raw['id'] }}" @if(!empty($result) && $raw['id']==$result['variant_attribute_id']) {{ 'selected' }} @endif>{{ \App\Helpers\commonHelper::getVariantName($raw['variant_id']) }} > {{$raw['title']}}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="row clearfix">
						
							<div class="col-sm-4">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Stock (Qty) <label class="text-danger">*</label></label>
										<input type="tel" name="stock" onkeypress="return /[0-9 ]/i.test(event.key)" value="@if(!empty($result)){{ $result['stock'] }}@else{{'0'}}@endif" class="form-control" placeholder="Enter Stock (Qty)" required />
									</div>
								</div>
							</div>
							
							<div class="col-sm-4">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">User Limit <label class="text-danger">*</label></label>
										<input type="tel" name="limit" onkeypress="return /[0-9 ]/i.test(event.key)" value="@if(!empty($result)){{ $result['user_limit'] }}@else{{'0'}}@endif" class="form-control" placeholder="Enter User Limit" required />
									</div>
								</div>
							</div>
							
							<div class="col-sm-4">
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
						
							<div class="col-sm-6" style="display:none">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Tax Ratio (%) <label class="text-danger">*</label></label>
										<input  value="@if(!empty($result)){{ $result['tax_ratio'] }} @else {{0}}@endif" onkeypress="return /[0-9 ]/i.test(event.key)" type="tel" required class="form-control" placeholder="Enter Tax Ratio (%)" name="tax_ratio" >
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
										<label for="inputName">Short Description <label class="text-danger">*</label></label>
										<textarea class="form-control" name="short_description" required  placeholder="Short Description">@if(!empty($result)){{ $result['short_description'] }}@endif</textarea>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Description <label class="text-danger">*</label></label>
										<textarea class="form-control" id="summernote" name="description" required placeholder="Description">@if(!empty($result)){{ $result['description'] }}@endif</textarea>
									</div>
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
	<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

	<script>
			$('#summernote').summernote({
				placeholder: 'Enter Description',
				tabsize: 2,
				height: 200,
			});
	</script>

	
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
	
	    var  subcategory = "@if($result){{$result['category_id']}}@endif";
	
		$('#category').on('change', function(){
			
			var country = this.value;
			if(country){
				var country = this.value;
			}else{
				var country = "0";
			}
			$.ajax({
				url: "{{ route('admin.get.sub.category')}}",
				type: "POST",
				data: {
					country: country,subcategory: subcategory
				},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 
				},
				cache: false,
				beforeSend: function(){
					
				},
				error:function(xhr,textStatus){ 
				
					// alert(xhr.responseJSON.message);
					showMsg('error', xhr.status + ': ' + xhr.statusText);
				},
				success: function(result){
					
					$("#cateData").html(result); 
					
					
				}
			}); 
		});

		$('#category').trigger('change'); 

</script>
@endpush

