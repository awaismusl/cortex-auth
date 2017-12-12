<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Tenantarea;

use Illuminate\Foundation\Http\FormRequest;

class AuthenticationRequest extends FormRequest
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
            'loginfield' => 'required|min:3|max:150',
            'password' => 'required|min:'.config('rinvex.fort.password_min_chars'),
        ];
    }
}
