{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Adminarea\ManagerFormRequest::class)->selector("#adminarea-cortex-auth-managers-create-form, #adminarea-cortex-auth-managers-{$manager->getRouteKey()}-update-form")->ignore('.skip-validation') !!}

    <script>
        window.countries = @json($countries);
        window.selectedCountry = '{{ old('country_code', $manager->country_code) }}';
    </script>
@endpush

{{-- Main Content --}}
@section('content')

    @includeWhen($manager->exists, 'cortex/foundation::adminarea.partials.modal', ['id' => 'delete-confirmation'])

    <div>
        <section class="pt-3">
            <h1>{{ Breadcrumbs::render() }}</h1>
        </section>
        {{-- Main content --}}
        <div class="bg-white p-3 my-3 rounded-sm shadow-md border border-gray-100">
            <section class="container mx-auto mb-6">
                <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
                    <aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
                        <nav class="space-y-1">
                            {!! Menu::render('adminarea.cortex.auth.managers.tabs', 'nav-tab') !!}
                        </nav>
                    </aside>
                    <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
                        @includeWhen($manager->exists, 'cortex/foundation::adminarea.partials.actions', ['name' => 'manager', 'model' => $manager, 'resource' => trans('cortex/auth::common.manager'), 'routePrefix' => 'adminarea.cortex.auth.managers'])

                        @if ($manager->exists)
                            {{ Form::model($manager, ['url' => route('adminarea.cortex.auth.managers.update', ['manager' => $manager]), 'id' => "adminarea-cortex-auth-managers-{$manager->getRouteKey()}-update-form", 'method' => 'put', 'files' => true]) }}
                        @else
                            {{ Form::model($manager, ['url' => route('adminarea.cortex.auth.managers.store'), 'id' => 'adminarea-cortex-auth-managers-create-form', 'files' => true]) }}
                        @endif
                        <div class="shadow sm:rounded-md sm:overflow-hidden">
                            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                                <div class="grid grid-cols-3 gap-6">
                                    {{-- Given Name --}}
                                    <div class="{{ $errors->has('given_name') ? ' has-error' : '' }}">
                                        {{ Form::label('given_name', trans('cortex/auth::common.given_name'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        {{ Form::text('given_name', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('given_name')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.given_name'), 'data-slugify' => '[name="username"]', 'required' => 'required', 'autofocus' => 'autofocus']) }}

                                        @if ($errors->has('given_name'))
                                            <span class="help-block">{{ $errors->first('given_name') }}</span>
                                        @endif
                                    </div>

                                    {{-- Family Name --}}
                                    <div class="{{ $errors->has('family_name') ? ' has-error' : '' }}">
                                        {{ Form::label('family_name', trans('cortex/auth::common.family_name'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        {{ Form::text('family_name', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('family_name')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.family_name')]) }}

                                        @if ($errors->has('family_name'))
                                            <span class="help-block">{{ $errors->first('family_name') }}</span>
                                        @endif
                                    </div>

                                    {{-- Username --}}
                                    <div class="{{ $errors->has('username') ? ' has-error' : '' }}">
                                        {{ Form::label('username', trans('cortex/auth::common.username'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        {{ Form::text('username', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('username')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.username'), 'required' => 'required']) }}

                                        @if ($errors->has('username'))
                                            <span class="help-block">{{ $errors->first('username') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-6">
                                    {{-- Email --}}
                                    <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <div class="flex justify-between">
                                            {{ Form::label('email', trans('cortex/auth::common.email'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                            {{ Form::label('email_verified', trans('cortex/auth::common.verified'), ['class' => 'block text-sm font-medium text-gray-700 pull-right']) }}
                                        </div>
                                        <div class="">
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <div class="relative flex items-stretch flex-grow focus-within:z-10">
                                                    {{ Form::email('email', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('email_verified')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.email'), 'required' => 'required']) }}
                                                </div>
                                                <div class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                    {{ Form::checkbox('email_verified') }}
                                                </div>
                                            </div>
                                        </div>

                                        @if ($errors->has('email'))
                                            <span class="help-block">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>

                                    {{-- Phone --}}
                                    <div class="{{ $errors->has('phone') ? ' has-error' : '' }}">
                                        {{ Form::label('phone', trans('cortex/auth::common.phone'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        {{ Form::tel('phone_input', $manager->phone, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('phone')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.phone')]) }}

                                        <span class="help-block hide">{{ trans('cortex/foundation::messages.invalid_phone') }}</span>
                                        @if ($errors->has('phone'))
                                            <span class="help-block">{{ $errors->first('phone') }}</span>
                                        @endif
                                    </div>

                                    {{-- Is Active --}}
                                    <div class="{{ $errors->has('is_active') ? ' has-error' : '' }}">
                                        {{ Form::label('is_active', trans('cortex/auth::common.is_active'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        {{ Form::select('is_active', [1 => trans('cortex/auth::common.yes'), 0 => trans('cortex/auth::common.no')], null, ['class' => 'form-control select2', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%', 'required' => 'required']) }}

                                        @if ($errors->has('is_active'))
                                            <span class="help-block">{{ $errors->first('is_active') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-6">
                                    {{-- Country Code --}}
                                    <div class="{{ $errors->has('country_code') ? ' has-error' : '' }}">
                                        {{ Form::label('country_code', trans('cortex/auth::common.country'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        {{ Form::hidden('country_code', '', ['class' => 'skip-validation', 'id' => 'country_code_hidden']) }}
                                        {{ Form::select('country_code', [], null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_country'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}

                                        @if ($errors->has('country_code'))
                                            <span class="help-block">{{ $errors->first('country_code') }}</span>
                                        @endif
                                    </div>

                                    {{-- Language Code --}}
                                    <div class="{{ $errors->has('language_code') ? ' has-error' : '' }}">
                                        {{ Form::label('language_code', trans('cortex/auth::common.language'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        {{ Form::hidden('language_code', '', ['class' => 'skip-validation', 'id' => 'language_code_hidden']) }}
                                        {{ Form::select('language_code', $languages, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_language'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}

                                        @if ($errors->has('language_code'))
                                            <span class="help-block">{{ $errors->first('language_code') }}</span>
                                        @endif
                                    </div>

                                    {{-- Title --}}
                                    <div class="{{ $errors->has('title') ? ' has-error' : '' }}">
                                        {{ Form::label('title', trans('cortex/auth::common.title'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        {{ Form::text('title', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('title')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.title')]) }}

                                        @if ($errors->has('title'))
                                            <span class="help-block">{{ $errors->first('title') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-6">
                                    {{-- Organization --}}
                                    <div class="{{ $errors->has('organization') ? ' has-error' : '' }}">
                                        {{ Form::label('organization', trans('cortex/auth::common.organization'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        {{ Form::text('organization', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('organization')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.organization')]) }}

                                        @if ($errors->has('organization'))
                                            <span class="help-block">{{ $errors->first('organization') }}</span>
                                        @endif
                                    </div>

                                    {{-- Birthday --}}
                                    <div class=" has-feedback{{ $errors->has('birthday') ? ' has-error' : '' }}">
                                        {{ Form::label('birthday', trans('cortex/auth::common.birthday'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        <div class="">
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <div class="relative flex items-stretch flex-grow focus-within:z-10">
                                                    {{ Form::date('birthday', null, ['class' => 'datepicker appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('organization')?' border-red-500':' border-gray-300'), 'data-locale' => '{"format": "YYYY-MM-DD"}', 'data-single-date-picker' => 'true', 'data-show-dropdowns' => 'true', 'data-auto-apply' => 'true', 'data-min-date' => '1900-01-01']) }}
                                                </div>
                                                <div class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                    <span class="fa fa-calendar form-control-feedback"></span>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($errors->has('birthday'))
                                            <span class="help-block">{{ $errors->first('birthday') }}</span>
                                        @endif
                                    </div>

                                    {{-- Gender --}}
                                    <div class="{{ $errors->has('gender') ? ' has-error' : '' }}">
                                        {{ Form::label('gender', trans('cortex/auth::common.gender'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        {{ Form::hidden('gender', '', ['class' => 'skip-validation', 'id' => 'gender_hidden']) }}
                                        {{ Form::select('gender', $genders, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_gender'), 'data-allow-clear' => 'true', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%']) }}

                                        @if ($errors->has('gender'))
                                            <span class="help-block">{{ $errors->first('gender') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-6">
                                    {{-- Tags --}}
                                    <div class="{{ $errors->has('tags') ? ' has-error' : '' }}">
                                        {{ Form::label('tags[]', trans('cortex/auth::common.tags'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        {{ Form::hidden('tags', '', ['class' => 'skip-validation']) }}
                                        {{ Form::select('tags[]', $tags, null, ['class' => 'form-control select2', 'multiple' => 'multiple', 'data-width' => '100%', 'data-tags' => 'true']) }}

                                        @if ($errors->has('tags'))
                                            <span class="help-block">{{ $errors->first('tags') }}</span>
                                        @endif
                                    </div>

                                    @can('assign', \Cortex\Auth\Models\Role::class)
                                        {{-- Roles --}}
                                        <div class="{{ $errors->has('roles') ? ' has-error' : '' }}">
                                            {{ Form::label('roles[]', trans('cortex/auth::common.roles'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                            {{ Form::hidden('roles', '', ['class' => 'skip-validation']) }}
                                            {{ Form::select('roles[]', $roles, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_roles'), 'multiple' => 'multiple', 'data-close-on-select' => 'false', 'data-width' => '100%']) }}

                                            @if ($errors->has('roles'))
                                                <span class="help-block">{{ $errors->first('roles') }}</span>
                                            @endif
                                        </div>
                                    @endcan

                                    @can('grant', \Cortex\Auth\Models\Ability::class)
                                        {{-- Abilities --}}
                                        <div class="{{ $errors->has('abilities') ? ' has-error' : '' }}">
                                            {{ Form::label('abilities[]', trans('cortex/auth::common.abilities'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                            {{ Form::hidden('abilities', '', ['class' => 'skip-validation']) }}
                                            {{ Form::select('abilities[]', $abilities, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_abilities'), 'multiple' => 'multiple', 'data-close-on-select' => 'false', 'data-width' => '100%']) }}

                                            @if ($errors->has('abilities'))
                                                <span class="help-block">{{ $errors->first('abilities') }}</span>
                                            @endif
                                        </div>
                                    @endcan
                                </div>
                                <div class="grid grid-cols-3 gap-6">
                                    {{-- Twitter --}}
                                    <div class="{{ $errors->has('social.twitter') ? ' has-error' : '' }}">
                                        {{ Form::label('social[twitter]', trans('cortex/auth::common.twitter'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        {{ Form::text('social[twitter]', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('social.twitter')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.twitter')]) }}

                                        @if ($errors->has('social.twitter'))
                                            <span class="help-block">{{ $errors->first('social.twitter') }}</span>
                                        @endif
                                    </div>

                                    {{-- Facebook --}}
                                    <div class="{{ $errors->has('social.facebook') ? ' has-error' : '' }}">
                                        {{ Form::label('social[facebook]', trans('cortex/auth::common.facebook'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        {{ Form::text('social[facebook]', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('social.facebook')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.facebook')]) }}

                                        @if ($errors->has('social.facebook'))
                                            <span class="help-block">{{ $errors->first('social.facebook') }}</span>
                                        @endif
                                    </div>

                                    {{-- Linkedin --}}
                                    <div class="{{ $errors->has('social.linkedin') ? ' has-error' : '' }}">
                                        {{ Form::label('social[linkedin]', trans('cortex/auth::common.linkedin'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        {{ Form::text('social[linkedin]', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('social.linkedin')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.linkedin')]) }}

                                        @if ($errors->has('social.linkedin'))
                                            <span class="help-block">{{ $errors->first('social.linkedin') }}</span>
                                        @endif
                                    </div>

                                    {{-- Timezone --}}
                                    <div class="{{ $errors->has('timezone') ? ' has-error' : '' }}">
                                        {{ Form::label('timezone', trans('cortex/tenants::common.timezone'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        {{ Form::hidden('timezone', '', ['class' => 'skip-validation', 'id' => 'timezone_hidden']) }}
                                        {{ Form::select('timezone', timezones(), null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_timezone'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}

                                        @if ($errors->has('timezone'))
                                            <span class="help-block">{{ $errors->first('timezone') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-6">
                                    {{-- Tenants --}}
                                    <div class="{{ $errors->has('tenants') ? ' has-error' : '' }}">
                                        {{ Form::label('tenants[]', trans('cortex/auth::common.tenants'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        {{ Form::hidden('tenants', '', ['class' => 'skip-validation']) }}
                                        {{ Form::select('tenants[]', $tenants, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_tenants'), 'multiple' => 'multiple', 'data-close-on-select' => 'false', 'data-width' => '100%']) }}

                                        @if ($errors->has('tenants'))
                                            <span class="help-block">{{ $errors->first('tenants') }}</span>
                                        @endif
                                    </div>
                                    {{-- Password --}}
                                    <div class=" has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                                        {{ Form::label('password', trans('cortex/auth::common.password'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        <div class="">
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <div class="relative flex items-stretch flex-grow focus-within:z-10">
                                                    @if ($manager->exists)
                                                        {{ Form::password('password', ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('password')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.password')]) }}
                                                    @else
                                                        {{ Form::password('password', ['class' => 'autogenerate appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('password')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.password'), 'required' => 'required']) }}
                                                    @endif
                                                </div>
                                                <div class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                    <span class="fa fa-key form-control-feedback"></span>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($errors->has('password'))
                                            <span class="help-block">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>

                                    {{-- Password Confirmation --}}
                                    <div class=" has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                        {{ Form::label('password_confirmation', trans('cortex/auth::common.password_confirmation'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        <div class="">
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <div class="relative flex items-stretch flex-grow focus-within:z-10">
                                                    @if ($manager->exists)
                                                        {{ Form::password('password_confirmation', ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('password_confirmation')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.password_confirmation')]) }}
                                                    @else
                                                        {{ Form::password('password_confirmation', ['class' => 'autogenerate appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('password_confirmation')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.password_confirmation'), 'required' => 'required']) }}
                                                    @endif
                                                </div>
                                                <div class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                    <span class="fa fa-key form-control-feedback"></span>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($errors->has('password_confirmation'))
                                            <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-6">
                                    {{-- Profile Picture --}}
                                    <div class=" has-feedback{{ $errors->has('profile_picture') ? ' has-error' : '' }}">
                                        {{ Form::label('profile_picture', trans('cortex/auth::common.profile_picture'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                        <div class="">
                                            <div class="input-group mt-1 flex rounded-md shadow-sm">
                                                <div class="relative flex items-stretch flex-grow focus-within:z-10">
                                                    {{ Form::text('profile_picture', null, ['class' => 'file-name appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('email_verified')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.profile_picture'), 'readonly' => 'readonly']) }}
                                                </div>
                                                <div class="input-group-btn -ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                    <div class="btn btn-default btn-file">
                                                        {{ trans('cortex/auth::common.browse') }}
                                                        {{-- Skip Javascrip validation for file input fields to avoid size validation conflict with jquery.validator --}}
                                                        {{ Form::file('profile_picture', ['class' => 'sr-only skip-validation', 'id' => 'profile_picture_browse']) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($manager->exists && $manager->getMedia('profile_picture')->count())
                                            <i class="fa fa-paperclip"></i>
                                            <a href="{{ $manager->getFirstMediaUrl('profile_picture') }}" target="_blank">{{ $manager->getFirstMedia('profile_picture')->file_name }}</a> ({{ $manager->getFirstMedia('profile_picture')->human_readable_size }})
                                            <a href="#" data-toggle="modal" data-target="#delete-confirmation"
                                               data-modal-action="{{ route('adminarea.cortex.auth.managers.media.destroy', ['manager' => $manager, 'media' => $manager->getFirstMedia('profile_picture')]) }}"
                                               data-modal-title="{{ trans('cortex/foundation::messages.delete_confirmation_title') }}"
                                               data-modal-button="<a href='#' class='btn btn-danger' data-form='delete' data-token='{{ csrf_token() }}'><i class='fa fa-trash-o'></i> {{ trans('cortex/foundation::common.delete') }}</a>"
                                               data-modal-body="{{ trans('cortex/foundation::messages.delete_confirmation_body', ['resource' => trans('cortex/foundation::common.media'), 'identifier' => $manager->getFirstMedia('profile_picture')->getRouteKey()]) }}"
                                               title="{{ trans('cortex/foundation::common.delete') }}"><i class="fa fa-trash text-danger"></i></a>
                                        @endif

                                        @if ($errors->has('profile_picture'))
                                            <span class="help-block">{{ $errors->first('profile_picture') }}</span>
                                        @endif
                                    </div>

                                    {{-- Cover Photo --}}
                                    <div class=" has-feedback{{ $errors->has('cover_photo') ? ' has-error' : '' }}">
                                        {{ Form::label('cover_photo', trans('cortex/auth::common.cover_photo'), ['class' => 'block text-sm font-medium text-gray-700']) }}

                                        <div class="">
                                            <div class="input-group mt-1 flex rounded-md shadow-sm">
                                                <div class="relative flex items-stretch flex-grow focus-within:z-10">
                                                    {{ Form::text('cover_photo', null, ['class' => 'file-name appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('cover_photo')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.cover_photo'), 'readonly' => 'readonly']) }}
                                                </div>
                                                <div class="input-group-btn -ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                    <div class="btn btn-default btn-file">
                                                        {{ trans('cortex/auth::common.browse') }}
                                                        {{-- Skip Javascrip validation for file input fields to avoid size validation conflict with jquery.validator --}}
                                                        {{ Form::file('cover_photo', ['class' => 'sr-only skip-validation', 'id' => 'cover_photo_browse']) }}

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($manager->exists && $manager->getMedia('cover_photo')->count())
                                            <i class="fa fa-paperclip"></i>
                                            <a href="{{ $manager->getFirstMediaUrl('cover_photo') }}" target="_blank">{{ $manager->getFirstMedia('cover_photo')->file_name }}</a> ({{ $manager->getFirstMedia('cover_photo')->human_readable_size }})
                                            <a href="#" data-toggle="modal" data-target="#delete-confirmation"
                                               data-modal-action="{{ route('adminarea.cortex.auth.managers.media.destroy', ['manager' => $manager, 'media' => $manager->getFirstMedia('cover_photo')]) }}"
                                               data-modal-title="{{ trans('cortex/foundation::messages.delete_confirmation_title') }}"
                                               data-modal-button="<a href='#' class='btn btn-danger' data-form='delete' data-token='{{ csrf_token() }}'><i class='fa fa-trash-o'></i> {{ trans('cortex/foundation::common.delete') }}</a>"
                                               data-modal-body="{{ trans('cortex/foundation::messages.delete_confirmation_body', ['resource' => trans('cortex/foundation::common.media'), 'identifier' => $manager->getFirstMedia('cover_photo')->getRouteKey()]) }}"
                                               title="{{ trans('cortex/foundation::common.delete') }}"><i class="fa fa-trash text-danger"></i></a>
                                        @endif

                                        @if ($errors->has('cover_photo'))
                                            <span class="help-block">{{ $errors->first('cover_photo') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                <button type="submit"
                                        class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ trans('cortex/auth::common.submit') }}
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
