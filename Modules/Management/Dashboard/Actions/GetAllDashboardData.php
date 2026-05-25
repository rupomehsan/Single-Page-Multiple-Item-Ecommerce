<?php

namespace Modules\Management\Dashboard\Actions;

use Illuminate\Support\Facades\DB;

class GetAllDashboardData
{
    public static function execute()
    {
        try {
            $orderModel    = \Modules\Management\OrderManagement\Order\Database\Models\Model::class;
            $customerModel = \Modules\Management\CustomerManagement\Customer\Database\Models\Model::class;
            $productModel  = \Modules\Management\ProductManagement\Product\Database\Models\Model::class;

            // ── KPI ──────────────────────────────────────────────────────
            $total_orders    = $orderModel::count();
            $total_revenue   = $orderModel::whereNotIn('order_status', ['cancelled', 'returned'])
                                    ->sum(DB::raw('CAST(total_amount AS DECIMAL(15,2))'));
            $total_customers = $customerModel::count();
            $total_products  = $productModel::count();

            $today_orders    = $orderModel::whereDate('created_at', today())->count();
            $today_revenue   = $orderModel::whereDate('created_at', today())
                                    ->whereNotIn('order_status', ['cancelled', 'returned'])
                                    ->sum(DB::raw('CAST(total_amount AS DECIMAL(15,2))'));
            $pending_orders  = $orderModel::where('order_status', 'pending')->count();
            $delivered_orders= $orderModel::where('order_status', 'delivered')->count();
            $total_reviews   = \Modules\Management\ReviewManagement\Review\Database\Models\Model::count();

            // ── Order status distribution ─────────────────────────────────
            $status_rows = $orderModel::select('order_status', DB::raw('count(*) as total'))
                ->groupBy('order_status')
                ->get();
            $order_status = [];
            foreach ($status_rows as $row) {
                $order_status[$row->order_status ?? 'unknown'] = (int) $row->total;
            }

            // ── Monthly orders + revenue (last 12 months) ─────────────────
            $monthly_raw = $orderModel::select(
                    DB::raw('YEAR(created_at)  AS yr'),
                    DB::raw('MONTH(created_at) AS mo'),
                    DB::raw('COUNT(*)           AS orders'),
                    DB::raw('SUM(CAST(total_amount AS DECIMAL(15,2))) AS revenue')
                )
                ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
                ->groupBy('yr', 'mo')
                ->orderBy('yr')->orderBy('mo')
                ->get()
                ->keyBy(fn($r) => $r->yr . '-' . $r->mo);

            $labels = $monthly_orders = $monthly_revenue = [];
            for ($i = 11; $i >= 0; $i--) {
                $d   = now()->subMonths($i);
                $key = $d->year . '-' . $d->month;
                $labels[]          = $d->format('M y');
                $monthly_orders[]  = isset($monthly_raw[$key]) ? (int)   $monthly_raw[$key]->orders  : 0;
                $monthly_revenue[] = isset($monthly_raw[$key]) ? (float) $monthly_raw[$key]->revenue : 0;
            }

            // ── Recent 5 orders ───────────────────────────────────────────
            $recent_orders = $orderModel::latest()
                ->limit(5)
                ->get(['order_number', 'customer_name', 'customer_phone',
                       'total_amount', 'order_status', 'payment_status', 'created_at']);

            $data = [
                'kpi' => [
                    'total_revenue'   => round($total_revenue, 2),
                    'total_orders'    => $total_orders,
                    'total_customers' => $total_customers,
                    'total_products'  => $total_products,
                    'today_orders'     => $today_orders,
                    'today_revenue'    => round($today_revenue, 2),
                    'pending_orders'   => $pending_orders,
                    'delivered_orders' => $delivered_orders,
                    'total_reviews'    => $total_reviews,
                ],
                'order_status'   => $order_status,
                'monthly'        => [
                    'labels'  => $labels,
                    'orders'  => $monthly_orders,
                    'revenue' => $monthly_revenue,
                ],
                'recent_orders'  => $recent_orders,
            ];

            return entityResponse($data);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
