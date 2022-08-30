@extends('layouts/master')

@section('title',__('Dashboard'))

@section('content')
	
		<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                         <h4 class="page-title">Dashboard</h4>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-xl-3 col-lg-6">
                    <a href="{{ url('admin/sales/list/pending') }}">
                        <div class="card l-bg-blue-dark">
                            <div class="info-box-5 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                                <div class="mb-4">
                                    <h5 class="font-20 mb-0">New Order</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            {{ number_format($totalPendingOrder) }}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <a href="{{ url('admin/sales/list/confirmed') }}">
                        <div class="card l-bg-green-dark">
                            <div class="info-box-5 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-tasks"></i></div>
                                <div class="mb-4">
                                    <h5 class="font-20 mb-0">Confirmed Orders</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            {{ number_format($totalConfirmedOrder) }}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <a href="{{ url('admin/sales/list/shipped') }}">
                        <div class="card l-bg-orange-dark">
                            <div class="info-box-5 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-money-check-alt"></i></div>
                                <div class="mb-4">
                                    <h5 class="font-20 mb-0">Shipped Orders</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            {{ number_format($totalShippedOrder) }}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <a href="{{ url('admin/sales/list/delivered') }}">
                        <div class="card l-bg-purple">
                            <div class="info-box-5 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-money-check-alt"></i></div>
                                <div class="mb-4">
                                    <h5 class="font-20 mb-0">Delivered Orders</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            {{ number_format($totalDeliveredOrder) }}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                <strong>Sales</strong> Chart</h2>
                        </div>
                        <div class="body">
                            <div class="recent-report__chart">
                                <div id="chart1"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-lg-6">
                    <div class="card">
                        <div class="header">
                            <h2>
                                <strong>Profit</strong> Chart</h2>
                        </div>
                        <div class="body">
                            <div class="recent-report__chart">
                                <div id="chart2"></div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            <div class="row clearfix">
                <!-- Task Info -->
                <!-- <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <div class="card">
                        <div class="header">
                            <h2>
                                <strong>Project</strong> Details</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="#" onClick="return false;" class="dropdown-toggle"
                                        data-bs-toggle="dropdown" role="button" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="#" onClick="return false;">Action</a>
                                        </li>
                                        <li>
                                            <a href="#" onClick="return false;">Another action</a>
                                        </li>
                                        <li>
                                            <a href="#" onClick="return false;">Something else here</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Project</th>
                                            <th>Team</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Project A</td>
                                            <td class="text-truncate">
                                                <ul class="list-unstyled order-list">
                                                    <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user4.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user5.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user3.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><span class="badge">+1</span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td><span class="badge col-orange">High</span></td>
                                            <td>
                                                <div class="progress-xs not-rounded progress shadow-style">
                                                    <div class="progress-bar progress-bar-danger width-per-40"
                                                        role="progressbar" aria-valuenow="40" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        <span class="sr-only">40%</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>$150.00</td>
                                        </tr>
                                        <tr>
                                            <td>Project B</td>
                                            <td class="text-truncate">
                                                <ul class="list-unstyled order-list">
                                                <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user4.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user5.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user3.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><span class="badge">+1</span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td><span class="badge col-cyan">Medium</span></td>
                                            <td>
                                                <div class="progress-xs not-rounded progress shadow-style">
                                                    <div class="progress-bar progress-bar-lightred width-per-60"
                                                        role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        <span class="sr-only">60%</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>$470.00</td>
                                        </tr>
                                        <tr>
                                            <td>Project C</td>
                                            <td class="text-truncate">
                                                <ul class="list-unstyled order-list">
                                                <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user4.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user5.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user3.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><span class="badge">+1</span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td><span class="badge col-orange">High</span></td>
                                            <td>
                                                <div class="progress-xs not-rounded progress shadow-style">
                                                    <div class="progress-bar progress-bar-warning width-per-40"
                                                        role="progressbar" aria-valuenow="40" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        <span class="sr-only">40%</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>$564.00</td>
                                        </tr>
                                        <tr>
                                            <td>Project D</td>
                                            <td class="text-truncate">
                                                <ul class="list-unstyled order-list">
                                                <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user4.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user5.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user3.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><span class="badge">+1</span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td><span class="badge col-green">Low</span></td>
                                            <td>
                                                <div class="progress-xs not-rounded progress shadow-style">
                                                    <div class="progress-bar progress-bar-success width-per-45"
                                                        role="progressbar" aria-valuenow="45" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        <span class="sr-only">45%</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>$925.00</td>
                                        </tr>
                                        <tr>
                                            <td>Project E</td>
                                            <td class="text-truncate">
                                                <ul class="list-unstyled order-list">
                                                <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user4.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user5.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user3.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><span class="badge">+1</span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td><span class="badge col-cyan">Medium</span></td>
                                            <td>
                                                <div class="progress-xs not-rounded progress shadow-style">
                                                    <div class="progress-bar progress-bar-lightred width-per-80"
                                                        role="progressbar" aria-valuenow="80" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        <span class="sr-only">80%</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>$785.00</td>
                                        </tr>
                                        <tr>
                                            <td>Project G</td>
                                            <td class="text-truncate">
                                                <ul class="list-unstyled order-list">
                                                <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user4.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user5.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><img class="rounded-circle"
                                                            src="{{ asset('admin-assets/images/user3.jpg') }}" alt="user">
                                                    </li>
                                                    <li class="avatar avatar-sm"><span class="badge">+3</span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td><span class="badge col-green">Low</span></td>
                                            <td>
                                                <div class="progress-xs not-rounded progress shadow-style">
                                                    <div class="progress-bar progress-bar-success width-per-40"
                                                        role="progressbar" aria-valuenow="40" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        <span class="sr-only">40%</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>$270.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- #END# Task Info -->
                <!-- Browser Usage -->
                <!-- <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="header">
                            <h2>
                                <strong>TODO </strong>List</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="#" onClick="return false;" class="dropdown-toggle"
                                        data-bs-toggle="dropdown" role="button" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="#" onClick="return false;">Action</a>
                                        </li>
                                        <li>
                                            <a href="#" onClick="return false;">Another action</a>
                                        </li>
                                        <li>
                                            <a href="#" onClick="return false;">Something else here</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="tdl-content">
                                <ul class="to-do-list ui-sortable">
                                    <li class="clearfix">
                                        <div class="form-check m-l-10">
                                            <label class="form-check-label"> <input class="form-check-input"
                                                    type="checkbox" value="">
                                                Add salary details in system <span class="form-check-sign"> <span
                                                        class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="todo-actionlist pull-right clearfix">
                                            <a href="#"> <i class="fas fa-times"></i>
                                            </a>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="form-check m-l-10">
                                            <label class="form-check-label"> <input class="form-check-input"
                                                    type="checkbox" value="">
                                                Announcement for holiday <span class="form-check-sign"> <span
                                                        class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="todo-actionlist pull-right clearfix">
                                            <a href="#"> <i class="fas fa-times"></i>
                                            </a>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="form-check m-l-10">
                                            <label class="form-check-label"> <input class="form-check-input"
                                                    type="checkbox" value="">
                                                call bus driver <span class="form-check-sign"> <span
                                                        class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="todo-actionlist pull-right clearfix">
                                            <a href="#"> <i class="fas fa-times"></i>
                                            </a>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="form-check m-l-10">
                                            <label class="form-check-label"> <input class="form-check-input"
                                                    type="checkbox" value="">
                                                Office Picnic <span class="form-check-sign"> <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="todo-actionlist pull-right clearfix">
                                            <a href="#"> <i class="fas fa-times"></i>
                                            </a>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="form-check m-l-10">
                                            <label class="form-check-label"> <input class="form-check-input"
                                                    type="checkbox" value="">
                                                Website Must Be Finished <span class="form-check-sign"> <span
                                                        class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="todo-actionlist pull-right clearfix">
                                            <a href="#"> <i class="fas fa-times"></i>
                                            </a>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="form-check m-l-10">
                                            <label class="form-check-label"> <input class="form-check-input"
                                                    type="checkbox" value="">
                                                Recharge My Mobile <span class="form-check-sign"> <span
                                                        class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="todo-actionlist pull-right clearfix">
                                            <a href="#"> <i class="fas fa-times"></i>
                                            </a>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <div class="form-check m-l-10">
                                            <label class="form-check-label"> <input class="form-check-input"
                                                    type="checkbox" value="">
                                                Add salary details in system <span class="form-check-sign"> <span
                                                        class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="todo-actionlist pull-right clearfix">
                                            <a href="#"> <i class="fas fa-times"></i>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <input type="text" class="tdl-new form-control-radious" placeholder="Enter Todo...">
                        </div>
                    </div>
                </div> -->
                <!-- #END# Browser Usage -->
            </div>
            <!-- <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="header">
                            <h2>Chart 1</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="#" onClick="return false;" class="dropdown-toggle"
                                        data-bs-toggle="dropdown" role="button" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="#" onClick="return false;">Action</a>
                                        </li>
                                        <li>
                                            <a href="#" onClick="return false;">Another action</a>
                                        </li>
                                        <li>
                                            <a href="#" onClick="return false;">Something else here</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div id="echart_graph_line" class="chartsh"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="header">
                            <h2>Chart 2</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="#" onClick="return false;" class="dropdown-toggle"
                                        data-bs-toggle="dropdown" role="button" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="#" onClick="return false;">Action</a>
                                        </li>
                                        <li>
                                            <a href="#" onClick="return false;">Another action</a>
                                        </li>
                                        <li>
                                            <a href="#" onClick="return false;">Something else here</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div id="echart_area_line" class="chartsh"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="header">
                            <h2>Chart 3</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="#" onClick="return false;" class="dropdown-toggle"
                                        data-bs-toggle="dropdown" role="button" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="#" onClick="return false;">Action</a>
                                        </li>
                                        <li>
                                            <a href="#" onClick="return false;">Another action</a>
                                        </li>
                                        <li>
                                            <a href="#" onClick="return false;">Something else here</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div id="echart_bar" class="chartsh"></div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </section>
  

@endsection


@push('custom_js')
	<script src="{{ asset('admin-assets/js/chart.min.js')}}"></script>
	<script src="{{ asset('admin-assets/js/echarts.js')}}"></script>
	<script src="{{ asset('admin-assets/js/apexcharts.min.js') }}"></script>
	
	<script>
		$(function () {
		  chart1("{{ $shippedAmount }}","{{ $deliveredAmount }}");
		//   chart2();
		//   chart3();
		//   chart4();
		//   chart5();
		});
 
	</script>
	
@endpush