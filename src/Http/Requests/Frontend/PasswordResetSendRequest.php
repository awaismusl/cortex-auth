<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetSendRequest extends FormRequest
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
            'email' => 'required|email|max:255',
        ];
    }
}
