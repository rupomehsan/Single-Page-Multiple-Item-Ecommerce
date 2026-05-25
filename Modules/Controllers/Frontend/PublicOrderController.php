<?php

namespace Modules\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Management\OrderManagement\Order\Database\Models\Model as Order;
use Modules\Management\OrderManagement\Order\Database\Models\OrderItemModel;
use Modules\Management\PromoCodeManagement\PromoCode\Database\Models\Model as PromoCode;
use Modules\Management\DeliveryManagement\DeliveryArea\Database\Models\Model as DeliveryArea;

class PublicOrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:20',
            'customer_email'   => 'nullable|email|max:255',
            'delivery_address' => 'required|string',
            'delivery_area_id' => 'nullable|integer',
            'delivery_district'=> 'nullable|string|max:100',
            'delivery_thana'   => 'nullable|string|max:100',
            'shipping_charge'  => 'required|numeric|min:0',
            'subtotal'         => 'required|numeric|min:0',
            'discount_amount'  => 'nullable|numeric|min:0',
            'promo_code_used'  => 'nullable|string|max:100',
            'total_amount'     => 'required|numeric|min:0',
            'special_notes'    => 'nullable|string',
            'items'            => 'required|array|min:1',
            'items.*.product_id'   => 'required|integer',
            'items.*.product_name' => 'required|string',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.unit_price'   => 'required|numeric|min:0',
            'items.*.total_price'  => 'required|numeric|min:0',
        ]);

        try {
            $orderNumber = 'ORD-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8)) . '-' . date('ymd');

            $order = Order::create([
                'order_number'     => $orderNumber,
                'customer_name'    => $validated['customer_name'],
                'customer_phone'   => $validated['customer_phone'],
                'customer_email'   => $validated['customer_email'] ?? null,
                'delivery_address' => $validated['delivery_address'],
                'delivery_area_id' => $validated['delivery_area_id'] ?? null,
                'delivery_district'=> $validated['delivery_district'] ?? null,
                'delivery_thana'   => $validated['delivery_thana'] ?? null,
                'subtotal'         => $validated['subtotal'],
                'shipping_charge'  => $validated['shipping_charge'],
                'discount_amount'  => $validated['discount_amount'] ?? 0,
                'promo_code_used'  => $validated['promo_code_used'] ?? null,
                'total_amount'     => $validated['total_amount'],
                'special_notes'    => $validated['special_notes'] ?? null,
                'payment_method'   => 'cash_on_delivery',
                'payment_status'   => 'pending',
                'order_status'     => 'pending',
                'source'           => 'website',
                'ip_address'       => $request->ip(),
                'status'           => 'active',
            ]);

            foreach ($validated['items'] as $item) {
                OrderItemModel::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $item['unit_price'],
                    'total_price'  => $item['total_price'],
                    'status'       => 'active',
                ]);
            }

            return response()->json([
                'status'       => 'success',
                'message'      => 'অর্ডার সফলভাবে দেওয়া হয়েছে!',
                'order_number' => $order->order_number,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'অর্ডার দেওয়া সম্ভব হয়নি। আবার চেষ্টা করুন।',
            ], 500);
        }
    }

    public function applyPromo(Request $request)
    {
        $request->validate([
            'code'     => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $code     = strtoupper(trim($request->code));
        $subtotal = (float) $request->subtotal;

        $promo = PromoCode::where('code', $code)
            ->where('status', 'active')
            ->where('is_active', 'yes')
            ->first();

        if (!$promo) {
            return response()->json(['status' => 'error', 'message' => 'অবৈধ প্রোমো কোড।'], 404);
        }

        if ($promo->valid_until && now()->gt($promo->valid_until)) {
            return response()->json(['status' => 'error', 'message' => 'প্রোমো কোডের মেয়াদ শেষ হয়ে গেছে।'], 422);
        }

        if ($promo->minimum_order_amount && $subtotal < (float) $promo->minimum_order_amount) {
            return response()->json([
                'status'  => 'error',
                'message' => "ন্যূনতম অর্ডার ৳{$promo->minimum_order_amount} হতে হবে।",
            ], 422);
        }

        if ($promo->max_usage && $promo->current_usage >= $promo->max_usage) {
            return response()->json(['status' => 'error', 'message' => 'এই প্রোমো কোডের ব্যবহার সীমা শেষ।'], 422);
        }

        $discount = 0;
        if ($promo->discount_type === 'percentage') {
            $discount = ($subtotal * (float) $promo->discount_value) / 100;
            if ($promo->maximum_discount_amount && $discount > (float) $promo->maximum_discount_amount) {
                $discount = (float) $promo->maximum_discount_amount;
            }
        } else {
            $discount = min((float) $promo->discount_value, $subtotal);
        }

        return response()->json([
            'status'   => 'success',
            'discount' => round($discount),
            'code'     => $code,
            'message'  => "ছাড় প্রযোজ্য হয়েছে — ৳" . round($discount),
        ]);
    }

    public function deliveryAreas()
    {
        try {
            $areas = DeliveryArea::where('status', 'active')
                ->get(['id', 'area_name', 'delivery_charge']);
            return response()->json(['status' => 'success', 'data' => $areas]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'success', 'data' => []]);
        }
    }
}
