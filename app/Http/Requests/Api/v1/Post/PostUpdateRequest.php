<?php

namespace App\Http\Requests\Api\v1\Post;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostUpdateRequest extends FormRequest
{
    use ApiResponseTrait;

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
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'category' => 'nullable|string|max:255',
        ];
    }
    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors()->toArray();
        throw new HttpResponseException(
            $this->validationErrorResponse($errors, 'Validation Error', 422)
        );
    }
}
