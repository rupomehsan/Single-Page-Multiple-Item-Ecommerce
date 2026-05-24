<?php

namespace Modules\Management\CMSManagement\Banner\Actions;

class UpdateData
{
    static $model = \Modules\Management\CMSManagement\Banner\Database\Models\Model::class;

    public static function execute($request,$slug)
    {
        try {
            if (!$data = self::$model::query()->where('slug', $slug)->first()) {
                return messageResponse('Data not found...',$data, 404, 'error');
            }
            $requestData = $request->validated();

             // Process file uploads for specific fields
                            if ($request->hasFile('banner_image')) {
                    $file = $request->file('banner_image');
                    $requestData['banner_image'] = uploader($file, 'uploads/CMSManagement/Banner');
                }
                if ($request->hasFile('banner_image_mobile')) {
                    $file = $request->file('banner_image_mobile');
                    $requestData['banner_image_mobile'] = uploader($file, 'uploads/CMSManagement/Banner');
                }
  
            $data->update($requestData);
            return messageResponse('Item updated successfully',$data, 201);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}