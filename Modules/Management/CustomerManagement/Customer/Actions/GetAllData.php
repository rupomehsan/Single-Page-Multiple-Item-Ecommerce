<?php

namespace Modules\Management\CustomerManagement\Customer\Actions;

class GetAllData
{
    static $model = \Modules\Management\CustomerManagement\Customer\Database\Models\Model::class;

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
    $q->where('user_id', 'like', '%' . $searchKey . '%');    

    $q->orWhere('phone_number', 'like', '%' . $searchKey . '%');    

    $q->orWhere('address', 'like', '%' . $searchKey . '%');    

    $q->orWhere('district', 'like', '%' . $searchKey . '%');    

    $q->orWhere('thana', 'like', '%' . $searchKey . '%');    

    $q->orWhere('village', 'like', '%' . $searchKey . '%');    

    $q->orWhere('total_orders', 'like', '%' . $searchKey . '%');    

    $q->orWhere('total_spent', 'like', '%' . $searchKey . '%');    

    $q->orWhere('lifetime_discount', 'like', '%' . $searchKey . '%');    

    $q->orWhere('loyalty_points', 'like', '%' . $searchKey . '%');    

    $q->orWhere('is_verified', 'like', '%' . $searchKey . '%');    

    $q->orWhere('last_order_date', 'like', '%' . $searchKey . '%');              

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