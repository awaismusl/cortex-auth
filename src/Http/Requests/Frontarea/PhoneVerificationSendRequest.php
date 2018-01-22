<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Frontarea;

use Rinvex\Fort\Exceptions\GenericException;

class PhoneVerificationSendRequest extends PhoneVerificationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws \Rinvex\Fort\Exceptions\GenericException
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $attemptUser = auth()->attemptUser();

        if (empty(config('rinvex.fort.twofactor.providers'))) {
            // At least one TwoFactor provider required for phone verification
            throw new GenericException(trans('cortex/fort::messages.verification.twofactor.globaly_disabled'), route('frontarea.account.settings'));
        }

        if ($user && ! $user->country_code) {
            // Country field required for phone verification
            throw new GenericException(trans('cortex/fort::messages.account.country_required'), route('frontarea.account.settings'));
        }

        if ($user && ! $user->phone) {
            // Phone field required before verification
            throw new GenericException(trans('cortex/fort::messages.account.phone_required'), route('frontarea.account.settings'));
        }

        if ($attemptUser && ! $attemptUser->country_code) {
            // Country field required for TwoFactor authentication
            throw new GenericException(trans('cortex/fort::messages.verification.twofactor.phone.country_required'), route('frontarea.home'));
        }

        if ($attemptUser && ! $attemptUser->phone) {
            // Phone field required for TwoFactor authentication
            throw new GenericException(trans('cortex/fort::messages.verification.twofactor.phone.phone_required'), route('frontarea.home'));
        }

        if (! in_array('phone', config('rinvex.fort.twofactor.providers'))) {
            // Country required for phone verification
            throw new GenericException(trans('cortex/fort::messages.verification.twofactor.phone.disabled'), route('frontarea.account.settings'));
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
