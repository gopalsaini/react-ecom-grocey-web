@extends('layouts/master')

@section('title',__('Variant Product List'))

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
                            <a class="btn-primary" href="{{ url('admin/catalog/product/add-variant-product/'.$parentProduct->id) }}">
                                <i class="fa fa-plus"></i> Add Variant Product 
							</a>
                        </div>
					</div>
				</div>
			</div>
		</div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
					<div class="header">
						<h2><i class="fa fa-th"></i> Variant Product List of {{ ucfirst($parentProduct->name) }}</h2>
					</div>
                    <div class="body">
                        <div class="table-">
                            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-hover js-basic-example contact_list dataTable"
                                            id="DataTables_Table_0" role="grid"
                                            aria-describedby="DataTables_Table_0_info">
                                            <thead>
                                                <tr role="row">
                                                    <th class="center sorting sorting_asc" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 48.4167px;" aria-sort="ascending"
                                                        aria-label="#: activate to sort column descending"># ID</th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> SKU No
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Sale Price
                                                    </th>
													<th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Stock
                                                    </th>
													<th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Attributes
                                                    </th>
													@if(\Auth::user()->designation_id=='1' || \Auth::user()->designation_id=='4')
														<th class="center sorting" tabindex="0"
															aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
															style="width: 193.017px;"
															aria-label=" Email : activate to sort column ascending"> Status
														</th>
														<th class="center sorting" tabindex="0"
															aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
															style="width: 85px;"
															aria-label=" Action : activate to sort column ascending"> Action
														</th>
													@endif
                                                </tr>
                                            </thead>
                                            <tbody>
												@if(!empty($result))
													@foreach($result as $key=>$value)
														<tr class="gradeX odd">
															<td class="center">{{ $key+1}}</td>
															<td class="center">{{ $value['sku_id'] }}</td>
															<td class="center">{{ $value['sale_price'] }}</td>
															<td class="center">{{ $value['stock'] }} Pc</td>
															<td class="center">  
																@php
																	$attribute='';
																	
																	$variantArray=explode(',',$value['variant_id']);
																	
																	if(!empty($variantArray) && $variantArray[0]!=''){
																		
																		$variantResult=\App\Models\Variant::whereIn('id',$variantArray)->where('status','1')->get();
																		
																		if(!empty($variantResult)){
																			 
																			foreach($variantResult as $variant){
																				 
																				$attributeArray=explode(',',$value['variant_attributes']); 
																				
																				if(!empty($attributeArray) && $attributeArray[0]!=''){
																					 $attributeResult=\App\Models\Variant_attribute::whereIn('id',$attributeArray)->where('variant_id',$variant['id'])->where('status','1')->first();
																					 
																					if($attributeResult){
																						
																						$attribute.='<label>'.$variant['name'].'</label>:  '.$attributeResult['title'].', ';
																					}
																					
																				} 
																			}
																		}
																		
																	
																	}
																	
																	echo rtrim($attribute,' ,');
																	
																@endphp
															</td>
															
															@if(\Auth::user()->designation_id=='1' || \Auth::user()->designation_id=='4')
																<td class="center">
																	<div class="switch mt-3">
																		<label>
																			<input type="checkbox" class="-change" data-id="{{ $value['id'] }}" @if($value['status']=='1'){{ 'checked' }} @endif>
																			<span class="lever switch-col-red layout-switch"></span>
																		</label>
																	</div>
																</td>
																<td class="center">
																	
																	<a href="{{ url('admin/catalog/product/update-variant-product/'.$value['product_id'].'/'.$value['id'] )}}" title="Update Variant Product" class="btn btn-tbl-edit">
																		<i class="fas fa-pencil-alt"></i>
																	</a>
																	<a title="Delete Variant Product" onclick="return confirm('Are you sure? You want to delete this product.')" href="{{ url('admin/catalog/product/delete-variantproduct/'.$value['id'] )}}" class="btn btn-tbl-delete">
																		<i class="fas fa-trash"></i>
																	</a>
																</td>
															@endif
														</tr>
													@endforeach
												@endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="center" rowspan="1" colspan="1">#</th>
                                                    <th class="center" rowspan="1" colspan="1"> SKU No </th>
                                                    <th class="center" rowspan="1" colspan="1"> Sale Price </th>
                                                    <th class="center" rowspan="1" colspan="1"> Stock </th>
													@if(\Auth::user()->designation_id=='1' || \Auth::user()->designation_id=='4')
														<th class="center" rowspan="1" colspan="1"> Status </th>
														<th class="center" rowspan="1" colspan="1"> Action </th>
													@endif
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('custom_js')
<script src="{{ asset('admin-assets/js/jquery-datatable.js') }}"></script>

    <script>
        $('.-change').change(function() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var id = $(this).data('id');

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ route('admin.product.variantproduct.changestatus') }}",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
                data: {
                    'status': status, 
                    'id': id
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
					$('#preloader').css('display','none');
                    sweetAlertMsg('success',data.message);
                }
            });
		});
		
    </script>                                           
@endpush