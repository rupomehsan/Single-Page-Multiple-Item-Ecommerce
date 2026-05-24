<?php

namespace Modules\Management\CMSManagement\HeroSection\Controller;
use Modules\Management\CMSManagement\HeroSection\Actions\GetAllData;
use Modules\Management\CMSManagement\HeroSection\Actions\DestroyData;
use Modules\Management\CMSManagement\HeroSection\Actions\GetSingleData;
use Modules\Management\CMSManagement\HeroSection\Actions\StoreData;
use Modules\Management\CMSManagement\HeroSection\Actions\UpdateData;
use Modules\Management\CMSManagement\HeroSection\Actions\UpdateStatus;
use Modules\Management\CMSManagement\HeroSection\Actions\SoftDelete;
use Modules\Management\CMSManagement\HeroSection\Actions\RestoreData;
use Modules\Management\CMSManagement\HeroSection\Actions\ImportData;
use Modules\Management\CMSManagement\HeroSection\Validations\BulkActionsValidation;
use Modules\Management\CMSManagement\HeroSection\Validations\DataStoreValidation;
use Modules\Management\CMSManagement\HeroSection\Actions\BulkActions;
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