<?php

namespace Modules\Management\ReviewManagement\Review\Controller;
use Modules\Management\ReviewManagement\Review\Actions\GetAllData;
use Modules\Management\ReviewManagement\Review\Actions\DestroyData;
use Modules\Management\ReviewManagement\Review\Actions\GetSingleData;
use Modules\Management\ReviewManagement\Review\Actions\StoreData;
use Modules\Management\ReviewManagement\Review\Actions\UpdateData;
use Modules\Management\ReviewManagement\Review\Actions\UpdateStatus;
use Modules\Management\ReviewManagement\Review\Actions\SoftDelete;
use Modules\Management\ReviewManagement\Review\Actions\RestoreData;
use Modules\Management\ReviewManagement\Review\Actions\ImportData;
use Modules\Management\ReviewManagement\Review\Validations\BulkActionsValidation;
use Modules\Management\ReviewManagement\Review\Validations\DataStoreValidation;
use Modules\Management\ReviewManagement\Review\Actions\BulkActions;
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