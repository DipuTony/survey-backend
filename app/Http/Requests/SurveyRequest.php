<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'villageId' => 'required|integer',
            'nameOfHead' => 'required',
            'age' => 'required|integer',
            'maritalStatus' => 'required|bool',
            'noOfDependencies' => 'required|integer',
            'relations' => 'required|array',
            'relations.*.name' => 'required',
            'relations.*.age' => 'required|integer',
            'relations.*.relation' => 'required',
            'questions' => 'required|array',
            'questions.*.questionId' => 'required|integer',
            'questions.*.answer' => 'required',
        ];
    }

    /**
     * | Show Messages
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 422),);
    }
}
