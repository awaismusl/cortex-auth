<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Tenantarea;

class PasswordResetPostProcessRequest extends PasswordResetRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //'token' => 'required|regex:/^([0-9a-f]*)$/',
            //'expiration' => 'required|date_format:U',
            // Do not validate `token` or `expiration` here since at this stage we can NOT generate viewable
            // error, and it is been processed in the controller through EmailVerificationBroker anyway
            'email' => 'required|email|min:3|max:150|exists:'.config('rinvex.fort.tables.users').',email',
            'password' => 'required|confirmed|min:'.config('rinvex.fort.password_min_chars'),
        ];
    }
}
