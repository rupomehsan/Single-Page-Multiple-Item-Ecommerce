<?php

namespace Modules\Management\CMSManagement\BenefitCard\Actions;

class UpdateData
{
    static $model = \Modules\Management\CMSManagement\BenefitCard\Database\Models\Model::class;

    public static function execute($request,$slug)
    {
        try {
            if (!$data = self::$model::query()->where('slug', $slug)->first()) {
                return messageResponse('Data not found...',$data, 404, 'error');
            }
            $requestData = $request->validated();

             // Process file uploads for specific fields
                            if ($request->hasFile('icon_image')) {
                    $file = $request->file('icon_image');
                    $requestData['icon_image'] = uploader($file, 'uploads/CMSManagement/BenefitCard');
                }
  
            $data->update($requestData);
            return messageResponse('Item updated successfully',$data, 201);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}