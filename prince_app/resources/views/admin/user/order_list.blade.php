@extends('layouts/master')

@section('title',__('Order Details'))

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="header">
                        <h2><i class="fa fa-th"></i> Order Details</h2>
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
                                                        aria-label=" Mobile : activate to sort column ascending"> Order
                                                        Id
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Order
                                                        Date
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 126.333px;"
                                                        aria-label=" Name : activate to sort column ascending"> Name
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 141.983px;"
                                                        aria-label=" Mobile : activate to sort column ascending"> Qty
                                                    </th>

                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending">
                                                        Subtotal
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Net
                                                        Total
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Status
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Payment
                                                        Mode
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($result))
                                                @foreach($result as $key=>$value)
                                                <tr>
                                                    <td class="center">{{ $key+1 }}</td>
                                                    <td class="center">{{ $value['order_id'] }}</td>
                                                    <td class="center">
                                                        {{ date('d-M-Y',strtotime($value['created_at'])) }}</td>
                                                    <td class="center">{{ ucfirst($value['product_name']) }}</td>
                                                    <td class="center">{{ $value['qty'] }}</td>
                                                    <td class="center">{{ $value['sub_total'] }}</td>
                                                    <td class="center">{{ $value['amount'] }}</td>
                                                    <td class="center">
                                                        @php $status =\App\Helpers\commonHelper::getOrderStatusName($value['order_status']);
                                                        @endphp

                                                        @if($value['order_status']=='0' || $value['order_status']=='1')
                                                            <div class="badge col-orange">{{ $status }}</div>
                                                        @elseif($value['order_status']=='8' || $value['order_status']=='2' || $value['order_status']=='9' || $value['order_status']=='10')
                                                            <div class="badge col-green">{{ $status }}</div>
                                                        @elseif($value['order_status']=='3' || $value['order_status']=='4' || $value['order_status']=='5' || $value['order_status']=='6' || $value['order_status']=='7')
                                                            <div class="badge col-red">{{ $status }}</div>
                                                        @endif

                                                    </td>
                                                    <td class="center">
                                                    @if($value->payment_status=='0' || $value->payment_status=='1' || $value->payment_status=='7')
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
                                                    <th class="center" rowspan="1" colspan="1"> Order Id </th>
                                                    <th class="center" rowspan="1" colspan="1"> Order Date </th>
                                                    <th class="center" rowspan="1" colspan="1"> Name </th>
                                                    <th class="center" rowspan="1" colspan="1"> Qty </th>
                                                    <th class="center" rowspan="1" colspan="1"> Subtotal </th>
                                                    <th class="center" rowspan="1" colspan="1"> Net Total </th>
                                                    <th class="center" rowspan="1" colspan="1"> Status </th>
                                                    <th class="center" rowspan="1" colspan="1"> Payment Mode </th>
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
        <div class="modal-content" id="productDetail">
        </div>
    </div>
</div>

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
        url: "{{ route('admin.slider.changestatus') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            'status': status,
            'id': id
        },
        beforeSend: function() {
            $('#preloader').css('display', 'block');
        },
        error: function(xhr, textStatus) {

            if (xhr && xhr.responseJSON.message) {
                sweetAlertMsg('error', xhr.status + ': ' + xhr.responseJSON.message);
            } else {
                sweetAlertMsg('error', xhr.status + ': ' + xhr.statusText);
            }
            $('#preloader').css('display', 'none');
        },
        success: function(data) {
            $('#preloader').css('display', 'none');
            sweetAlertMsg('success', data.message);
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
            'type': type,
            'pageType': pageType
        },
        beforeSend: function() {
            $('#preloader').css('display', 'block');
        },
        error: function(xhr, textStatus) {

            if (xhr && xhr.responseJSON.message) {
                sweetAlertMsg('error', xhr.status + ': ' + xhr.responseJSON.message);
            } else {
                sweetAlertMsg('error', xhr.status + ': ' + xhr.statusText);
            }
            $('#preloader').css('display', 'none');
        },
        success: function(data) {
            $('#productDetail').html(data.html);
            $('#productdetailModal').modal('toggle');

            $('#preloader').css('display', 'none');
        }
    });
});
</script>
@endpush