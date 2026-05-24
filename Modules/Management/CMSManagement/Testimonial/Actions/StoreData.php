<?php

namespace Modules\Management\CMSManagement\Testimonial\Actions;

class StoreData
{
    static $model = \Modules\Management\CMSManagement\Testimonial\Database\Models\Model::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();

            // Process file uploads for specific fields
                            if ($request->hasFile('customer_image')) {
                    $file = $request->file('customer_image');
                    $requestData['customer_image'] = uploader($file, 'uploads/CMSManagement/Testimonial');
                }
          
            if ($data = self::$model::query()->create($requestData)) {
                return messageResponse('Item added successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}