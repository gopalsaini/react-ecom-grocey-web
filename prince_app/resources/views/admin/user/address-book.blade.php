@extends('layouts/master')

@section('title',__('Addres Book'))

@section('content')

<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
					<div class="header">
						<h2><i class="fa fa-th"></i> Address Book of {{ ucfirst($user->name) }}</h2>
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
                                                        style="width: 126.333px;"
                                                        aria-label=" Name : activate to sort column ascending"> State
                                                    </th>
													<th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 126.333px;"
                                                        aria-label=" Name : activate to sort column ascending"> City
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 126.333px;"
                                                        aria-label=" Name : activate to sort column ascending"> Address-I
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 141.983px;"
                                                        aria-label=" Mobile : activate to sort column ascending"> Address II
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Pincode
                                                    </th>
													<th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Type
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
														<tr class="gradeX odd">
															<td class="center">{{ $key+1}}</td>
															<td class="center">{{ \App\Helpers\commonHelper::getStateNameById($value['state_id']) }}</td>
															<td class="center">{{ \App\Helpers\commonHelper::getCityNameById($value['city_id']) }}</td>
															<td class="center">{{ $value['address_line1'] }}</td>
															<td class="center">{{ $value['address_line2'] }}</td>
															<td class="center">{{ $value['pincode'] }}</td>
															<td class="center">
																@if($value['type']=='1')
																	{{ 'Home' }}
																@elseif($value['type']=='2')
																	{{ 'Office' }}
																@endif
															</td>
															
															<td class="center">
																
																<a href="{{ url('admin/user/update-address/'.$value['user_id'].'/'.$value['id'] )}}" title="Edit category" class="btn btn-tbl-edit">
																	<i class="fas fa-pencil-alt"></i>
																</a>
																<a title="Delete Address" onclick="return confirm('Are you sure? You want to delete this category.')" href="{{ url('admin/user/delete-address/'.$value['id'] )}}" class="btn btn-tbl-delete">
																	<i class="fas fa-trash"></i>
																</a>
															</td>
														</tr>
													@endforeach
												@endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="center" rowspan="1" colspan="1">#</th>
                                                    <th class="center" rowspan="1" colspan="1"> State </th>
                                                    <th class="center" rowspan="1" colspan="1"> City </th>
                                                    <th class="center" rowspan="1" colspan="1"> Address-I </th>
                                                    <th class="center" rowspan="1" colspan="1"> Address II </th>
                                                    <th class="center" rowspan="1" colspan="1"> Pincode </th>
                                                    <th class="center" rowspan="1" colspan="1"> Type </th>
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

@endsection