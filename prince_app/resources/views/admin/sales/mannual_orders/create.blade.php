@extends('layouts/master')

@section('title')
Create Mannual Order
@endsection


@push('custom_css')

<link href="{{ asset('admin-assets/css/intlTelInput.css')}}" rel="stylesheet" />
<link href="{{ asset('admin-assets/css/demo.css')}}" rel="stylesheet" />

<style>
    .in-table tr th,
    table tr td,
    .below-table tr th,
    .below-table tr td {
        padding: 5px 15px !important;
        border: 1px solid #000 !important;
    }

    .in-table,
    .below-table {
        margin-bottom: 0 !important;
    }

    .below-table tr:first-child th,
    .below-table tr:first-child td {
        border-top: none !important;
    }

    .card2 {
        margin-bottom: 0 !important;
    }

    .new-label {
		font-family:'Roboto', sans-serif;
        font-size: 13px;
        line-height: 2;
        color: #414244;
        font-weight: 500;
    }

    .iti {
        width: 100%;
    }

	.fs-wrap{
		width:100%;
		height:100%;
	}
    .fs-label-wrap{
        height:60%
    }
    .fs-label-wrap .fs-label {
        padding: 12px 22px 6px 8px;
    }
 
	.fs-dropdown {
		width:24%;
	}


</style>
@endpush

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><i class="fa fa-th"></i> Go To</h2>
                    </div>
                    <div class="body">
                        <div class="btn-group top-head-btn">
                            <a class="btn-primary" href="{{ url('admin/sales/mannual-orders/orders-list')}}">
                                <i class="fa fa-list"></i> Mannual Orders List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2><i class="fa fa-th"></i> Create Mannual Order</h2>
                    </div>
                    <div class="body">
                        <form id="form" action="{{ route('admin.sales.createmanualorder') }}" method="post"
                            enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <!-- new form -->
                            <div class="container-fluid p-0">
                                <div class="contaier  card card2">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="new-label" for=""> Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class='form-control' name="name" placeholder="Enter Name" onkeypress="return /[A-Za-z ]/i.test(event.key)" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="new-label" for=""> Mobile Number <span
                                                        class="text-danger">*</span></label>
                                                <input type="hidden" name="phone_code" value="" id="phoneCode" />
                                                <input id="phone" name="mobile" type="tel" onkeypress="return /[0-9 ]/i.test(event.key)" class="form-control" placeholder="Enter Mobile No." required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="new-label" for=""> Email-Id <span
                                                        class="text-danger">*</span></label>
                                                <input id="email" name="email" type="email" class="form-control" placeholder="Enter Email-id" required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="" class="new-label">Currency <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select currency"  required name="currency_id"> 
												@foreach($currency as $cor)
													<option value="{{ $cor->id}}" data-firsticon="{{ $cor->first_icon }}" data-secondicon="{{ $cor->second_icon }}">{{ ucfirst($cor->name) }}</option>
												@endforeach
                                            </select>
                                        </div>
                                        
                                    </div>

                                    <div class="row">
                                        
                                        <div class="col-md-3 mb-md-4 mb-3">
                                            <label for="" class="new-label">Country <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select country" aria-label="Default select example" required data-state_id="0" data-city_id="0" name="country_id"> 
												<option value="" disabled selected >--Select--</option>
												@foreach($country as $con)
													<option value="{{ $con->id}}">{{ ucfirst($con->name) }}</option>
												@endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-md-4 mb-3">
                                            <label for="" class="new-label">State <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select statehtml" aria-label="Default select example" required name="state_id">
                                                <option selected>--Select State--</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-md-4 mb-3">
                                            <label for="" class="new-label">City <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select cityHtml" aria-label="Default select example" required name="city_id">
                                                <option selected>--Select City--</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="new-label" for="">Pincode <span
                                                    class="text-danger">*</span></label> 
                                            <input type="text" class='form-control pincodesssss' name="pincode" placeholder="Enter Pincode" minlength="6" maxlength="6" onkeypress="return /[0-9 ]/i.test(event.key)" required/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-md-4 mb-3">
                                            <div class="form-group">
                                                <label for="" class="new-label">Address 1 <span
                                                        class="text-danger">*</span></label>
                                                <input required class="form-control"
                                                    placeholder="Address 1(House No, Building, Street, Area)*"
                                                    type="text" name="address_line1">
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="" class="new-label">Address 2</label>
                                                <input  class="form-control" value=""
                                                    placeholder="Address 2(House No, Building, Street, Area)"
                                                    type="text" name="address_line2">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row justify-content-end">
                                        <div class="col-md-2">
                                            <button style="background:#353c48;float:right" type="button"
                                                class="btn btn-primary waves-effect w-100 addNewProduct">Add New
                                                Product</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table style="width:100%" class="table in-table" id="mannualProduct">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Product Name</th>
                                                        <th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Discount (in %)</th>
                                                        <th>GST (in %)</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            1
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" class='form-control'
                                                                    placeholder="Enter Product name" required
                                                                    name="product_name[]" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="tel"
                                                                    onkeypress="return /[0-9 ]/i.test(event.key)"
                                                                    class='form-control price1'
                                                                    onchange="calculateCalculation()"
                                                                    placeholder="Enter Product Price" required
                                                                    name="product_price[]" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" class='form-control qty1'
                                                                    onchange="calculateCalculation()"
                                                                    placeholder="Enter Quantity"
                                                                    onkeypress="return /[0-9 ]/i.test(event.key)"
                                                                    required name="product_qty[]" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="tel" class='form-control discount1'
                                                                    onchange="calculateCalculation()"
                                                                    onkeypress="return /[0-9 ]/i.test(event.key)"
                                                                    placeholder="Enter Discount" required
                                                                    name="product_discount[]" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group"><input type="text"
                                                                    class='form-control gst1'
                                                                    onchange="calculateCalculation()"
                                                                    placeholder="Enter GST"
                                                                    onkeypress="return /[0-9 ]/i.test(event.key)"
                                                                    required name="product_gst[]" />
                                                            </div>
                                                        </td>

                                                        <td><span class="preicon">₹</span> <span id="subtotal1">0</span>
                                                            <span class="posticon"></span></td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5" class="border-0"></td>
                                                        <td><b>Payable Amount</b></td>
                                                        <td><span class="preicon">₹</span> <span
                                                                id="payableAmount">0</span> <span
                                                                class="posticon"></span></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 p-t-20 text-center">
                                <button style="background:#353c48;" type="submit"
                                    class="btn btn-primary waves-effect m-r-15">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('custom_js')
