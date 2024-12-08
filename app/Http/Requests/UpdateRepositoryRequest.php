<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * This class handles the validation and authorization of the update repository request.
 * It is used to ensure that the input data for updating a repository is valid and the user is authorized to perform this action.
 */
class UpdateRepositoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * This method checks whether the authenticated user has permission to make the update request.
     *
     * @return bool True if the user is authorized, false otherwise.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * This method returns an array of validation rules that are applied to the input data for updating a repository.
     *
     * @return array The validation rules.
     */
    public function rules(): array
    {
        return [
            'tags' => 'required|array',
            'tags.*' => 'string',
        ];
    }
}
