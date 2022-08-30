@extends('layouts/master')

@section('title',__('Customer List'))

@section('content')

<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
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
                                                        style="width: 126.333px;"
                                                        aria-label=" Name : activate to sort column ascending"> Name
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 141.983px;"
                                                        aria-label=" Mobile : activate to sort column ascending"> Email
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Mobile
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 85px;"
                                                        aria-label=" Action : activate to sort column ascending"> Reg. Type
                                                    </th>
													
													@if(\Auth::user()->designation_id=='1' || \Auth::user()->designation_id=='4')
														<th class="center sorting" tabindex="0"
															aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
															style="width: 85px;"
															aria-label=" Action : activate to sort column ascending"> Status
														</th>
														<th class="center sorting" tabindex="0"
															aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
															style="width: 85px;"
															aria-label=" Action : activate to sort column ascending"> Block User
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
															<td class="center">{{ ucfirst($value['name']) }}</td>
															<td class="center">{{ ucfirst($value['email']) }}</td>
															<td class="center">{{ ucfirst($value['mobile']) }}</td>
															<td class="center">{{ ucfirst($value['reg_type']) }}</td>
															<td class="center">
																@if($value['status']=='0')
																	<div class="badge col-orange">Pending</div>
																@elseif($value['status']=='1')
																	<div class="badge col-green">Activated</div>	
																@endif
															</td>
															
															@if(\Auth::user()->designation_id=='1' || \Auth::user()->designation_id=='4')
																<td class="center">
																	<div class="switch mt-3">
																		<label>
																			<input type="checkbox" class="-change" data-id="{{ $value['id'] }}" @if($value['block_user']=='1'){{ 'checked' }} @endif>
																			<span class="lever switch-col-red layout-switch"></span>
																		</label>
																	</div>
																</td>
																<td class="center">
																	<a href="{{ url('admin/user/update/'.$value['id'] )}}" title="Update User" class="btn btn-tbl-edit">
																		<i class="fas fa-pencil-alt"></i>
																	</a>
																	
																	<a href="{{ url('admin/user/address-book/'.$value['id'] )}}" title="Address Book" class="btn btn-tbl-edit" style="background-color:orange">
																		<i class="fas fa-address-book"></i>
																	</a>

                                                                    <a href="{{ url('admin/user/view-order/'.$value['id'] )}}" title="View Order" class="btn btn-tbl-edit" style="background-color:orange">
																		<i class="fas fa-shopping-cart"></i>
																	</a>
																	
																</td>
															@endif
														</tr>
													@endforeach
												@endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="center" rowspan="1" colspan="1"># ID</th>
                                                    <th class="center" rowspan="1" colspan="1"> Name </th>
                                                    <th class="center" rowspan="1" colspan="1"> Email </th>
                                                    <th class="center" rowspan="1" colspan="1"> Mobile </th>
													@if(\Auth::user()->designation_id=='1' || \Auth::user()->designation_id=='4')
														<th class="center" rowspan="1" colspan="1"> Reg. Type </th>
														<th class="center" rowspan="1" colspan="1"> Status </th>
														<th class="center" rowspan="1" colspan="1"> Block User </th>
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
                url: "{{ route('admin.user.block') }}",
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