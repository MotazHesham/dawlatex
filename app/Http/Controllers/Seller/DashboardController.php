<?php

namespace App\Http\Controllers\Seller;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Auth;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $authUserId = auth()->user()->id;
        $data['this_month_pending_orders'] = OrderDetail::whereSellerId($authUserId)
                                    ->whereDeliveryStatus('pending')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
        $data['this_month_cancelled_orders'] = OrderDetail::whereSellerId($authUserId)
                                    ->whereDeliveryStatus('cancelled')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
        $data['this_month_on_the_way_orders'] = OrderDetail::whereSellerId($authUserId)
                                    ->whereDeliveryStatus('on_the_way')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
        $data['this_month_delivered_orders'] = OrderDetail::whereSellerId($authUserId)
                                    ->whereDeliveryStatus('delivered')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
                                    
        $data['this_month_sold_amount'] = DB::table('orders')
                                            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                                            ->whereNull('orders.deleted_at')
                                            ->where('orders.seller_id', Auth::user()->id)
                                            ->where('orders.payment_status', 'paid')
                                            ->whereYear('orders.created_at', Carbon::now()->year)
                                            ->whereMonth('orders.created_at', Carbon::now()->month)
                                            ->sum('order_details.purchase_price');
        $data['previous_month_sold_amount'] = DB::table('orders')
                                                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                                                ->whereNull('orders.deleted_at')
                                                ->where('orders.seller_id', Auth::user()->id)
                                                ->where('orders.payment_status', 'paid')
                                                ->whereYear('orders.created_at', Carbon::now()->year)
                                                ->whereMonth('orders.created_at', Carbon::now()->subMonth()->month)
                                                ->sum('order_details.purchase_price');
        
        $data['products'] = filter_products(Product::where('user_id', Auth::user()->id)->orderBy('num_of_sale', 'desc'))->limit(12)->get();
        $data['last_7_days_sales'] = DB::table('orders')
                                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                                    ->whereNull('orders.deleted_at')
                                    ->where('orders.created_at', '>=', Carbon::now()->subDays(7))
                                    ->where('orders.seller_id', '=', Auth::user()->id)
                                    ->where('orders.delivery_status', '=', 'delivered')
                                    ->select(
                                        DB::raw("sum(order_details.purchase_price) as total"),
                                        DB::raw("DATE_FORMAT(orders.created_at, '%d %b') as date")
                                    )
                                    ->groupBy(DB::raw("DATE_FORMAT(orders.created_at, '%Y-%m-%d')"))
                                    ->pluck('total', 'date');  

        return view('seller.dashboard', $data);
    }
}
