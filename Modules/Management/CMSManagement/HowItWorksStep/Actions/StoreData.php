<?php

namespace Modules\Management\CMSManagement\HowItWorksStep\Actions;

class StoreData
{
    static $model = \Modules\Management\CMSManagement\HowItWorksStep\Database\Models\Model::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();

            // Process file uploads for specific fields
                            if ($request->hasFile('icon_image')) {
                    $file = $request->file('icon_image');
                    $requestData['icon_image'] = uploader($file, 'uploads/CMSManagement/HowItWorksStep');
                }
          
            if ($data = self::$model::query()->create($requestData)) {
                return messageResponse('Item added successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}