<?php

declare(strict_types=1);

use Rinvex\Fort\Contracts\RoleContract;
use Rinvex\Fort\Contracts\UserContract;
use Rinvex\Fort\Contracts\AbilityContract;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;

Breadcrumbs::register('adminarea.roles.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/foundation::common.adminarea'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/fort::common.roles'), route('adminarea.roles.index'));
});

Breadcrumbs::register('adminarea.roles.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.roles.index');
    $breadcrumbs->push(trans('cortex/fort::common.create_role'), route('adminarea.roles.create'));
});

Breadcrumbs::register('adminarea.roles.edit', function (BreadcrumbsGenerator $breadcrumbs, RoleContract $role) {
    $breadcrumbs->parent('adminarea.roles.index');
    $breadcrumbs->push($role->name, route('adminarea.roles.edit', ['role' => $role]));
});

Breadcrumbs::register('adminarea.roles.logs', function (BreadcrumbsGenerator $breadcrumbs, RoleContract $role) {
    $breadcrumbs->parent('adminarea.roles.index');
    $breadcrumbs->push($role->name, route('adminarea.roles.edit', ['role' => $role]));
    $breadcrumbs->push(trans('cortex/fort::common.logs'), route('adminarea.roles.logs', ['role' => $role]));
});

Breadcrumbs::register('adminarea.users.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/foundation::common.adminarea'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/fort::common.users'), route('adminarea.users.index'));
});

Breadcrumbs::register('adminarea.users.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.users.index');
    $breadcrumbs->push(trans('cortex/fort::common.create_user'), route('adminarea.users.create'));
});

Breadcrumbs::register('adminarea.users.edit', function (BreadcrumbsGenerator $breadcrumbs, UserContract $user) {
    $breadcrumbs->parent('adminarea.users.index');
    $breadcrumbs->push($user->username, route('adminarea.users.edit', ['user' => $user]));
});

Breadcrumbs::register('adminarea.users.logs', function (BreadcrumbsGenerator $breadcrumbs, UserContract $user) {
    $breadcrumbs->parent('adminarea.users.index');
    $breadcrumbs->push($user->username, route('adminarea.users.edit', ['user' => $user]));
    $breadcrumbs->push(trans('cortex/fort::common.logs'), route('adminarea.users.logs', ['user' => $user]));
});

Breadcrumbs::register('adminarea.users.activities', function (BreadcrumbsGenerator $breadcrumbs, UserContract $user) {
    $breadcrumbs->parent('adminarea.users.index');
    $breadcrumbs->push($user->username, route('adminarea.users.edit', ['user' => $user]));
    $breadcrumbs->push(trans('cortex/fort::common.activities'), route('adminarea.users.activities', ['user' => $user]));
});

Breadcrumbs::register('adminarea.abilities.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/foundation::common.adminarea'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/fort::common.abilities'), route('adminarea.abilities.index'));
});

Breadcrumbs::register('adminarea.abilities.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.abilities.index');
    $breadcrumbs->push(trans('cortex/fort::common.create_ability'), route('adminarea.abilities.create'));
});

Breadcrumbs::register('adminarea.abilities.edit', function (BreadcrumbsGenerator $breadcrumbs, AbilityContract $ability) {
    $breadcrumbs->parent('adminarea.abilities.index');
    $breadcrumbs->push($ability->name, route('adminarea.abilities.edit', ['ability' => $ability]));
});

Breadcrumbs::register('adminarea.abilities.logs', function (BreadcrumbsGenerator $breadcrumbs, AbilityContract $ability) {
    $breadcrumbs->parent('adminarea.abilities.index');
    $breadcrumbs->push($ability->name, route('adminarea.abilities.edit', ['ability' => $ability]));
    $breadcrumbs->push(trans('cortex/fort::common.logs'), route('adminarea.abilities.logs', ['ability' => $ability]));
});
