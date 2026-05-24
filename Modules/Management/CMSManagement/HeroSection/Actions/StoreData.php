<?php

namespace Modules\Management\CMSManagement\HeroSection\Actions;

class StoreData
{
    static $model = \Modules\Management\CMSManagement\HeroSection\Database\Models\Model::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();

            // Process file uploads for specific fields
                            if ($request->hasFile('background_image')) {
                    $file = $request->file('background_image');
                    $requestData['background_image'] = uploader($file, 'uploads/CMSManagement/HeroSection');
                }
          
            if ($data = self::$model::query()->create($requestData)) {
                return messageResponse('Item added successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}