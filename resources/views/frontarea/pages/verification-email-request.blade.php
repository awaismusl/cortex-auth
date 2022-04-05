{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

{{-- Scripts --}}
@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Frontarea\EmailVerificationSendRequest::class)->selector('#frontarea-verification-email-request-form')->ignore('.skip-validation') !!}
@endpush

@section('body-attributes')class="auth-page"@endsection

{{-- Main Content --}}
@section('content')

    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">{{ trans('cortex/auth::common.account_verification_email') }}</h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                {{ Form::open(['url' => route('frontarea.cortex.auth.account.verification.email.send'), 'id' => 'frontarea-verification-email-request-form', 'class' => 'space-y-6']) }}

                <div>
                    <div class="mt-1">
                        {{ Form::email('email', old('email', request()->user()->email ?? ''), ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('email')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.email'), 'required' => 'required']) }}
                        @if ($errors->has('email'))
                            <p class="text-red-500 text-xs italic">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">{{ trans('cortex/auth::common.verification_email_request') }}</button>
                </div>
                {{ Form::close() }}

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500"> Or </span>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <div>
                            {{ Html::link(route('frontarea.cortex.auth.account.login'), trans('cortex/auth::common.account_login')) }}
                        </div>
                        <div>
                            {{ Html::link(route('frontarea.cortex.auth.account.register.member'), trans('cortex/auth::common.account_register')) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
