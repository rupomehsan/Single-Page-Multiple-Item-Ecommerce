<?php

namespace Modules\Management\OrderManagement\Order\Validations;

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
            'order_number' => 'required | sometimes',
            'customer_name' => 'required | sometimes',
            'customer_phone' => 'required | sometimes',
            'customer_email' => 'required | sometimes',
            'delivery_address' => 'required | sometimes',
            'delivery_area_id' => 'required | sometimes',
            'delivery_district' => 'required | sometimes',
            'delivery_thana' => 'required | sometimes',
            'delivery_village' => 'required | sometimes',
            'delivery_date' => 'required | sometimes',
            'delivered_date' => 'required | sometimes',
            'subtotal' => 'required | sometimes',
            'shipping_charge' => 'required | sometimes',
            'discount_amount' => 'required | sometimes',
            'promo_code_used' => 'required | sometimes',
            'total_amount' => 'required | sometimes',
            'payment_method' => 'required | sometimes',
            'payment_status' => 'required | sometimes',
            'order_status' => 'required | sometimes',
            'special_notes' => 'required | sometimes',
            'admin_notes' => 'required | sometimes',
            'ip_address' => 'required | sometimes',
            'source' => 'required | sometimes',
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
        ];
    }
}