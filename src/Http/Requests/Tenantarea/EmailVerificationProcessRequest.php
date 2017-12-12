<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Tenantarea;

class EmailVerificationProcessRequest extends EmailVerificationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Do not validate `token` here since at this stage we can NOT generate viewable error,
            // and it is been processed in the controller through EmailVerificationBroker anyway
            //'token' => 'required|regex:/^([0-9a-f]*)$/',
            'email' => 'required|email|min:3|max:150|exists:'.config('rinvex.fort.tables.users').',email',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getRedirectUrl()
    {
        return $this->redirector->getUrlGenerator()->route('tenantarea.verification.email.request');
    }
}
