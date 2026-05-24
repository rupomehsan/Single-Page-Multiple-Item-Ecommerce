<?php

namespace Modules\Management\ProductManagement\ProductCategory\Controller;
use Modules\Management\ProductManagement\ProductCategory\Actions\GetAllData;
use Modules\Management\ProductManagement\ProductCategory\Actions\DestroyData;
use Modules\Management\ProductManagement\ProductCategory\Actions\GetSingleData;
use Modules\Management\ProductManagement\ProductCategory\Actions\StoreData;
use Modules\Management\ProductManagement\ProductCategory\Actions\UpdateData;
use Modules\Management\ProductManagement\ProductCategory\Actions\UpdateStatus;
use Modules\Management\ProductManagement\ProductCategory\Actions\SoftDelete;
use Modules\Management\ProductManagement\ProductCategory\Actions\RestoreData;
use Modules\Management\ProductManagement\ProductCategory\Actions\ImportData;
use Modules\Management\ProductManagement\ProductCategory\Validations\BulkActionsValidation;
use Modules\Management\ProductManagement\ProductCategory\Validations\DataStoreValidation;
use Modules\Management\ProductManagement\ProductCategory\Actions\BulkActions;
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