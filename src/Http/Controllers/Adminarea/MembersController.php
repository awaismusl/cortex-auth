<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Cortex\Auth\Models\Member;
use Cortex\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Foundation\Importers\InsertImporter;
use Cortex\Foundation\DataTables\ActivitiesDataTable;
use Cortex\Auth\DataTables\Adminarea\MembersDataTable;
use Cortex\Foundation\Http\Requests\ImportFormRequest;
use Cortex\Auth\Http\Requests\Adminarea\MemberFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;
use Cortex\Auth\Http\Requests\Adminarea\MemberAttributesFormRequest;

class MembersController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'cortex.auth.models.member';

    /**
     * List all members.
     *
     * @param \Cortex\Auth\DataTables\Adminarea\MembersDataTable $membersDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(MembersDataTable $membersDataTable)
    {
        $countries = collect(countries())->map(function ($country, $code) {
            return [
                'id' => $code,
                'text' => $country['name'],
                'emoji' => $country['emoji'],
            ];
        })->values();

        $roles = app('cortex.auth.role')->pluck('title', 'id');
        $languages = collect(languages())->pluck('name', 'iso_639_1');
        $tags = app('rinvex.tags.tag')->all()->groupBy('group')->map->pluck('name', 'id')->sortKeys();
        $genders = ['male' => trans('cortex/auth::common.male'), 'female' => trans('cortex/auth::common.female')];

        return $membersDataTable->with([
            'id' => 'adminarea-cortex-auth-members-index',
            'countries' => $countries,
            'languages' => $languages,
            'genders' => $genders,
            'roles' => $roles,
            'tags' => $tags,
            'routePrefix' => 'adminarea.cortex.auth.members',
            'pusher' => ['entity' => 'member', 'channel' => 'cortex.auth.members.index'],
        ])->render('cortex/auth::adminarea.pages.members');
    }

    /**
     * List member logs.
     *
     * @param \Cortex\Auth\Models\Member                  $member
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(Member $member, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'resource' => $member,
            'tabs' => 'adminarea.cortex.auth.members.tabs',
            'id' => "adminarea-cortex-auth-members-{$member->getRouteKey()}-logs",
        ])->render('cortex/foundation::adminarea.pages.datatable-tab');
    }

    /**
     * Get a listing of the resource activities.
     *
     * @param \Cortex\Auth\Models\Member                        $member
     * @param \Cortex\Foundation\DataTables\ActivitiesDataTable $activitiesDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function activities(Member $member, ActivitiesDataTable $activitiesDataTable)
    {
        return $activitiesDataTable->with([
            'resource' => $member,
            'tabs' => 'adminarea.cortex.auth.members.tabs',
            'id' => "adminarea-cortex-auth-members-{$member->getRouteKey()}-activities",
        ])->render('cortex/foundation::adminarea.pages.datatable-tab');
    }

    /**
     * Show the form for create/update of the given resource attributes.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \Cortex\Auth\Models\Member $member
     *
     * @return \Illuminate\View\View
     */
    public function attributes(Request $request, Member $member)
    {
        return view('cortex/auth::adminarea.pages.member-attributes', compact('member'));
    }

    /**
     * Process the account update form.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\MemberAttributesFormRequest $request
     * @param \Cortex\Auth\Models\Member                                       $member
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateAttributes(MemberAttributesFormRequest $request, Member $member)
    {
        $data = $request->validated();

        // Update profile
        $member->fill($data)->save();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/auth::messages.account.updated_attributes')],
        ]);
    }

    /**
     * Import members.
     *
     * @param \Cortex\Foundation\Http\Requests\ImportFormRequest $request
     * @param \Cortex\Foundation\Importers\InsertImporter        $importer
     * @param \Cortex\Auth\Models\Member                         $member
     *
     * @return void
     */
    public function import(ImportFormRequest $request, InsertImporter $importer, Member $member)
    {
        $importer->withModel($member)->import($request->file('file'));
    }

    /**
     * Create new member.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \Cortex\Auth\Models\Member $member
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request, Member $member)
    {
        return $this->form($request, $member);
    }

    /**
     * Edit given member.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \Cortex\Auth\Models\Member $member
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, Member $member)
    {
        return $this->form($request, $member);
    }

    /**
     * Show member create/edit form.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \Cortex\Auth\Models\Member $member
     *
     * @return \Illuminate\View\View
     */
    protected function form(Request $request, Member $member)
    {
        if (! $member->exists && $request->has('replicate') && $replicated = $member->resolveRouteBinding($request->input('replicate'))) {
            $member = $replicated->replicate();
        }

        $countries = collect(countries())->map(function ($country, $code) {
            return [
                'id' => $code,
                'text' => $country['name'],
                'emoji' => $country['emoji'],
            ];
        })->values();

        $tags = app('rinvex.tags.tag')->pluck('name', 'id');
        $languages = collect(languages())->pluck('name', 'iso_639_1');
        $genders = ['male' => trans('cortex/auth::common.male'), 'female' => trans('cortex/auth::common.female')];
        $abilities = $request->user()->getManagedAbilityIds();
        $roles = $request->user()->getManagedRoles();

        return view('cortex/auth::adminarea.pages.member', compact('member', 'abilities', 'roles', 'countries', 'languages', 'genders', 'tags'));
    }

    /**
     * Store new member.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\MemberFormRequest $request
     * @param \Cortex\Auth\Models\Member                             $member
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(MemberFormRequest $request, Member $member)
    {
        return $this->process($request, $member);
    }

    /**
     * Update given member.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\MemberFormRequest $request
     * @param \Cortex\Auth\Models\Member                             $member
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(MemberFormRequest $request, Member $member)
    {
        return $this->process($request, $member);
    }

    /**
     * Process stored/updated member.
     *
     * @param \Cortex\Foundation\Http\FormRequest $request
     * @param \Cortex\Auth\Models\Member          $member
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function process(FormRequest $request, Member $member)
    {
        // Prepare required input fields
        $data = $request->validated();

        ! $request->hasFile('profile_picture')
        || $member->addMediaFromRequest('profile_picture')
                ->sanitizingFileName(function ($fileName) {
                    return md5($fileName).'.'.pathinfo($fileName, PATHINFO_EXTENSION);
                })
                ->toMediaCollection('profile_picture', config('cortex.foundation.media.disk'));

        ! $request->hasFile('cover_photo')
        || $member->addMediaFromRequest('cover_photo')
                ->sanitizingFileName(function ($fileName) {
                    return md5($fileName).'.'.pathinfo($fileName, PATHINFO_EXTENSION);
                })
                ->toMediaCollection('cover_photo', config('cortex.foundation.media.disk'));

        // Save member
        $member->fill($data)->save();

        return intend([
            'url' => route('adminarea.cortex.auth.members.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => trans('cortex/auth::common.member'), 'identifier' => $member->getRouteKey()])],
        ]);
    }

    /**
     * List the members.
     *
     * @TODO: to be refactored!
     *
     * @return array
     */
    public function ajax(): array
    {
        return app('cortex.auth.member')->all()->pluck('full_name', 'id')->toArray();
    }

    /**
     * Destroy given member.
     *
     * @param \Cortex\Auth\Models\Member $member
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Member $member)
    {
        $member->delete();

        return intend([
            'url' => route('adminarea.cortex.auth.members.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => trans('cortex/auth::common.member'), 'identifier' => $member->getRouteKey()])],
        ]);
    }
}
