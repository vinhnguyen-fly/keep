<?php namespace Keep\Http\Controllers\Admin;

use Keep\Http\Requests;
use Keep\Http\Controllers\Controller;
use Keep\Http\Requests\UserGroupRequest;
use Keep\Repositories\UserGroup\UserGroupRepositoryInterface;

class UserGroupsController extends Controller {

    protected $groupRepository;

    /**
     * Constructor.
     *
     * @param UserGroupRepositoryInterface $groupRepository
     */
	public function __construct(UserGroupRepositoryInterface $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    /**
     * Get all current groups.
     *
     * @return \Illuminate\View\View
     */
    public function activeGroups()
    {
        $groups = $this->groupRepository->getPaginatedGroups(15);

        return view('admin.groups.active_groups', compact('groups'));
    }

    /**
     * Get the form to create a new group.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.groups.create');
    }

    /**
     * Persist a new group.
     *
     * @param UserGroupRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserGroupRequest $request)
    {
        $this->groupRepository->create($request->all());

        flash()->success('The new group was successfully created.');

        return redirect()->route('admin.active.groups');
    }

    /**
     * Show a specific group.
     *
     * @param $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $group = $this->groupRepository->findBySlug($slug);

        $users = $this->groupRepository->getPaginatedAssociatedUsers($group, 16);

        return view('admin.groups.show', compact('group', 'users'));
    }

    /**
     * Get the form to update a group.
     *
     * @param $slug
     * @return \Illuminate\View\View
     */
    public function edit($slug)
    {
        $group = $this->groupRepository->findBySlug($slug);

        return view('admin.groups.edit', compact('group'));
    }

    /**
     * Update information of a group.
     *
     * @param UserGroupRequest $request
     * @param                  $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserGroupRequest $request, $slug)
    {
        $this->groupRepository->update($slug, $request->all());

        flash()->info('The information of this group was updated.');

        return redirect()->route('admin.active.groups');
    }

    /**
     * Soft delete a group.
     *
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($slug)
    {
        $this->groupRepository->delete($slug);

        flash()->info('This group was successfully sent to trash');

        return redirect()->back();
    }

    /**
     * Restore a trashed group.
     *
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($slug)
    {
        $this->groupRepository->restore($slug);

        flash()->success('This group was successfully restored');

        return redirect()->back();
    }

    /**
     * Get trashed groups.
     *
     * @return \Illuminate\View\View
     */
    public function trashedGroups()
    {
        $trashedGroups = $this->groupRepository->getTrashedGroups();

        return view('admin.groups.trashed_groups', compact('trashedGroups'));
    }

    /**
     * Permanently delete a group.
     *
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDeleteGroup($slug)
    {
        $this->groupRepository->forceDelete($slug);

        flash()->info('This group was permanently deleted.');

        return redirect()->back();
    }

}
