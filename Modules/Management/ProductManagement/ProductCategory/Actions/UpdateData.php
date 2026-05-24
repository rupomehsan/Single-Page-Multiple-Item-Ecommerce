<?php

namespace Modules\Management\ProductManagement\ProductCategory\Actions;

class UpdateData
{
    static $model = \Modules\Management\ProductManagement\ProductCategory\Database\Models\Model::class;

    public static function execute($request,$slug)
    {
        try {
            if (!$data = self::$model::query()->where('slug', $slug)->first()) {
                return messageResponse('Data not found...',$data, 404, 'error');
            }
            $requestData = $request->validated();

             // Process file uploads for specific fields
                            if ($request->hasFile('category_image')) {
                    $file = $request->file('category_image');
                    $requestData['category_image'] = uploader($file, 'uploads/ProductManagement/ProductCategory');
                }
  
            $data->update($requestData);
            return messageResponse('Item updated successfully',$data, 201);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}