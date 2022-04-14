<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ExecGenericApiWithSqlRequest extends FormRequest
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
            'user_name' => ['required', 'string', 'exists:pa_users,user_name'],
            'password' => ['required', 'string'],
            'apiName' => ['required', 'string', 'exists:t_adm_generic_api_conf,api_name'],
            'wildcards' => ['array'],
            'sqlwildcards' => ['array'],
        ];
    }
}
