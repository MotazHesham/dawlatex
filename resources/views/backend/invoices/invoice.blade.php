<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ translate('INVOICE') }}</title>
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
            direction: <?php echo $direction; ?>;
            text-align: <?php echo $text_align; ?>;
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

        .text-left {
            text-align: <?php echo $text_align; ?>;
        }

        .text-right {
            text-align: <?php echo $not_text_align; ?>;
        } 
    </style>
</head>

<body onload="window.print()">
    <div>

        @php
            $logo = get_setting('header_logo');
        @endphp

        <div style="padding: 1rem;">
            <table style="padding:0 30px">
                <tr>
                    <td style="font-size: 1.5rem;" class="text-left strong">
                        <div style="width: fit-content;text-align:center">
                            <b style="font-size: 45px">دوله تكس</b>
                            <br>
                            <small>للغزل والنسيج</small>
                        </div>
                    </td>
                    <td class="text-right">
                        @if ($logo != null)
                            <img src="{{ uploaded_asset($logo) }}" height="90" style="display:inline-block;">
                        @else
                            <img src="{{ static_asset('assets/img/logo.png') }}" height="90"
                                style="display:inline-block;">
                        @endif
                    </td>
                </tr>
            </table>
            <table style="padding:0 30px">
                <tr>
                    <td class="gry-color small" style="width: 240px">{{ translate('Address') }}:
                        {{ get_setting('contact_address', null, $language_code) }}</td>
                    <td class="text-right"> {{ get_setting('contact_email') }}</td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ translate('Phone') }}: {{ get_setting('contact_phone') }}</td>
                    <td class="text-right small"> {{ get_setting('contact_phone') }}</td>
                </tr>
                <tr>
                    <td class="gry-color small">سجل تجاري رقم /250112</td>
                    <td class="text-right small"></td>
                </tr>
                <tr>
                    <td class="gry-color small">بطاقة ضريبية رقم /472-322-768</td>
                    <td class="text-right small"></td>
                </tr>
            </table>

        </div>

        <div style="padding: 1rem;padding-bottom: 0;text-align: center">
            <div class="gry-color">فاتورة </div> <span class="strong" style="text-align: right">{{ $order->code }}</span>
        </div>
        <div style="padding: 1rem;padding-bottom: 0">
            <table>
                <tr>
                    <td class="gry-color small">التاريخ: {{ date('Y/m/d', $order->date) }}</td>
                    <td class="text-right">Date: {{ date('d/m/Y', $order->date) }}</td>
                </tr>
            </table>
        </div>
        <div style="padding: 1rem;padding-bottom: 0">
            <table>
                @php
                    $shipping_address = json_decode($order->shipping_address);
                @endphp
                <tr>
                    <td class="gry-color small">المطلوب من: {{ isset($vendor) && $vendor ? ( $order->seller ? $order->seller->name : '') : $shipping_address->name }} </td>
                    <td class="text-right"> :Requested From </td>
                </tr>
            </table>
        </div> 
        <div style="padding: 1rem;">
            <table class="padding text-left small product-table" style="border-style:hidden;box-shadow:0 0 1px 1px #000;border-collapse:collapse;border-radius:15px;">
                <thead>
					@php
						$total_tax = $order->orderDetails->sum('tax');
						$total_shipping = $order->orderDetails->sum('shipping_cost');
					@endphp
                    <tr>
						<th width="5%">م</th>
                        <th width="35%" class="text-left">البيان</th>
                        <th width="10%" class="text-left">{{ translate('Qty') }}</th>
                        <th width="15%" class="text-left">{{ translate('Unit Price') }}</th>
                        @if ($total_tax > 0)
                            <th width="10%" class="text-left">{{ translate('Tax') }}</th>
                        @endif 
                        <th width="15%" class="text-right">الإجمالي</th>
                    </tr>
                </thead>
                <tbody class="strong">
                    @foreach ($order->orderDetails as $key => $orderDetail)
                        @if ($orderDetail->product != null)
                            <tr class="">
								<td>{{ $key + 1 }}</td>
                                <td>
                                    {{ $orderDetail->product->name }}
                                    @if ($orderDetail->variation != null)
                                        ({{ $orderDetail->variation }})
                                    @endif
                                </td>
                                <td class="">{{ $orderDetail->quantity }}</td>
                                <td class="currency">{{ single_price($orderDetail->price / $orderDetail->quantity) }}</td>
                                @if ($orderDetail->tax > 0)
                                    <td class="currency">{{ single_price($orderDetail->tax / $orderDetail->quantity) }}
                                    </td>
                                @endif 
                                <td class="text-right currency">
                                    {{ single_price($orderDetail->price + $orderDetail->tax) }}</td>
                            </tr>
                        @endif
                    @endforeach
					<tr>
						<td @if ($total_tax > 0)  colspan="5" @else colspan="4" @endif>فقط وقدره : ............................................................................</td> 
						<td>
							@if($total_shipping > 0)
								+شحن {{ single_price($total_shipping) }}
							@endif
							<br>
							{{ single_price($order->grand_total) }}
						</td>
					</tr>
                </tbody>
            </table>
        </div>

        <div style="padding: 1rem;padding-bottom: 0;text-align: center">
            <b class="gry-color">يعتمد،،،</span>
        </div>
        <div style="padding: 1rem;padding-bottom: 0">
            <table>
                <tr>
                    <td class="gry-color small" style="padding-right:40px">المحاسب: .......................................</td>
                    <td class="text-right" style="padding-left:40px">المدير المالي: .......................................</td>
                </tr>
            </table>
        </div>  
    </div> 
</body>

</html>
