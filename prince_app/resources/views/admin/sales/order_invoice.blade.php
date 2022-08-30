<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
rel="stylesheet">
<style>
	* {
		padding: 0;
		box-sizing: border-box;
	}
	body {
		font-family: 'Montserrat', sans-serif;
		background: #f1f1f1;
	}
</style>
</head>
<body>
	<table align="center" style="width: 800px; background: #fff;border-collapse: collapse;">
        <tr>
            <td style="padding: 25px 30px 0px;">
                <table style="background: #fff;width: 100%;border:1px solid #111;border-collapse: collapse;">
					<tr>
                        <td colspan="2" style="padding: 8px;border-bottom:1px solid #111;text-align:center;">
							<!--<img src="{{ asset('images/logo.png') }}" alt="logo" style="width: 30%;">-->
							<p style="font-size: 12px;margin: 5px 0;"> <b>TAX INVOICE</b></p>
							<p style="font-size: 24px;margin: 5px 0;"> <b>Doorstep Grocery</b></p>
						 	<p style="font-size: 12px;margin: 5px 0;"> Alwar, 301001</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px;">
							<p style="font-size: 12px;margin: 5px 0;"> <b>Order id: </b> #{{ $result[0]['order_id'] }} </p>
							<p style="font-size: 12px;margin: 5px 0;"> <b>Payment status: </b> <span> Completed </span></p>
						 	<p style="font-size: 12px;margin: 5px 0;"> <b>Order Date:</b> {{ date('d-M-Y',strtotime($result[0]['created_at'])) }}</p>
                        </td>
						<td style="text-align: right;padding: 8px;">
                            <p style="font-size: 12px;margin: 5px 0;"> <b>GSTIN NO</b>: 05AMIPT1901F1ZB </p>
							<p style="font-size: 12px;margin: 5px 0;"> Alwar, 301101 </p>
							<p style="font-size: 12px;margin: 5px 0;"> India </p>
                        </td>
                    </tr>
					<tr>
                        <td style="border-top:1px solid #111;padding: 8px;width: 50%;border-right:1px solid #111;vertical-align: top;">
							<h5 style="margin:9px 0;">Billed To</h5>
							<p style="font-size: 12px;margin: 5px 0;"><b>Customer Name: </b> {{ ucfirst($result[0]['name']) }}</p>
							<p style="font-size: 12px;margin: 5px 0;"><b>Address:</b> {{ ucfirst($result[0]['address_line1']) }} {{ ucfirst($result[0]['address_line2']) }}</p>
							<p style="font-size: 12px;margin: 5px 0;"><b>City:</b> {{ \App\Helpers\commonHelper::getCityNameById($result[0]['city_id']) }}</p>
							<p style="font-size: 12px;margin: 5px 0;"><b>Pin Code: </b>{{ $result[0]['pincode'] }}</p>
                        </td>
						<td style="border-top:1px solid #111;padding: 8px;">
                            <h5 style="margin:9px 0;">Shipped To</h5>
							<p style="font-size: 12px;margin: 5px 0;"><b>Customer Name: </b> {{ ucfirst($result[0]['name']) }}</p>
							<p style="font-size: 12px;margin: 5px 0;"><b>Address:</b> {{ ucfirst($result[0]['address_line1']) }} {{ ucfirst($result[0]['address_line2']) }}</p>
							<p style="font-size: 12px;margin: 5px 0;"><b>City:</b> {{ \App\Helpers\commonHelper::getCityNameById($result[0]['city_id']) }}</p>
							<p style="font-size: 12px;margin: 5px 0;"><b>Pin Code: </b>{{ $result[0]['pincode'] }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
		<tr>
			<td style="padding: 0 30px 0;">
				<table style="text-align:left;background: #fff;width: 100%;border:1px solid #111;border-top:0;border-collapse: collapse;">
					<tr class="table-heading">
						<th colspan="5" style="padding: 15px 8px;"></th>
					</tr> 
					<tr class="table-heading">
						<th style="border: 1px solid #111;padding: 0 8px;width:40px;"><p style="font-size: 12px;margin: 5px 0;">S.N.</p></th>
						<th style="border: 1px solid #111;padding: 0 8px;"><p style="font-size: 12px;margin: 5px 0;">Item(s)</p></th>
						<th style="border: 1px solid #111;padding: 0 8px;"><p style="font-size: 12px;margin: 5px 0;">Quantity</p></th>
						<th style="border: 1px solid #111;padding: 0 8px;"><p style="font-size: 12px;margin: 5px 0;">Tax(%)</p></th>
						<th style="border: 1px solid #111;padding: 0 8px;"><p style="font-size: 12px;margin: 5px 0;">Price</p></th>
												
						<th style="border: 1px solid #111;padding: 0 8px;text-align:right"><p style="font-size: 12px;margin: 5px 0;">Amount</p></th>
					</tr> 

					@php
						$totalUnitPrice=0;$totalCgsgAmount=0;$totalIgstAmount=0;$totalFinalAmount=0;
					@endphp

					@foreach($result as $key=>$value)

						<?php

							$taxPercentage=0;

							$variantResult=\App\Models\Product::where('id',$value['product_id'])->first(); 
							
							if($variantResult){

								$productData = \App\Models\Product::where('id',$variantResult['product_id'])->first();

								if($productData){

									$taxPercentage=$productData->tax_ratio;
								}
							}

							$unitPrice=$value['amount'];

							

							$totalUnitPrice+=$unitPrice*$value['qty'];

							
						?>

						<tr> 
							<td style="border: 1px solid #111;border-top:0;border-bottom:0;padding: 0 8px;"><p style="font-size: 12px;margin: 5px 0;">{{ $key+1 }}.</p></td>
							<td style="border: 1px solid #111;border-top:0;border-bottom:0;padding: 0 8px;"><p style="font-size: 12px;margin: 5px 0;"><strong>{{ ucfirst($value['product_name']) }}</strong></p></td>
							<td style="border: 1px solid #111;border-top:0;border-bottom:0;padding: 0 8px;"><p style="font-size: 12px;margin: 5px 0;">{{ $value['qty'] }}</p></td>
							<td style="border: 1px solid #111;border-top:0;border-bottom:0;padding: 0 8px;"><p style="font-size: 12px;margin: 5px 0;">{{ number_format($taxPercentage) }}</p></td>					
							<td style="border: 1px solid #111;border-top:0;border-bottom:0;padding: 0 8px;"><p style="font-size: 12px;margin: 5px 0;">{{ number_format($variantResult->sale_price,2) }}</p></td>
												
							<td style="border: 1px solid #111;border-top:0;border-bottom:0;padding: 0 8px;text-align:right"><p style="font-size: 12px;margin: 5px 0;">{{ number_format(($unitPrice),2) }}</p></td>	
						</tr>
					@endforeach
					
					<?php $totalFinalAmount+=$totalUnitPrice; ?>

					<tr style="border-bottom:1px solid black"> 
						<td style="border-top: 1px solid #111;"></td> 
						<td style="border-top: 1px solid #111;"></td> 
						<td style="border-top: 1px solid #111;"></td> 
						<td style="border: 1px solid #111;border-bottom:0;padding: 0 8px;"><p style="font-size: 12px;margin: 5px 0;"><strong>Total</strong></p></td> 
						<td style="border: 1px solid #111;border-bottom:0;padding: 0 8px;text-align:left"><p style="font-size: 12px;margin: 5px 0;">{{ number_format($totalUnitPrice,2) }}</p></td>
						<td style="border: 1px solid #111;border-bottom:0;padding: 0 8px;text-align:right"><p style="font-size: 12px;margin: 5px 0;">{{ number_format($totalFinalAmount,2) }}</p></td> 
					</tr> 
					
					<tr class="table-heading">
						<th colspan="7" style="padding: 15px 8px;font-size:15px">Amount in Words: {{ \App\Helpers\commonHelper::convert_number_to_words($totalFinalAmount) }} Only.</th>
					</tr> 
				</table>
			</td>
		</tr>
		<tr>
            <td style="padding: 0 30px 25px;">
                <table style="background: #fff;width: 100%;border:1px solid #111;border-collapse: collapse;border-top: 0;">
					<tr>
                        <td rowspan="2" style="border-right:1px solid #111;padding: 8px;width:50%;vertical-align: top;">
							<h5 style="margin:9px 0;">Terms & Conditions</h5>
							<p style="font-size: 12px;margin: 5px 0;">E.& O.E.</p>
							<p style="font-size: 12px;margin: 5px 0;">1. Good once sold will not be taken back.</p>
							<p style="font-size: 12px;margin: 5px 0;">2. Interest @ 18% p.a. will be charged if the payment is not made with in the stipulated time.</p>
							<p style="font-size: 12px;margin: 5px 0;">3. Subject to 'Uttarakhand' Jurisdiction only.</p>
                        </td>
						<td style="padding: 8px;">
                            <h5 style="margin: 5px 0;">Receiver's Signature:</h5>
                        </td>
                    </tr>
					<tr>
						<td style="text-align: right;border-top:1px solid #111;padding: 8px;">
                            <h5 style="margin:9px 0;">For Doorstep Grocery</h5>
							<img src="{{ asset('images/invoice_signature.jpeg') }}" alt="signature"> 
							<p style="font-size: 12px;margin: 0 0 5px;"><b>Authorised Signatory</b></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>