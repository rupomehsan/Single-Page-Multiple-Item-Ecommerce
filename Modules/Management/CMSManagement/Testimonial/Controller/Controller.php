<?php

namespace Modules\Management\CMSManagement\Testimonial\Controller;
use Modules\Management\CMSManagement\Testimonial\Actions\GetAllData;
use Modules\Management\CMSManagement\Testimonial\Actions\DestroyData;
use Modules\Management\CMSManagement\Testimonial\Actions\GetSingleData;
use Modules\Management\CMSManagement\Testimonial\Actions\StoreData;
use Modules\Management\CMSManagement\Testimonial\Actions\UpdateData;
use Modules\Management\CMSManagement\Testimonial\Actions\UpdateStatus;
use Modules\Management\CMSManagement\Testimonial\Actions\SoftDelete;
use Modules\Management\CMSManagement\Testimonial\Actions\RestoreData;
use Modules\Management\CMSManagement\Testimonial\Actions\ImportData;
use Modules\Management\CMSManagement\Testimonial\Validations\BulkActionsValidation;
use Modules\Management\CMSManagement\Testimonial\Validations\DataStoreValidation;
use Modules\Management\CMSManagement\Testimonial\Actions\BulkActions;
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