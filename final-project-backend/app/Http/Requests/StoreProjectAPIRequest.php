<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\ProductOwnerValidationRule;
use App\Rules\ProductManagerValidationRule;
class StoreProjectAPIRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
            'project_title' => 'required|string|max:255',
            'project_type' => 'required|string|max:255',
            'project_description' => 'required|string',
            'project_start' => 'required|date',
            'project_end' => 'required|date|after:project_start',
            'project_status' => 'required|in:notStarted,inProgress,complete',
            'ProductOwner_id' => ['required', 'exists:managers,id', new ProductOwnerValidationRule],
            'ProductManager_id' => ['required', 'exists:managers,id', new ProductManagerValidationRule],
            'client_id' => 'required|exists:clients,id',
        ];
    }
    ## add new function failed validation
    public  function  failedValidation(Validator $validator)
    {
//        parent::failedValidation($validator); // TODO: Change the autogenerated stub
        throw  new HttpResponseException(
            response()->json([
            'success'=>false,
            "message"=>"validation project errors",
            "data"=> $validator->errors()
            ],
            400

        ));
    }
}