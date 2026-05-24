<?php

namespace Modules\Management\OrderManagement\Order\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class OrderItemModel extends EloquentModel
{
    protected $table = "order_items";
    protected $guarded = [];
}