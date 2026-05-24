<?php

namespace Modules\Management\DeliveryManagement\DeliveryArea\Controller;
use Modules\Management\DeliveryManagement\DeliveryArea\Actions\GetAllData;
use Modules\Management\DeliveryManagement\DeliveryArea\Actions\DestroyData;
use Modules\Management\DeliveryManagement\DeliveryArea\Actions\GetSingleData;
use Modules\Management\DeliveryManagement\DeliveryArea\Actions\StoreData;
use Modules\Management\DeliveryManagement\DeliveryArea\Actions\UpdateData;
use Modules\Management\DeliveryManagement\DeliveryArea\Actions\UpdateStatus;
use Modules\Management\DeliveryManagement\DeliveryArea\Actions\SoftDelete;
use Modules\Management\DeliveryManagement\DeliveryArea\Actions\RestoreData;
use Modules\Management\DeliveryManagement\DeliveryArea\Actions\ImportData;
use Modules\Management\DeliveryManagement\DeliveryArea\Validations\BulkActionsValidation;
use Modules\Management\DeliveryManagement\DeliveryArea\Validations\DataStoreValidation;
use Modules\Management\DeliveryManagement\DeliveryArea\Actions\BulkActions;
use App\Http\Controllers\Controller as ControllersController;


class Controller extends ControllersController
{

    public function index( ){

        $data = GetAllData::execute();
        return $data;
    }

    public function store(DataStoreValidation $request)
    {
        $data = StoreData::execute($request);
        return $data;
    }

    public function show($slug)
    {
        $data = GetSingleData::execute($slug);
        return $data;
    }

    public function update(DataStoreValidation $request, $slug)
    {
        $data = UpdateData::execute($request, $slug);
        return $data;
    }
         public function updateStatus()
    {
        $data = UpdateStatus::execute();
        return $data;
    }

    public function softDelete()
    {
        $data = SoftDelete::execute();
        return $data;
    }
    public function destroy($slug)
    {
        $data = DestroyData::execute($slug);
        return $data;
    }
    public function restore()
    {
        $data = RestoreData::execute();
        return $data;
    }
    public function import()
    {
        $data = ImportData::execute();
        return $data;
    }
    public function bulkAction(BulkActionsValidation $request)
    {
        $data = BulkActions::execute($request);
        return $data;
    }

}