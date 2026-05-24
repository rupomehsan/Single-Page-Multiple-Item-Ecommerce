<?php

namespace Modules\Management\ProductManagement\Product\Controller;
use Modules\Management\ProductManagement\Product\Actions\GetAllData;
use Modules\Management\ProductManagement\Product\Actions\DestroyData;
use Modules\Management\ProductManagement\Product\Actions\GetSingleData;
use Modules\Management\ProductManagement\Product\Actions\StoreData;
use Modules\Management\ProductManagement\Product\Actions\UpdateData;
use Modules\Management\ProductManagement\Product\Actions\UpdateStatus;
use Modules\Management\ProductManagement\Product\Actions\SoftDelete;
use Modules\Management\ProductManagement\Product\Actions\RestoreData;
use Modules\Management\ProductManagement\Product\Actions\ImportData;
use Modules\Management\ProductManagement\Product\Validations\BulkActionsValidation;
use Modules\Management\ProductManagement\Product\Validations\DataStoreValidation;
use Modules\Management\ProductManagement\Product\Actions\BulkActions;
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