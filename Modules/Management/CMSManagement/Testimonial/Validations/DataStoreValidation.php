<?php

namespace Modules\Management\CMSManagement\Testimonial\Validations;

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
            'customer_name' => 'required | sometimes',
            'customer_image' => 'required | sometimes',
            'customer_location' => 'required | sometimes',
            'testimonial_text' => 'required | sometimes',
            'rating' => 'required | sometimes',
            'from_order_id' => 'required | sometimes',
            'is_featured' => 'required | sometimes',
            'is_published' => 'required | sometimes',
            'display_order' => 'required | sometimes',
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
        ];
    }
}