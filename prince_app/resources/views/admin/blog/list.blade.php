@extends('layouts/master')

@section('title',__('Blog List'))

@section('content')
<section class="content mt-ct">
    <div class="container-fluid">
        <div class="row">
        </div>
    </div>
</section>
<section class="content" style="margin-top:0">
    <div class="container-fluid">
        <div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="card">
					<div class="header">
						<h2><i class="fa fa-th"></i> Go To</h2>
					</div>
					<div class="body">
						<div class="btn-group top-head-btn">
                            <a class="btn-primary" href="{{ route('admin.blog.add')}}">
                                <i class="fa fa-list"></i> Add Blog
							</a>
                        </div>
					</div>
				</div>
			</div>
		</div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="table-">
                            <div id="" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table
                                            class="table table-hover js-basic-example" id="Bloglist">
                                            <thead>
                                                <tr>
                                                    <th># ID</th>
                                                    <th> Title</th>
                                                    <th> Category</th>
                                                    <th> Image</th>
                                                    <th> Status</th>
                                                    <th> Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                <th># ID</th>
                                                    <th> Title</th>
                                                    <th> Category</th>
                                                    <th> Image</th>
                                                    <th> Status</th>
                                                    <th> Action</th>
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

<script>
    $(document).ready(function(){

    fill_datatable();
  
        $('#Bloglist').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ordering": true,
            "oLanguage": {sProcessing: "<div id='loader' class='spinner-border' style='' role='status'></div>"},

            "ajax": {
                "url": "{{ route('admin.blog.list') }}",
                "dataType": "json",
                "async":false,
                "type": "get"
            },
            "fnDrawCallback": function() {
                fill_datatable();
            },
            "columns": [{
                    "data": "id"
                },
                {
                    "data": "title"
                },
                {
                    "data": "category"
                },
                {
                    "data": "image"
                },
                {
                    "data": "status"
                },
                {
                    "data": "action"
                }
            ]

        });
           
    });
    function fill_datatable(){
       
        $('.-change').change(function(){
            var status = $(this).prop('checked') == true ? 1 : 0;
            var id = $(this).data('id');

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ route('admin.blog.changestatus') }}",
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
    }
</script>
@endpush