@extends('layouts/master')

@section('title',__('Ondemand Enquiry List'))
@push('custom_css')
<link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endpush
@section('content')

<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="header">
                        <h2><i class="fa fa-th"></i> List</h2>
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
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending">
                                                        Quantity
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 85px;"
                                                        aria-label=" Action : activate to sort column ascending"> View
                                                    </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($result))
                                                @foreach($result as $key=>$value)
                                                <tr class="gradeX odd">
                                                    <td class="center">{{ $key+1}}</td>
                                                    <td class="center">{{ ucfirst($value['name']) }}</td>
                                                    <td class="center">{{ $value['email'] }}</td>
                                                    <td class="center">{{ $value['mobile'] }}</td>
                                                    <td class="center">{{ $value['qty'] }}</td>
                                                    <td class="center">
                                                        <button data-toggle="modal"
                                                            data-target="#details{{ $value['id'] }}"
                                                            class="btn btn-tbl-edit " title="View Details"> <i
                                                                class="fa fa-info-circle" aria-hidden="true"></i>
                                                        </button>
                                                        <a href="{{url('product-detail/'.$value['slug'])}}"
                                                            target="_blank" class="btn btn-tbl-edit "
                                                            title="View Product" style="background-color: orange;"> <i
                                                                class="fa fa-eye" aria-hidden="true"></i> </a></td>
                                                </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="center" rowspan="1" colspan="1">#</th>
                                                    <th class="center" rowspan="1" colspan="1"> Name </th>
                                                    <th class="center" rowspan="1" colspan="1"> Email </th>
                                                    <th class="center" rowspan="1" colspan="1"> Mobile </th>
                                                    <th class="center" rowspan="1" colspan="1"> Quantity</th>
                                                    <th class="center" rowspan="1" colspan="1"> View </th>

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


@if(!empty($result))
@foreach($result as $key=>$value)
<!-- Modal -->
<div class="modal fade" id="details{{ $value['id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style='border-bottom: 1px solid #d6d6d6;padding:0;'>
                <h4 style='font-weight:400;'>Product Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style='padding:15px 0;'>
                <p style='display:inline-block;font-size:14px;font-weight:400; color:#000;margin:0;'>
                    <h6 style='display:inline-block;color:#000; font: weight 400px !important;font-size:14px;'> Product
                        Name: </h6> <span style='color:#000;'>{{ $value['pname'] }}</span>
                </p>
                <p>
                    <h6 style='display:inline-block;color:#000; font: weight 400px !important;font-size:14px;'>
                        Description: </h6>  <span style='color:#000;'>{{ $value['description'] }}</span>
                </p>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
@endsection

@push('custom_js')
<script src="{{ asset('admin-assets/js/jquery-datatable.js') }}"></script>

<script>
    $('.-change').change(function () {
        var status = $(this).prop('checked') == true ? 1 : 0;
        var id = $(this).data('id');

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ route('admin.product.changestatus') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'status': status,
                'id': id
            },
            beforeSend: function () {
                $('#preloader').css('display', 'block');
            },
            error: function (xhr, textStatus) {

                if (xhr && xhr.responseJSON.message) {
                    sweetAlertMsg('error', xhr.status + ': ' + xhr.responseJSON.message);
                } else {
                    sweetAlertMsg('error', xhr.status + ': ' + xhr.statusText);
                }
                $('#preloader').css('display', 'none');
            },
            success: function (data) {
                $('#preloader').css('display', 'none');
                sweetAlertMsg('success', data.message);
            }
        });
    });

    $('.-topselling').change(function () {
        var status = $(this).prop('checked') == true ? 1 : 0;
        var id = $(this).data('id');

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ route('admin.product.topsellingstatus') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'status': status,
                'id': id
            },
            beforeSend: function () {
                $('#preloader').css('display', 'block');
            },
            error: function (xhr, textStatus) {

                if (xhr && xhr.responseJSON.message) {
                    sweetAlertMsg('error', xhr.status + ': ' + xhr.responseJSON.message);
                } else {
                    sweetAlertMsg('error', xhr.status + ': ' + xhr.statusText);
                }
                $('#preloader').css('display', 'none');
            },
            success: function (data) {
                $('#preloader').css('display', 'none');
                sweetAlertMsg('success', data.message);
            }
        });
    });

    $('.-dealsofthday').change(function () {
        var status = $(this).prop('checked') == true ? 1 : 0;
        var id = $(this).data('id');

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ route('admin.product.dealsoftheday') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'status': status,
                'id': id
            },
            beforeSend: function () {
                $('#preloader').css('display', 'block');
            },
            error: function (xhr, textStatus) {

                if (xhr && xhr.responseJSON.message) {
                    sweetAlertMsg('error', xhr.status + ': ' + xhr.responseJSON.message);
                } else {
                    sweetAlertMsg('error', xhr.status + ': ' + xhr.statusText);
                }
                $('#preloader').css('display', 'none');
            },
            success: function (data) {
                $('#preloader').css('display', 'none');
                sweetAlertMsg('success', data.message);
            }
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>

@endpush