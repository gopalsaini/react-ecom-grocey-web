<div>
    <div class="panel-body">
        <div class="form-group btm_border">
            <div class="col-sm-12">
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <title> </title>
                	<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
                    <style>
                    table, th, td {
                      border: 1px solid black;
                      border-collapse: collapse;
                    }
                    th, td {
                        padding: 5px;
                        font-size: 11px;
                    }
                    body{
                    font-family: 'Open Sans', sans-serif;
                    }	   
                    </style>
                </head>
                <body> 
                    <table style="width:384px;height:576px;margin:auto;color: #000;" id="printarea">
                    <tr style="width:100%;">
                        <td style="width:50%; text-align:center;"><strong style="font-size:20px;"> Five Ferns </strong></td>
                        <td colspan="2" style="text-align:center;width:50%;"> <img src="{{ asset('images/logo.png') }}" style="width: 100px;padding:1px 8px;"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:center; border-bottom:0px;"> <img src="<?= $slip['packages'][0]['barcode']; ?>">  </td>
                    </tr>
                    <tr>
                        <td style="border-right:0px; border-top:0px;font-size:12px; "><?= $slip['packages'][0]['pin']; ?></td>
                        <td colspan="2" style="text-align: right; border-left:0px; border-top:0px;"><strong style="font-size:12px;"><?= $slip['packages'][0]['sort_code']; ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p style="font-size:11px;margin:5px 1px;text-align:left;padding: 0px 0px; font-weight:bold;"> Shipping Address:</p>
                            <h4 style="margin: 5px 0;text-transform: uppercase;font-weight: bold;"><?= $slip['packages'][0]['name']; ?></h4>
                            <p style="font-size:11px;margin:0 1px;text-align:left;padding: 0px 0px;"><?= $slip['packages'][0]['contact']; ?>  </p>
                            <p style=" font-size:11px;margin:0 1px;text-align:left;padding: 0px 0px;"><?= $slip['packages'][0]['address']; ?> </p>
                            <p style="font-size:11px;margin:0 1px;text-align:left;padding: 0px 0px;"><?= $slip['packages'][0]['destination']; ?> </p>
                            <p style="font-size:11px;margin:0 1px;text-align:left;padding: 0px 0px;"><span><strong>  PIN:<?= $slip['packages'][0]['pin']; ?> </strong></span></p>
                        </td>
                        <td style="text-align:center;">
                            <p style="font-size:11px;"><strong><?= $slip['packages'][0]['pt']; ?></strong></p>
                            <h4 style="margin: 0;"><strong>&#8377;<?= $slip['packages'][0]['rs']; ?></strong></h4>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:left;font-size:11px;">Seller: <?= $slip['packages'][0]['snm']; ?><br>Seller GSTIN: <?= $slip['packages'][0]['consignee_gst_tin']; ?><br> Address: <?= $slip['packages'][0]['sadd']; ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Product</td>
                        <td style="text-align:center;">Price</td>
                        <td style="text-align:center;">Total</td>
                    </tr>
                    <tr>
                        <td style="height:60px;"><?= $slip['packages'][0]['prd']; ?><br>HSN:<?= $slip['packages'][0]['hsn_code']; ?> </td>
                        <td style="text-align:center;"> &#8377;<?= $slip['packages'][0]['rs']; ?></td>
                        <td style="text-align:center;"> &#8377;<?= $slip['packages'][0]['rs']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Total</strong></td>
                        <td style="text-align:center;"><strong> &#8377;<?= $slip['packages'][0]['rs']; ?></strong></td>
                        <td style="text-align:center;"><strong> &#8377;<?= $slip['packages'][0]['rs']; ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:center;"> <img src="<?= $slip['packages'][0]['oid_barcode']; ?>">  </td>
                    </tr>
                    <tr>
                        <td colspan="3"> 
                            <p style="text-align: left;font-size: 11px;margin: 0;">Return Address: <?= $slip['packages'][0]['radd']; ?></p>
                        </td>
                    </tr>
                    </table>
                    <div class="col-sm-12 text-center" style="margin-top: 15px;">
                        <input type='button' id='btn' value='Print' onclick='printFunc();'>
                    </div>
                </body>
                </html>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-footer{
    display: none
    };
</style>

<script type="text/javascript">

function printFunc() {
    var divToPrint = document.getElementById('printarea');
    var htmlToPrint = '' +
        '<style type="text/css">' +
        'table th, table td {' +
        'border:1px solid #000;' +
        'padding;0.5em;' +
        '}' +
        '</style>';
    htmlToPrint += divToPrint.outerHTML;
    newWin = window.open("");
    newWin.document.write(htmlToPrint);
    newWin.print();
    newWin.close();
    }


	$(document).ready(function() {
		$("form").submit(function(e){
			event.preventDefault();
		});
	});
</script>