<script src="{{ asset('admin-assets/js/jquery-datatable.js') }}"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.8/js/intlTelInput-jquery.min.js"></script>

<script>
    var mobileCountry=$('#phone').intlTelInput({
        autoHideDialCode: true,
        autoPlaceholder: "ON",
        dropdownContainer: document.body,
        formatOnDisplay: true,
        hiddenInput: "full_number",
        initialCountry: "auto",
        nationalMode: true,
        placeholderNumberType: "MOBILE",
        preferredCountries: ['US'],
        separateDialCode: true
    });

    mobileCountry.on("countrychange", function() {
        $('#phoneCode').val($("#phone").intlTelInput("getSelectedCountryData").dialCode);
    });

</script>

<script>
    var totalRow = 1;
    var html;
    var preIcon = "₹";
    var postIcon = "";

    $(document).ready(function () {

        $('.addNewProduct').click(function () {

            totalRow++;

            var html = '<tr>';
            html += '<td>';
            html += totalRow;
            html += '</td>';
            html += '<td>';
            html += '<div class="form-group">'
            html +=
                '<input type="text" class="form-control"  required placeholder="Enter Product name" name="product_name[]"/>';
            html += '</div>';
            html += '</td>';
            html += '<td>';
            html += '<div class="form-group">';
            html +=
                '<input type="tel" onkeypress="return /[0-9 ]/i.test(event.key)"onchange="calculateCalculation()" required class="form-control price' +
                totalRow + '"  placeholder="Enter Product Price" name="product_price[]"/>';
            html += '</div>';
            html += '</td>';
            html += '<td>';
            html += '<div class="form-group">';
            html += '<input type="text" class="form-control qty' + totalRow +
                '" onchange="calculateCalculation()" required placeholder="Enter Quantity" onkeypress="return /[0-9 ]/i.test(event.key)" name="product_qty[]"/>';
            html += '</div>';
            html += '</td>';
            html += '<td>';
            html += '<div class="form-group">';
            html += '<input type="tel" class="form-control discount' + totalRow +
                '" onchange="calculateCalculation()" required onkeypress="return /[0-9 ]/i.test(event.key)" placeholder="Enter Discount" name="product_discount[]"/>';
            html += '</div>';
            html += '</td>';
            html += '<td>'
            html += '<div class="form-group"><input type="text" class="form-control gst' + totalRow +
                '" onchange="calculateCalculation()" required  placeholder="Enter GST" onkeypress="return /[0-9 ]/i.test(event.key)" name="product_gst[]"/>';
            html += '</div>';
            html += '</td>';
            html += '<td><span class="preicon">' + preIcon + '</span> <span id="subtotal' + totalRow +
                '">0</span> <span class="posticon">' + postIcon + '</span></td>';
            html += '</tr>';

            $("#mannualProduct > tbody").append(html);

        });
    });

    function resetFormData() {

        location.href="{{ url('admin/sales/mannual-orders/orders-list') }}";
    }

    var productPrice;
    var productQty;
    var productFinalPrice;
    var productDiscount;
    var finalSubTotal;
    var netTotalAmount = 0;

    function calculateCalculation() {

        netTotalAmount = 0;

        for (var i = 1; i <= totalRow; i++) {

            productPrice = parseFloat($('.price' + i).val());
            if (isNaN(productPrice)) productPrice = 0;

            productQty = parseFloat($('.qty' + i).val());
            if (isNaN(productQty)) productQty = 0;

            productFinalPrice = productPrice * productQty;

            productDiscount = (productFinalPrice * (parseFloat($('.discount' + i).val()) / 100));
            if (isNaN(productDiscount)) productDiscount = 0;

            productGst = ((productFinalPrice - productDiscount) * (parseFloat($('.gst' + i).val()) / 100));
            if (isNaN(productGst)) productGst = 0;

            finalSubTotal = (productFinalPrice - productDiscount + productGst).toFixed(2);

            netTotalAmount += parseFloat(finalSubTotal);

            $('#subtotal' + i).html(finalSubTotal);
        }

        $('#payableAmount').html((netTotalAmount).toFixed(2));

    }

	$('.country').fSelect({
		placeholder: '--Country--',
		overflowText: '{n} selected',
		noResultsText: 'No results found',
		searchText: 'Search',
		showSearch: true
	});

	$('.statehtml').fSelect();

	$('.cityHtml').fSelect();

    $('.currency').change(function(){  
        preIcon=($(this).find(':selected').data('firsticon'));
        postIcon=($(this).find(':selected').data('secondicon'));
        $('.preicon').html(preIcon);
        $('.posticon').html(postIcon);
    });
</script>
@endpush
