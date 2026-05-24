<?php

namespace Modules\Management\ProductManagement\ProductCategory\Actions;

class StoreData
{
    static $model = \Modules\Management\ProductManagement\ProductCategory\Database\Models\Model::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();

            // Process file uploads for specific fields
                            if ($request->hasFile('category_image')) {
                    $file = $request->file('category_image');
                    $requestData['category_image'] = uploader($file, 'uploads/ProductManagement/ProductCategory');
                }
          
            if ($data = self::$model::query()->create($requestData)) {
                return messageResponse('Item added successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}