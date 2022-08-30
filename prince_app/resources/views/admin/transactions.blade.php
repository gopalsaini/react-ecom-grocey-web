@extends('layouts/master')

@section('title',__('Transaction History'))

@section('content')

<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
					<div class="header">
						<h2><i class="fa fa-th"></i> Transaction History</h2>
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
                                                    <th class="center sorting sorting_asc" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 48.4167px;" aria-sort="ascending"
                                                        aria-label="#: activate to sort column descending">Date & Time</th> 
                                                    <th class="center sorting sorting_asc" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 48.4167px;" aria-sort="ascending"
                                                        aria-label="#: activate to sort column descending">Payment By</th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 126.333px;"
                                                        aria-label=" Name : activate to sort column ascending">Order Id
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 126.333px;"
                                                        aria-label=" Name : activate to sort column ascending"> Razorpay/Paypal Order Id
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 141.983px;"
                                                        aria-label=" Mobile : activate to sort column ascending"> Transaction Id
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Method
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Amount
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 85px;"
                                                        aria-label=" Action : activate to sort column ascending"> Status
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
												@if(!empty($result))
													@foreach($result as $key=>$value)
														<tr class="gradeX odd">
															<td class="center">{{ $key+1}}</td>
                                                            <td class="center">{{ date('d-M-Y H:i:s',strtotime($value->created_at)) }}</td>
															<td class="center">
                                                                @if($value['payment_by']=='1')
                                                                {{ 'Razorpay' }}
                                                                @elseif($value['payment_by']=='2')
                                                                 {{ 'Paypal' }}
                                                                @else
                                                                    {{ 'N/A' }}
                                                                @endif

                                                            </td>
															<td class="center">{{ $value->order_id }}</td>
															<td class="center">
                                                                @if($value['payment_by']=='1')
                                                                    {{ $value->razorpay_order_id }}
                                                                @elseif($value['payment_by']=='2')
                                                                     {{ $value->paypal_payerid }}
                                                                @else
                                                                    {{ 'N/A' }}
                                                                @endif
                                                            </td>
															<td class="center">{{ $value->transaction_id }}</td>
															<td class="center">{{ $value->method }}</td>
															<td class="center">{{ \App\Helpers\commonHelper::getpriceIconByCountry($value['amount'], $value['currency_id'])}}</td>
															<td class="center">
																@if($value->payment_status=='0' || $value->payment_status=='1' || $value->payment_status=='7' || $value->payment_status=='8')
                                                                  <div class="badge col-red">{{ \App\Helpers\commonHelper::getPaymentStatusName($value->payment_status) }}</div>
                                                                @elseif($value->payment_status=='3' || $value->payment_status=='4' || $value->payment_status=='6')
                                                                    <div class="badge col-orange">{{ \App\Helpers\commonHelper::getPaymentStatusName($value->payment_status) }}</div>
                                                                @elseif($value->payment_status=='2' || $value->payment_status=='5')
                                                                    <div class="badge col-green">{{ \App\Helpers\commonHelper::getPaymentStatusName($value->payment_status) }}</div>
                                                                @endif
															</td>
														</tr>
													@endforeach
												@endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="center" rowspan="1" colspan="1">#</th>
                                                    <th class="center" rowspan="1" colspan="1">Date & Time</th>
                                                    <th class="center" rowspan="1" colspan="1">Payment By</th>
                                                    <th class="center" rowspan="1" colspan="1">Order Id</th>
                                                    <th class="center" rowspan="1" colspan="1"> Razorpay/Paypal Order Id </th>
                                                    <th class="center" rowspan="1" colspan="1"> Transaction Id </th>
                                                    <th class="center" rowspan="1" colspan="1"> Method </th>
                                                    <th class="center" rowspan="1" colspan="1"> Amount </th>
                                                    <th class="center" rowspan="1" colspan="1"> Status </th>
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