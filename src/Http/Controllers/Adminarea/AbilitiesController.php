<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Cortex\Auth\Models\Ability;
use Cortex\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Foundation\Importers\InsertImporter;
use Cortex\Foundation\Http\Requests\ImportFormRequest;
use Cortex\Auth\DataTables\Adminarea\AbilitiesDataTable;
use Cortex\Auth\Http\Requests\Adminarea\AbilityFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;
use Cortex\Auth\Http\Requests\Adminarea\AbilityFormProcessRequest;

class AbilitiesController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'cortex.auth.models.ability';

    /**
     * List all abilities.
     *
     * @param \Cortex\Auth\DataTables\Adminarea\AbilitiesDataTable $abilitiesDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(AbilitiesDataTable $abilitiesDataTable)
    {
        return $abilitiesDataTable->with([
            'id' => 'adminarea-cortex-auth-abilities-index',
            'routePrefix' => 'adminarea.cortex.auth.abilities',
            'pusher' => ['entity' => 'ability', 'channel' => 'cortex.auth.abilities.index'],
        ])->render('cortex/foundation::adminarea.pages.datatable-index');
    }

    /**
     * List ability logs.
     *
     * @param \Cortex\Auth\Models\Ability                 $ability
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(Ability $ability, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'resource' => $ability,
            'tabs' => 'adminarea.cortex.auth.abilities.tabs',
            'id' => "adminarea-cortex-auth-abilities-{$ability->getRouteKey()}-logs",
        ])->render('cortex/foundation::adminarea.pages.datatable-tab');
    }

    /**
     * Import abilities.
     *
     * @param \Cortex\Foundation\Http\Requests\ImportFormRequest $request
     * @param \Cortex\Foundation\Importers\InsertImporter        $importer
     * @param \Cortex\Auth\Models\Ability                        $ability
     *
     * @return void
     */
    public function import(ImportFormRequest $request, InsertImporter $importer, Ability $ability)
    {
        $importer->withModel($ability)->import($request->file('file'));
    }

    /**
     * Create new ability.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Auth\Models\Role $ability
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request, Ability $ability)
    {
        return $this->form($request, $ability);
    }

    /**
     * Edit given ability.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\AbilityFormRequest $request
     * @param \Cortex\Auth\Models\Ability                             $ability
     *
     * @return \Illuminate\View\View
     */
    public function edit(AbilityFormRequest $request, Ability $ability)
    {
        return $this->form($request, $ability);
    }

    /**
     * Show ability create/edit form.
     *
     * @param \Illuminate\Http\Request    $request
     * @param \Cortex\Auth\Models\Ability $ability
     *
     * @return \Illuminate\View\View
     */
    protected function form(Request $request, Ability $ability)
    {
        if (! $ability->exists && $request->has('replicate') && $replicated = $ability->resolveRouteBinding($request->input('replicate'))) {
            $ability = $replicated->replicate();
        }

        $roles = $request->user()->getManagedRoles();
        $entityTypes = app('cortex.auth.ability')->distinct()->get(['entity_type'])->pluck('entity_type', 'entity_type')->toArray();

        return view('cortex/auth::adminarea.pages.ability', compact('ability', 'roles', 'entityTypes'));
    }

    /**
     * Store new ability.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\AbilityFormProcessRequest $request
     * @param \Cortex\Auth\Models\Ability                                    $ability
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(AbilityFormProcessRequest $request, Ability $ability)
    {
        return $this->process($request, $ability);
    }

    /**
     * Update given ability.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\AbilityFormProcessRequest $request
     * @param \Cortex\Auth\Models\Ability                                    $ability
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(AbilityFormProcessRequest $request, Ability $ability)
    {
        return $this->process($request, $ability);
    }

    /**
     * Process stored/updated ability.
     *
     * @param \Cortex\Foundation\Http\FormRequest $request
     * @param \Cortex\Auth\Models\Ability         $ability
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function process(FormRequest $request, Ability $ability)
    {
        // Prepare required input fields
        $data = $request->validated();

        // Save ability
        $ability->fill($data)->save();

        return intend([
            'url' => route('adminarea.cortex.auth.abilities.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => trans('cortex/auth::common.ability'), 'identifier' => $ability->getRouteKey()])],
        ]);
    }

    /**
     * Destroy given ability.
     *
     * @param \Cortex\Auth\Models\Ability $ability
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Ability $ability)
    {
        $ability->delete();

        return intend([
            'url' => route('adminarea.cortex.auth.abilities.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => trans('cortex/auth::common.ability'), 'identifier' => $ability->getRouteKey()])],
        ]);
    }
}
