@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">{{ translate('Order Details') }}</h1>
        </div>
        <div class="card-body">
            <div class="row gutters-5">
                <div class="col text-md-left text-center">
                </div>
                @php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->payment_status;
                    $admin_user_id = get_admin()->id;
                @endphp
                @if ($order->seller_id == $admin_user_id || get_setting('product_manage_by_admin') == 1)

                    <!--Assign Delivery Boy-->
                    @if (addon_is_activated('delivery_boy'))
                        <div class="col-md-3 ml-auto">
                            <label for="assign_deliver_boy">{{ translate('Assign Deliver Boy') }}</label>
                            @if (($delivery_status == 'pending' || $delivery_status == 'confirmed' || $delivery_status == 'picked_up') && auth()->user()->can('assign_delivery_boy_for_orders'))
                                <select class="form-control aiz-selectpicker" data-live-search="true"
                                    data-minimum-results-for-search="Infinity" id="assign_deliver_boy">
                                    <option value="">{{ translate('Select Delivery Boy') }}</option>
                                    @foreach ($delivery_boys as $delivery_boy)
                                        <option value="{{ $delivery_boy->id }}"
                                            @if ($order->assign_delivery_boy == $delivery_boy->id) selected @endif>
                                            {{ $delivery_boy->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="form-control" value="{{ optional($order->delivery_boy)->name }}"
                                    disabled>
                            @endif
                        </div>
                    @endif

                    <div class="col-md-3 ml-auto">
                        <label for="update_payment_status">{{ translate('Payment Status') }}</label>
                        @if (auth()->user()->can('update_order_payment_status') && $payment_status == 'unpaid')
                            {{-- <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity" id="update_payment_status"> --}}
                            <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity" id="update_payment_status" onchange="confirm_payment_status()">
                                <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>
                                    {{ translate('Unpaid') }}
                                </option>
                                <option value="paid" @if ($payment_status == 'paid') selected @endif>
                                    {{ translate('Paid') }}
                                </option>
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ ucfirst($payment_status) }}" disabled>
                        @endif
                    </div>
                    <div class="col-md-3 ml-auto">
                        <label for="update_delivery_status">{{ translate('Delivery Status') }}</label>
                        @if (auth()->user()->can('update_order_delivery_status') && $delivery_status != 'delivered' && $delivery_status != 'cancelled')
                            <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                                id="update_delivery_status">
                                <option value="pending" @if ($delivery_status == 'pending') selected @endif>
                                    {{ translate('Pending') }}
                                </option>
                                <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>
                                    {{ translate('Confirmed') }}
                                </option>
                                <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>
                                    {{ translate('Picked Up') }}
                                </option>
                                <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>
                                    {{ translate('On The Way') }}
                                </option>
                                <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>
                                    {{ translate('Delivered') }}
                                </option>
                                <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>
                                    {{ translate('Cancel') }}
                                </option>
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ $delivery_status }}" disabled>
                        @endif
                    </div>
                    <div class="col-md-3 ml-auto">
                        <label for="update_tracking_code">
                            {{ translate('Tracking Code (optional)') }}
                        </label>
                        <input type="text" class="form-control" id="update_tracking_code"
                            value="{{ $order->tracking_code }}">
                    </div>
                @endif
            </div>
            <div class="mb-3">
                @php
                    $removedXML = '<?xml version="1.0" encoding="UTF-8"?>';
                @endphp
                {!! str_replace($removedXML, '', QrCode::size(100)->generate($order->code)) !!}
            </div>
            <div class="row gutters-5">
                <div class="col text-md-left text-center">
                    @if(json_decode($order->shipping_address))
                        <address>
                            <strong class="text-main">
                                {{ json_decode($order->shipping_address)->name }}
                            </strong><br>
                            {{ json_decode($order->shipping_address)->email }}<br>
                            {{ json_decode($order->shipping_address)->phone }}<br>
                            {{ json_decode($order->shipping_address)->address }}, {{ json_decode($order->shipping_address)->city }}, @if(isset(json_decode($order->shipping_address)->state)) {{ json_decode($order->shipping_address)->state }} - @endif {{ json_decode($order->shipping_address)->postal_code }}<br>
                            {{ json_decode($order->shipping_address)->country }}
                        </address>
                    @else
                        <address>
                            <strong class="text-main">
                                {{ $order->user->name }}
                            </strong><br>
                            {{ $order->user->email }}<br>
                            {{ $order->user->phone }}<br>
                        </address>
                    @endif
                    @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                        <br>
                        <strong class="text-main">{{ translate('Payment Information') }}</strong><br>
                        {{ translate('Name') }}: {{ json_decode($order->manual_payment_data)->name }},
                        {{ translate('Amount') }}:
                        {{ single_price(json_decode($order->manual_payment_data)->amount) }},
                        {{ translate('TRX ID') }}: {{ json_decode($order->manual_payment_data)->trx_id }}
                        <br>
                        <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" target="_blank">
                            <img src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt=""
                                height="100">
                        </a>
                    @endif
                </div>
                <div class="col-md-4">
                    <table class="ml-auto">
                        <tbody>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order #') }}</td>
                                <td class="text-info text-bold text-right"> {{ $order->code }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Status') }}</td>
                                <td class="text-right">
                                    @if ($delivery_status == 'delivered')
                                        <span class="badge badge-inline badge-success">
                                            {{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}
                                        </span>
                                    @else
                                        <span class="badge badge-inline badge-info">
                                            {{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Date') }} </td>
                                <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">
                                    {{ translate('Total amount') }}
                                </td>
                                <td class="text-right">
                                    {{ single_price($order->grand_total) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Payment method') }}</td>
                                <td class="text-right">
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Additional Info') }}</td>
                                <td class="text-right">{{ $order->additional_info }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="new-section-sm bord-no">

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title fs-5" id="exampleModalLabel">{{ translate('Add New Product To Order') }}</h4>
                            <button type="button" class="btn btn-close" data-dismiss="modal" aria-label="Close">x</button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('orders.store_order_detail') }}" method="POST">
                                @csrf 
                                <input type="hidden" name="order_id" value="{{ $order->id }}" id="">
                                <div class="form-group row">
                                    <label class="col-xxl-3 col-from-label fs-13">{{translate('Product')}}</label>
                                    <div class="col-xxl-9">
                                        <select class="form-control aiz-selectpicker" name="product_id" id="product_id" data-live-search="true" required>
                                            <option value="">{{ translate('Select Product') }}</option>
                                            @foreach (\App\Models\Product::where('published',1)->where('approved',1)->get() as $product)
                                                <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->id }} - {{ $product->getTranslation('name') }}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xxl-3 col-from-label fs-13">{{translate('Variation')}}</label>
                                    <div class="col-xxl-9">
                                        <input type="text" class="form-control" name="variation" value="{{ old('variation') }}" placeholder="Variation (e.g. LG, XL etc)" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xxl-3 col-from-label fs-13">{{translate('Price')}}</label>
                                    <div class="col-xxl-9">
                                        <input type="number" class="form-control" name="price" value="{{ old('price') ?? 0.00 }}"  step="0.01" placeholder="0.00" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xxl-3 col-from-label fs-13">{{translate('Quantity')}}</label>
                                    <div class="col-xxl-9">
                                        <input type="number" class="form-control" name="quantity" value="{{ old('quantity') ?? 0.00 }}"  step="0.01" placeholder="0.00" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xxl-3 col-from-label fs-13">{{translate('Tax')}}</label>
                                    <div class="col-xxl-9">
                                        <input type="number" class="form-control" name="tax" value="{{ old('tax') ?? 0.00 }}"  step="0.01" placeholder="0.00" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xxl-3 col-from-label fs-13">{{translate('Shipping Cost')}}</label>
                                    <div class="col-xxl-9">
                                        <input type="number" class="form-control" name="shipping_cost" value="{{ old('shipping_cost') ?? 0.00 }}"  step="0.01" placeholder="0.00" required>
                                    </div>
                                </div>
                                <hr> 
                                <button type="sumbit" class="btn btn-success">{{ translate('Add') }}</button> 
                            </form>
                        </div> 
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    @if(!in_array($order->delivery_status,orderStatusRestrictions()))
                        <button class="btn btn-success mb-2" style="float: right;" data-toggle="modal" data-target="#exampleModal">{{ translate('Add New') }}</button>
                    @endif
                    <form action="{{ route('orders.update_order_detail') }}" method="POST">
                        @csrf 
                        <input type="hidden" name="order_id" value="{{ $order->id }}" id="">

                        <table class="table-bordered aiz-table invoice-summary table">
                            <thead>
                                <tr class="bg-trans-dark">
                                    <th data-breakpoints="lg" class="min-col">#</th>
                                    <th width="10%">{{ translate('Photo') }}</th>
                                    <th class="text-uppercase">{{ translate('Description') }}</th>
                                    <th data-breakpoints="lg" class="text-uppercase">{{ translate('Delivery Type') }}</th>
                                    <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                        {{ translate('Qty') }}
                                    </th>
                                    <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                        {{ translate('Price') }}</th>
                                    <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                        {{ translate('Tax') }}</th>
                                    <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                        {{ translate('Shipping Cost') }}</th>
                                    <th data-breakpoints="lg" class="min-col text-uppercase text-right">
                                        {{ translate('Total') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderDetails as $key => $orderDetail) 
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                                <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank">
                                                    <img height="50" src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}">
                                                </a>
                                            @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                                <a href="{{ route('auction-product', $orderDetail->product->slug) }}" target="_blank">
                                                    <img height="50" src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}">
                                                </a>
                                            @else
                                                <strong>{{ translate('N/A') }}</strong>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                                <strong>
                                                    <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank"
                                                        class="text-muted">
                                                        {{ $orderDetail->product->getTranslation('name') }}
                                                    </a>
                                                </strong>
                                                <small>
                                                    {{-- {{ $orderDetail->variation }} --}}
                                                    @if ($orderDetail->variation != null)
                                                        <input type="text" name="order_detail[{{$orderDetail->id}}][variation]" value="{{$orderDetail->variation}}" class="form-control" id="">
                                                    @endif
                                                </small>
                                                <br>
                                                <small>
                                                    @php
                                                        $product_stock = $orderDetail->product->stocks->where('variant', $orderDetail->variation)->first();
                                                    @endphp
                                                    {{translate('SKU')}}: {{ $product_stock['sku'] ?? '' }}
                                                </small>
                                            @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                                <strong>
                                                    <a href="{{ route('auction-product', $orderDetail->product->slug) }}" target="_blank"
                                                        class="text-muted">
                                                        {{ $orderDetail->product->getTranslation('name') }}
                                                    </a>
                                                </strong>
                                            @else
                                                <strong>{{ translate('Product Unavailable') }}</strong>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                                {{ translate('Home Delivery') }}
                                            @elseif ($order->shipping_type == 'pickup_point')
                                                @if ($order->pickup_point != null)
                                                    {{ $order->pickup_point->getTranslation('name') }}
                                                    ({{ translate('Pickup Point') }})
                                                @else
                                                    {{ translate('Pickup Point') }}
                                                @endif
                                            @elseif($order->shipping_type == 'carrier')
                                                @if ($order->carrier != null)
                                                    {{ $order->carrier->name }} ({{ translate('Carrier') }})
                                                    <br>
                                                    {{ translate('Transit Time').' - '.$order->carrier->transit_time }}
                                                @else
                                                    {{ translate('Carrier') }}
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{-- {{ $orderDetail->quantity }} --}}
                                            <input type="number" name="order_detail[{{$orderDetail->id}}][quantity]" value="{{$orderDetail->quantity}}" class="form-control" id="">
                                        </td>
                                        <td class="text-center">
                                            {{-- {{ single_price($orderDetail->price / $orderDetail->quantity) }} --}}
                                            <input type="number" name="order_detail[{{$orderDetail->id}}][price]" value="{{$orderDetail->price / $orderDetail->quantity}}" class="form-control" id="">
                                        </td>
                                        <td class="text-center"> 
                                            <input type="number" name="order_detail[{{$orderDetail->id}}][tax]" value="{{$orderDetail->tax}}" class="form-control" id="">
                                        </td>
                                        <td class="text-center"> 
                                            <input type="number" name="order_detail[{{$orderDetail->id}}][shipping_cost]" value="{{$orderDetail->shipping_cost}}" class="form-control" id="">
                                        </td>
                                        <td class="text-center">
                                            {{ single_price($orderDetail->price + $orderDetail->tax + $orderDetail->shipping_cost ) }}
                                        </td>
                                        <td> 
                                            @if(!in_array($order->delivery_status,orderStatusRestrictions()))
                                                <a class="btn btn-danger" onclick="return confirm('هل انت متأكد من حذف المنتج')" href="{{ route('orders.delete_order_detail',$orderDetail->id) }}">{{ translate('Delete') }}</a>
                                            @endif
                                        </td>
                                    </tr> 
                                @endforeach
                            </tbody>
                        </table> 
                        @if(!in_array($order->delivery_status,orderStatusRestrictions()))
                            <button class="btn btn-info mb-2" style="float: right;" type="submit">{{ translate('Update') }}</button>
                            <div style="clear: both"></div>
                        @endif
                    </form>
                </div>
            </div>
            <div class="clearfix float-right">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Sub Total') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('price')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Tax') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('tax')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Shipping') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('shipping_cost')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Coupon') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->coupon_discount) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('TOTAL') }} :</strong>
                            </td>
                            <td class="text-muted h5">
                                {{ single_price($order->grand_total) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="no-print text-right">
                    <a href="{{ route('invoice.download', $order->id) }}" type="button" class="btn btn-icon btn-light"><i
                            class="las la-print"></i></a>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('modal')

    <!-- confirm payment Status Modal -->
    <div id="confirm-payment-status" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content p-2rem">
                <div class="modal-body text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="64" viewBox="0 0 72 64">
                        <g id="Octicons" transform="translate(-0.14 -1.02)">
                          <g id="alert" transform="translate(0.14 1.02)">
                            <path id="Shape" d="M40.159,3.309a4.623,4.623,0,0,0-7.981,0L.759,58.153a4.54,4.54,0,0,0,0,4.578A4.718,4.718,0,0,0,4.75,65.02H67.587a4.476,4.476,0,0,0,3.945-2.289,4.773,4.773,0,0,0,.046-4.578Zm.6,52.555H31.582V46.708h9.173Zm0-13.734H31.582V23.818h9.173Z" transform="translate(-0.14 -1.02)" fill="#ffc700" fill-rule="evenodd"/>
                          </g>
                        </g>
                    </svg>
                    <p class="mt-3 mb-3 fs-16 fw-700">{{translate('Are you sure you want to change the payment status?')}}</p>
                    <button type="button" class="btn btn-light rounded-2 mt-2 fs-13 fw-700 w-150px" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="button" onclick="update_payment_status()" class="btn btn-success rounded-2 mt-2 fs-13 fw-700 w-150px">{{translate('Confirm')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        $('#assign_deliver_boy').on('change', function() {
            var order_id = {{ $order->id }};
            var delivery_boy = $('#assign_deliver_boy').val();
            $.post('{{ route('orders.delivery-boy-assign') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                delivery_boy: delivery_boy
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Delivery boy has been assigned') }}');
            });
        });
        $('#update_delivery_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('orders.update_delivery_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
                location.reload();
            });
        });

        // Payment Status Update
        function confirm_payment_status(value){
            $('#confirm-payment-status').modal('show');
        }

        function update_payment_status(){
            $('#confirm-payment-status').modal('hide');
            var order_id = {{ $order->id }};
            $.post('{{ route('orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: 'paid'
            }, function(data) {
                $('#update_payment_status').prop('disabled', true);
                AIZ.plugins.bootstrapSelect('refresh');
                AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
                location.reload();
            });
        }

        $('#update_tracking_code').on('change', function() {
            var order_id = {{ $order->id }};
            var tracking_code = $('#update_tracking_code').val();
            $.post('{{ route('orders.update_tracking_code') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                tracking_code: tracking_code
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Order tracking code has been updated') }}');
            });
        });
    </script>
@endsection
