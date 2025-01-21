<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>استلام نقدية</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
    <style media="all">
        @page {
            margin: 0;
            padding: 0;
        }

        body {
            font-size: 0.875rem;
            font-family: '<?php echo $font_family; ?>';
            font-weight: bolder; 
            padding: 0;
            margin: 0;
        }

        .gry-color *,
        .gry-color {
            color: #000;
        }

        table {
            width: 100%;
			font-weight: bolder
        }

        table th {
            font-weight: normal;
        }

        table.padding th {
            padding: .25rem .7rem;
        }

        table.padding td {
            padding: .25rem .7rem;
        }

        table.sm-padding td {
            padding: .1rem .7rem;
        }

        .product-table td,
        .product-table th {
            border: 1px solid #000000; 
        } 
    </style>
</head>
{{-- onload="window.print()" --}}
<body>
    @php
        $logo = get_setting('header_logo');
    @endphp

    <div> 
        
        <h2 style="text-align: center;margin-top:14%;position: relative">
            <div style="position: absolute;left:10%;top:-24px"> 
                @if ($logo != null)
                    <img src="{{ uploaded_asset($logo) }}" height="90" style="display:inline-block;">
                @else
                    <img src="{{ static_asset('assets/img/logo.png') }}" height="90"
                        style="display:inline-block;">
                @endif 
            </div>
            إيصال إستلام / صرف
            <br>
            نقدية / شيكات
        </h2> 
        @php
            $shipping_address = json_decode($order->shipping_address);
        @endphp 
        <table style="padding: 0 6.5rem 1.5rem 6.5rem;float: left;position: relative;"> 
            <tr>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:30px"> </td>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:30px">
                    <span class="gry-color strong"
                        style="float:right"><span>{{ date('Y/m/d') }}</span>: تحرير في </span>
                </td>
            </tr>

            <tr>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:30px"> </td>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:30px">
                    <span class="gry-color strong" style="float:right">
                        <span style="float:right"> استلمت من السيد</span>
                        <span style="float:right"> : </span>
                        <span>
                            {{ $shipping_address->name }} 
                        </span>
                    </span>
                </td>
            </tr>

            <tr>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:30px"> </td>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:30px">  
                    <span class="gry-color strong" style="float:right">................................: مبلغ وقدره</span>
                </td>
            </tr>

            <tr>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:30px"><span> </span> <span
                        class="gry-color strong" style="float:right">...........................................: مسحوب
                        علي بنك</span></td>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:30px"><span> </span> <span
                        class="gry-color strong" style="float:right">...........................................: نقدا /
                        بشيك رقم</span></td>
            </tr>

            <tr>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:30px"> </td>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:30px">  
                    <span class="gry-color strong" style="float:right">................................: وذلك قيمة</span>
                </td>
            </tr> 


        </table>

        <table style="padding:0 6.5rem 1.0rem 6.5rem ;float: right;position: relative;">

            <tr>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:30px"><span> </span> <span
                        class="gry-color strong" style="float:right;text-align:center;">
                        المستلم</span></td>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:30px"><span> </span> <span
                        class="gry-color strong" style="float:right;text-align:center;">
                        الحسابات</span></td>
            </tr>
            <tr>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:30px"><span> </span> <span
                        class="gry-color strong"
                        style="float:right">.............................................</span></td>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:30px"><span> </span> <span
                        class="gry-color strong"
                        style="float:right">.............................................</span></td>
            </tr>
        </table>
        
        <hr width="850" style="border: 1px solid grey;">
        <table style="padding:0 6.5rem 1.5rem 6.5rem ;float: right;position: relative;">
            <tr>  
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px">
                    <span class="gry-color strong" style="float:right">
                        <span style="float:right">تليفون</span>
                        <span style="float:right"> : &nbsp;</span>
                        <span>
                            {{ get_setting('contact_phone', null, $language_code) }}
                        </span>
                    </span> 
                </td>
                <td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px">
                    <span class="gry-color strong" style="float:right">
                        <span style="float:right">العنوان</span>
                        <span style="float:right"> : &nbsp;</span>
                        <span>
                            {{ get_setting('contact_address', null, $language_code) }}
                        </span>
                    </span> 
                </td>
            </tr>
        </table>
    </div> 

</body>

</html>
