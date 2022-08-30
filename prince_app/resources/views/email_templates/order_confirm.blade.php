<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        * {
            padding: 0;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <table align="center" style="background:#f1f1f1 ; font-family: 'Montserrat', sans-serif;">
        <tr>
            <td style="padding: 40px;">
                <table style="width: 600px; background: #fff;border-collapse: collapse;">
                    <tr>
                        <td>
                            <table style="text-align: center; background: #fff;padding:  25px 30px; width: 100%;">
                                <tr>
                                    <td style="padding-bottom: 15px;">
                                        <img style="width: 215px; height: 50px;object-fit: contain;" src="{{ asset('images/logo.png') }}"
                                            alt="logo-image">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table
                                            style="width: 100%; background: #768d17;border-radius: 10px; padding: 15px 0;">
                                            <tr>
                                                <td style="color:white"><b>Thanks for shopping with us</b></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">
                                        <h6 style="line-height: 20px; margin: 0;font-size: 12px;font-weight: 500;">
                                            Hi {{ ucfirst($orderResult['name']) }},
                                            <br>
                                            {{ $msg }}
                                        </h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br> 
                                        <h5 style="font-size: 16px; margin-top: 0;">[Suborder id #{{ $orderResult['getsalesdetailchild'][0]['suborder_id'] }}] ({{ date('F d,Y',strtotime($orderResult['created_at'])) }})
                                        </h5>
                                        <table border="1" class="prdouct-table1"
                                            style="font-size: 12px; border-collapse: collapse; border-color: #000; width: 100%; text-align: left;">
                                            <tr>
                                                <th style="padding: 10px;" colspan="1">S. No.</th>
                                                <th style="padding: 10px;" colspan="">Product Image</th>
                                                <th style="padding: 10px;" colspan="3">Product Name</th>
                                                <th style="padding: 10px;" colspan="2">Quantity
                                                </th>
                                                <th style="padding: 10px;">Price</th>
                                            </tr>

                                            @foreach($orderResult['getsalesdetailchild'] as $value)
                                                <tr>
                                                    <td style="text-align: center; padding: 10px;">1</td>
                                                    <td style="padding: 10px;"> <img style="width: 100px;"
                                                            src="{{ $value['product_image'] }}"
                                                            alt="Product Image">
                                                    </td>
                                                    <td style="padding: 10px;" colspan="3">
                                                       {{ ucfirst($value['product_name']) }}
                                                    </td>
                                                    <td style="text-align: center; padding: 10px;" colspan="2">{{ $value['qty'] }}</td>
                                                    <td style="text-align: center; padding: 10px;" colspan="1">{{\App\Helpers\commonHelper::getpriceIconByCountry($value['amount'],$value['currency_id'])}}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 12px; padding: 20px 0;">Thanks for shopping with us.
                                    </td>
                                </tr>
                            </table>
                            <table style="background: #f9f7fa;text-align: center; width: 100%; padding: 25px 0;">
                                <tr>
                                    <td>
                                        <h6 style="margin: 0; color: #a6a6a6;font-size: 12px; font-weight: 500;">&copy;
                                            2021. Made by <a href="" style="color: #768d17;">FiveFerns</a></h6>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>	