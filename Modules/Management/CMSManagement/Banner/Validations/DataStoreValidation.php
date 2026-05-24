<?php

namespace Modules\Management\CMSManagement\Banner\Validations;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class DataStoreValidation extends FormRequest
{
    /**
     * Determine if the  is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * validateError to make this request.
     */
    public function validateError($data)
    {
        $errorPayload =  $data->getMessages();
        return response(['status' => 'validation_error', 'errors' => $errorPayload], 422);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validateError($validator->errors()));
        if ($this->wantsJson() || $this->ajax()) {
            throw new HttpResponseException($this->validateError($validator->errors()));
        }
        parent::failedValidation($validator);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required | sometimes',
            'description' => 'required | sometimes',
            'banner_image' => 'required | sometimes',
            'banner_image_mobile' => 'required | sometimes',
            'button_text' => 'required | sometimes',
            'button_link' => 'required | sometimes',
            'alt_text' => 'required | sometimes',
            'start_date' => 'required | sometimes',
            'end_date' => 'required | sometimes',
            'is_active' => 'required | sometimes',
            'display_order' => 'required | sometimes',
            'background_color' => 'required | sometimes',
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
        ];
    }
}