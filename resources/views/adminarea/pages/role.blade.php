{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Adminarea\RoleFormProcessRequest::class)->selector("#adminarea-cortex-auth-roles-create-form, #adminarea-cortex-auth-roles-{$role->getRouteKey()}-update-form")->ignore('.skip-validation') !!}
@endpush

{{-- Main Content --}}
@section('content')

    @includeWhen($role->exists, 'cortex/foundation::adminarea.partials.modal', ['id' => 'delete-confirmation'])

    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ Breadcrumbs::render() }}</h1>
        </section>

        {{-- Main content --}}
        <section class="content">

            <div class="nav-tabs-custom">
                @includeWhen($role->exists, 'cortex/foundation::adminarea.partials.actions', ['name' => 'role', 'model' => $role, 'resource' => trans('cortex/auth::common.role'), 'routePrefix' => 'adminarea.cortex.auth.roles'])
                {!! Menu::render('adminarea.cortex.auth.roles.tabs', 'nav-tab') !!}

                <div class="tab-content">
                    <div class="tab-pane active" id="details-tab">

                        @if ($role->exists)
                            {{ Form::model($role, ['url' => route('adminarea.cortex.auth.roles.update', ['role' => $role]), 'method' => 'put', 'id' => "adminarea-cortex-auth-roles-{$role->getRouteKey()}-update-form"]) }}
                        @else
                            {{ Form::model($role, ['url' => route('adminarea.cortex.auth.roles.store'), 'id' => 'adminarea-cortex-auth-roles-create-form']) }}
                        @endif

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Title --}}
                                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                        {{ Form::label('title', trans('cortex/auth::common.title'), ['class' => 'control-label']) }}
                                        {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.title'), 'data-slugify' => '[name="name"]', 'required' => 'required', 'autofocus' => 'autofocus']) }}

                                        @if ($errors->has('title'))
                                            <span class="help-block">{{ $errors->first('title') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Name --}}
                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        {{ Form::label('name', trans('cortex/auth::common.name'), ['class' => 'control-label']) }}
                                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.name'), 'required' => 'required']) }}

                                        @if ($errors->has('name'))
                                            <span class="help-block">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Scope --}}
                                    <div class="form-group{{ $errors->has('scope') ? ' has-error' : '' }}">
                                        {{ Form::label('scope', trans('cortex/auth::common.scope'), ['class' => 'control-label']) }}
                                        {{ Form::text('scope', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.scope')]) }}

                                        @if ($errors->has('scope'))
                                            <span class="help-block">{{ $errors->first('scope') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                @can('grant', \Cortex\Auth\Models\Ability::class)
                                    <div class="col-md-12">

                                        {{-- Abilities --}}
                                        <div class="form-group{{ $errors->has('abilities') ? ' has-error' : '' }}">
                                            {{ Form::label('abilities[]', trans('cortex/auth::common.abilities'), ['class' => 'control-label']) }}
                                            {{ Form::hidden('abilities', '', ['class' => 'skip-validation']) }}
                                            {{ Form::select('abilities[]', $abilities, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_abilities'), 'multiple' => 'multiple', 'data-close-on-select' => 'false', 'data-width' => '100%']) }}

                                            @if ($errors->has('abilities'))
                                                <span class="help-block">{{ $errors->first('abilities') }}</span>
                                            @endif
                                        </div>

                                    </div>
                                @endcan

                            </div>

                            <div class="row">
                                <div class="col-md-12">

                                    <div class="pull-right">
                                        {{ Form::button(trans('cortex/auth::common.submit'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                                    </div>

                                    @include('cortex/foundation::adminarea.partials.timestamps', ['model' => $role])

                                </div>
                            </div>

                        {{ Form::close() }}

                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection
