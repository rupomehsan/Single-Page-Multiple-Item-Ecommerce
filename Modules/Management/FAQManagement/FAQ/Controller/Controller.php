<?php

namespace Modules\Management\FAQManagement\FAQ\Controller;
use Modules\Management\FAQManagement\FAQ\Actions\GetAllData;
use Modules\Management\FAQManagement\FAQ\Actions\DestroyData;
use Modules\Management\FAQManagement\FAQ\Actions\GetSingleData;
use Modules\Management\FAQManagement\FAQ\Actions\StoreData;
use Modules\Management\FAQManagement\FAQ\Actions\UpdateData;
use Modules\Management\FAQManagement\FAQ\Actions\UpdateStatus;
use Modules\Management\FAQManagement\FAQ\Actions\SoftDelete;
use Modules\Management\FAQManagement\FAQ\Actions\RestoreData;
use Modules\Management\FAQManagement\FAQ\Actions\ImportData;
use Modules\Management\FAQManagement\FAQ\Validations\BulkActionsValidation;
use Modules\Management\FAQManagement\FAQ\Validations\DataStoreValidation;
use Modules\Management\FAQManagement\FAQ\Actions\BulkActions;
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