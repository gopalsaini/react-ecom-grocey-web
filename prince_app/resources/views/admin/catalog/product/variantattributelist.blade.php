@extends('layouts/master')

@section('title',__('Variant Attributes'))

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
                            <a class="btn-primary" href="{{ url('admin/catalog/variant-attribute/add') }}">
                                <i class="fa fa-plus"></i> Add Variant Attribute 
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
						<h2><i class="fa fa-th"></i> Variant Attributes List</h2>
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
                                                        aria-label=" Email : activate to sort column ascending"> Variant Name
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending">Title
                                                    </th>
													
													@if(\Auth::user()->designation_id=='1' || \Auth::user()->designation_id=='4')
														<th class="center sorting" tabindex="0"
															aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
															style="width: 193.017px;"
															aria-label=" Email : activate to sort column ascending">Action
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
															<td class="center">{{ ucfirst($value['title']) }}</td>
															@if(\Auth::user()->designation_id=='1' || \Auth::user()->designation_id=='4')
																<td class="center">
																	<a href="{{ url('admin/catalog/variant-attribute/update-variant-attribute/'.$value['id'] )}}" title="Edit Variant Attribute" class="btn btn-tbl-edit">
																		<i class="fas fa-pencil-alt"></i>
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
                                                    <th class="center" rowspan="1" colspan="1"> Variant Name</th>
                                                    <th class="center" rowspan="1" colspan="1"> Title </th>
													@if(\Auth::user()->designation_id=='1' || \Auth::user()->designation_id=='4')
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
