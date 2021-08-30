<?php

namespace App\Http\Requests\Api\Participant;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;

use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Http\Response;

use\Illuminate\Http\Request;

class StoreRequest extends FormRequest
{
    /**
     * Set validation rules
     * @author : Shubham Dayma
     * @param  : Request inputs
     * @return void
    */
    public function rules(Request $request) {
        return [            
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name'  => 'required',
            'event_id'  => 'required',
            'slot_date'  => 'required',
            'slot_start_time'  => 'required',
            'slot_end_time'  => 'required',
        ];
    }

    /**
     * Handle a failed validation attempt.
     * @author : Shubham Dayma
     * @param  Validator  $validator
     * @return void
    */
    protected function failedValidation(Validator $validator) {

        $errors = $validator->errors();
        throw new HttpResponseException(response()->json([
            'errors' => $errors,
            'status_code' => 400,
            'status' => false
        ], 400));
    }
}