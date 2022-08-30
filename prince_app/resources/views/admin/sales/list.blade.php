@extends('layouts/master')

@section('title',__($title))

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
					<div class="header">
						<h2><i class="fa fa-th"></i> {{ $title }}</h2>
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
                                                        style="width: 141.983px;"
                                                        aria-label=" Mobile : activate to sort column ascending"> Order Id
                                                    </th>
                                                    @if($type=='confirmed' || $type=='rejected' || $type=='shipped' || $type=='delivered')
                                                        <th class="center sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                            style="width: 141.983px;"
                                                            aria-label=" Mobile : activate to sort column ascending"> SubOrder Id
                                                        </th>
                                                    @endif
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Order Date
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 126.333px;"
                                                        aria-label=" Name : activate to sort column ascending"> Name
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 141.983px;"
                                                        aria-label=" Mobile : activate to sort column ascending"> Mobile
                                                    </th>
                                                    
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Subtotal
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Coupon Code
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Net Total
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Payment Mode
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 85px;"
                                                        aria-label=" Action : activate to sort column ascending"> Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
												@if(!empty($result))
													@foreach($result as $key=>$value)
                                                    <tr>
														<td class="center">{{ $key+1 }}</td>
														<td class="center">{{ $value['order_id'] }}</td>
                                                        @if($type=='confirmed' || $type=='rejected' || $type=='shipped' || $type=='delivered')
														    <td class="center">{{ $value['suborder_id'] }}</td>
                                                        @endif
														<td class="center">{{ date('d-M-Y',strtotime($value['created_at'])) }}</td>
														<td class="center">{{ ucfirst($value['name']) }}</td>
														<td class="center">{{ $value['mobile'] }}</td>
														<td class="center">{{ $value['subtotal']}}</td>
														<td class="center">N/A</td>
														<td class="center">{{ $value['net_amount']}}</td>
														<td class="center">
															@if($value['payment_type']=='1')
																<div class="badge col-green">Online Payment</div>
															@elseif($value['payment_type']=='2')
																<div class="badge col-orange">COD</div>	
															@else
																N/A
															@endif
														</td>
														<td class="center">
															@if($type=='pending')
																<a href="javascript:void(0)" data-type="approve"  data-page_type="{{ $type }}" data-sale_id="{{ $value->id }}" title="Approved Order" class="btn btn-tbl-edit getorderdetail">
																	<i class="fas fa-check"></i>
																</a>
																
																<a href="javascript:void(0)" data-type="reject" data-page_type="{{ $type }}" title="Reject Order"  data-type="reject"  data-sale_id="{{ $value->id }}" class="btn btn-tbl-delete getorderdetail" >
																	<i class="fas fa-times"></i>
																</a>
															@else
																<a  style="background-color:blue" data-type="view" data-page_type="{{ $type }}" href="javascript:void(0)" title="View Orders" data-type="view"  data-sale_id="{{ $value->id }}" data-pagetype="{{ $type }}" class="btn btn-tbl-delete getorderdetail" >
																	<i class="fas fa-list"></i>
																</a>
															@endif

                                                            @if($type=='confirmed' || $type=='shipped' || $type=='delivered')
                <!--                                                <a  style="background-color:orange"  target="_blank" href="{{ url('admin/sales/download-packaging-slip/'.$value['waybill_no']) }}" title="Packaging Slip" class="btn btn-tbl-delete" >-->
																<!--	<i class="fas fa-download"></i>-->
																<!--</a>-->

                                                                <a  style="background-color:red"  target="_blank" href="{{ url('admin/sales/order-invoice/'.$value['id']) }}" title="Download Invoice" class="btn btn-tbl-delete" >
																	<i class="fas fa-file-invoice"></i>
																</a>
                                                            @endif

                                                            @if($type=='confirmed')

                                                                <a href="javascript:void(0)"  data-sale_id="{{ $value->id }}" style="padding: 3px;background-color:green"  data-suborderid="{{ $value->suborder_id }}" title="Order Ready for Shipped" data-type="shipped"  class="btn btn-tbl-edit orderready">
                                                                    <i class="fas fa-check"></i>
                                                                </a>
                                                               
                                                            @endif
                                                            
                                                            @if($type=='shipped')

                                                                <a href="javascript:void(0)"  data-sale_id="{{ $value->id }}" style="padding: 3px;background-color:green"  data-suborderid="{{ $value->suborder_id }}" title="Order Ready for Delivered" data-type="delivered" class="btn btn-tbl-edit orderready">
                                                                    <i class="fas fa-check"></i>
                                                                </a>
                                                                
                                                            @endif

														</td>
                                                    </tr>
													@endforeach
												@endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="center" rowspan="1" colspan="1">#</th>
                                                    <th class="center" rowspan="1" colspan="1"> Order Id </th>
                                                    @if($type=='confirmed' || $type=='rejected' || $type=='shipped' || $type=='delivered')
                                                        <th class="center" rowspan="1" colspan="1"> SubOrder Id </th>
                                                    @endif
                                                    <th class="center" rowspan="1" colspan="1"> Order Date </th>
                                                    <th class="center" rowspan="1" colspan="1"> Name </th>
                                                    <th class="center" rowspan="1" colspan="1"> Mobile </th>
                                                    <th class="center" rowspan="1" colspan="1"> Subtotal </th>
                                                    <th class="center" rowspan="1" colspan="1"> Coupon Code </th>
                                                    <th class="center" rowspan="1" colspan="1"> Net Total </th>
                                                    <th class="center" rowspan="1" colspan="1"> Payment Mode </th>
                                                    <th class="center" rowspan="1" colspan="1"> Action </th>
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

	<div id="productdetailModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content"  id="productDetail">
			</div>
		</div>
	</div>

@endsection

@push('custom_js')
<script src="{{ asset('admin-assets/js/jquery-datatable.js') }}"></script>

    <script>
	
        $('.orderready').click(function() {

            var sale_id = $(this).data('sale_id');
            var suborder_id = $(this).data('suborderid');
            var type = $(this).data('type');
            
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ route('admin.sales.orderready') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'sale_id': sale_id,
                    'suborder_id':suborder_id,
                    'type':type,
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
		
		$('.getorderdetail').click(function() {
			
            var id = $(this).data('sale_id');
            var type = $(this).data('type');
            var pageType = $(this).data('page_type');

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ url('admin/sales/getsaledetail') }}",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
                data: {
                    'id': id,
					'type':type,
                    'pageType':pageType
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
					$('#productDetail').html(data.html);
					$('#productdetailModal').modal('toggle');
					
					$('#preloader').css('display','none');
                }
            });
		});
		
    </script>                                           
@endpush