<?php

namespace Modules\Management\OrderManagement\Order\Actions;

class GetAllData
{
    static $model = \Modules\Management\OrderManagement\Order\Database\Models\Model::class;

    public static function execute()
    {
        try {

            $pageLimit = request()->input('limit') ?? 10;
            $orderByColumn = request()->input('sort_by_col') ?? 'id';
            $orderByType = request()->input('sort_type') ?? 'desc';
            $status = request()->input('status') ?? 'active';
            $fields = request()->input('fields') ?? '*';
            $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');

                            $with = [];

            $condition = [];

            $data = self::$model::query();

            if (request()->has('search') && request()->input('search')) {
                $searchKey = request()->input('search');
                $data = $data->where(function ($q) use ($searchKey) {
    $q->where('order_number', 'like', '%' . $searchKey . '%');    

    $q->orWhere('customer_name', 'like', '%' . $searchKey . '%');    

    $q->orWhere('customer_phone', 'like', '%' . $searchKey . '%');    

    $q->orWhere('customer_email', 'like', '%' . $searchKey . '%');    

    $q->orWhere('delivery_address', 'like', '%' . $searchKey . '%');    

    $q->orWhere('delivery_area_id', 'like', '%' . $searchKey . '%');    

    $q->orWhere('delivery_district', 'like', '%' . $searchKey . '%');    

    $q->orWhere('delivery_thana', 'like', '%' . $searchKey . '%');    

    $q->orWhere('delivery_village', 'like', '%' . $searchKey . '%');    

    $q->orWhere('delivery_date', 'like', '%' . $searchKey . '%');    

    $q->orWhere('delivered_date', 'like', '%' . $searchKey . '%');    

    $q->orWhere('subtotal', 'like', '%' . $searchKey . '%');    

    $q->orWhere('shipping_charge', 'like', '%' . $searchKey . '%');    

    $q->orWhere('discount_amount', 'like', '%' . $searchKey . '%');    

    $q->orWhere('promo_code_used', 'like', '%' . $searchKey . '%');    

    $q->orWhere('total_amount', 'like', '%' . $searchKey . '%');    

    $q->orWhere('payment_method', 'like', '%' . $searchKey . '%');    

    $q->orWhere('payment_status', 'like', '%' . $searchKey . '%');    

    $q->orWhere('order_status', 'like', '%' . $searchKey . '%');    

    $q->orWhere('special_notes', 'like', '%' . $searchKey . '%');    

    $q->orWhere('admin_notes', 'like', '%' . $searchKey . '%');    

    $q->orWhere('ip_address', 'like', '%' . $searchKey . '%');    

    $q->orWhere('source', 'like', '%' . $searchKey . '%');              

                });
            }

            if ($start_date && $end_date) {
                 if ($end_date > $start_date) {
                    $data->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                } elseif ($end_date == $start_date) {
                    $data->whereDate('created_at', $start_date);
                }
            }

            if ($status == 'trashed') {
                $data = $data->onlyTrashed();
            }

            if (request()->has('get_all') && (int)request()->input('get_all') === 1) {
                $data = $data
                    ->with($with)
                    ->select($fields)
                    ->where($condition)
                    ->where('status', $status)
                    ->limit($pageLimit)
                    ->orderBy($orderByColumn, $orderByType)
                    ->get();
                     return entityResponse($data);
            } else if ($status == 'trashed') {
                $data = $data
                    ->with($with)
                    ->select($fields)
                    ->where($condition)
                    ->orderBy($orderByColumn, $orderByType)
                    ->paginate($pageLimit);
            } else {
                $data = $data
                    ->with($with)
                    ->select($fields)
                    ->where($condition)
                    ->where('status', $status)
                    ->orderBy($orderByColumn, $orderByType)
                    ->paginate($pageLimit);
            }

            return entityResponse([
                ...$data->toArray(),
                "active_data_count" => self::$model::active()->count(),
                "inactive_data_count" => self::$model::inactive()->count(),
                "trashed_data_count" => self::$model::onlyTrashed()->count(),
            ]);

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}