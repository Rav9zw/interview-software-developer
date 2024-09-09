<?php

namespace App\Http\Requests;

use App\Models\Vehicle;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;


class CreateTicketRequest extends FormRequest
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
        $validTypes = Vehicle::pluck('type')->toArray();

        return [
            'vehicle_type' => ['required', Rule::in($validTypes)],
            'spot_number' => 'required|string',
            'email' => 'required|email',
        ];


    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation failed',
                'errors' => $errors->messages()
            ], 422)
        );
    }


}
