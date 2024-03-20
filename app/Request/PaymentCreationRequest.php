<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class PaymentCreationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
			'payer' => 'required',
	        'payer.email' => 'required|email',
			'payer.identification' => 'required',
			'payer.identification.type' => 'required|max:4',
			'payer.identification.number' => 'required|max:14',
	        'transaction_amount' => 'required|numeric:2|gt:0',
	        'installments' => 'required|integer|gte:1',
	        'token' => 'required',
	        'payment_method_id' => 'required|string',
        ];
    }
}
