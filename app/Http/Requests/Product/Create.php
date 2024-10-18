<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;


class Create extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'name' => 'required|string|between:3,30',
            'description' => 'required|string',
            'price' => 'required|integer|min:0|max:1000000000',
            'quantity' => 'required|integer|min:0|max:100000',
            'weight' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }


    public function messages()
    {
        return [
            'category_id.required' => 'The category field is required.',
            'category_id.integer' => 'The category must be a valid integer.',
            'category_id.exists' => 'The selected category is invalid.',
            'name.required' => 'The name field is required.',
            'name.string' => 'Name must be a string.',
            'name.between' => 'Name must be between 3 and 30 characters.',
            'description.required' => 'The description field is required.',
            'description.string' => 'Description must be a string.',
            'price.required' => 'The price is required.',
            'price.integer' => 'The price must be a whole number in VND.',
            'price.min' => 'The price must be at least 0.',
            'price.max' => 'The price may not exceed 1,000,000,000 VND.',
            'quantity.required' => 'The quantity is required.',
            'quantity.integer' => 'The quantity must be an integer.',
            'quantity.min' => 'The quantity must be at least 0.',
            'quantity.max' => 'The quantity may not exceed 100,000.',
            'weight.string' => 'The weight must be a string.',
            'weight.max' => 'The weight may not be greater than 255 characters.',
            'weight.required' => 'The weight is required.',
            'image.image' => 'The image must be an image file.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg.',
            'image.max' => 'The image may not be greater than 2MB.',
        ];
    }

    /**
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(
            [
                'status'     => 'fail',
                'message_key' => 'VALIDATE_FAILED',
                'errors' => $errors,
                'code' => 400,
                'data' => null
            ],
            JsonResponse::HTTP_BAD_REQUEST
        ));
    }
}
