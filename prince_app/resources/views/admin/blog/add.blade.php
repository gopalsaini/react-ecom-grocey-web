@extends('layouts/master')

@section('title')
	@if(!empty($result))
		Update
	@else
		Add
	@endif
	Blog
@endsection
@push('custom_css')
 <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush
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
                            <a class="btn-primary" href="{{ route('admin.blog.list')}}">
                                <i class="fa fa-list"></i> Blog
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
						<h2><i class="fa fa-th"></i> @if(!empty($result)) Update @else Add @endif Blog</h2>
					</div>
					<div class=" header panel-body">
                    
						{!! Form::open(['route'=>'admin.blog.add','id'=>'form','method'=>'post','enctype'=>'multipart/form-data']) !!}  
					
							{{Form::hidden("id", $result->id ?? 0 , $attributes = ['id'=>'form1'])}} 
							
							<div class="form-group">
								{{ form::label('inputStatus','Select Category')}}
								{{Form::select('category_id',$dataarray, $result->category_id ?? null , $attributes = ['class'=>'form-control'])}}
							</div><br>
							<div class="form-group">
								{{ form::label('title','Title')}} @if(!$result) <span style="color:red">*</span> @endif
								{{Form::text('title', $result->title ?? null, $attributes = ['class'=>'form-control',"id"=>"title","required"=>"required"])}} 
							</div><br>
							<div class="form-group">
								{{ form::label('short_desc','Short Description')}} @if(!$result) <span style="color:red">*</span> @endif
								{{Form::textarea('short_desc', $result->short_desc ?? null, $attributes = ['class'=>'form-control',"id"=>"short_desc","required"=>"required"])}} 
							</div><br>
							<div class="form-group">
								{{ form::label('inputimg','Image')}} @if(!$result) <span style="color:red">*</span> @endif
								{{Form::file('image', $attributes = ['class'=>'form-control',"id"=>"inputimg","data-type"=>"single","data-image-preview"=>"category_preview","accept"=>"images/*",$result->image ?? "required"=>"required"])}} 
								<h5 style="color:red"> Image size must be in : 230*198</h5>
							</div>
							<div class="form-group previewimages" id="category_preview">
								@if($result && $result->image!='')
								<img src="{{ $result->image }}"
									style="width: 100px;border:1px solid #222;margin-right: 13px" />
								@endif

							</div><br>
							<div class="form-group">
								{{ form::label('description','Description')}} @if(!$result) <span style="color:red">*</span> @endif
								{{Form::textarea('description', $result->description ?? null, $attributes = ['class'=>'form-control',"id"=>"summernote","required"=>"required"])}} 
							</div>
							<br>
							<div class="form-group">
								{{Form::submit('Submit', ['class'=>'btn btn-primary'])}}  
							</div>
						{!!Form::close()!!} 
                    
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
@endpush

