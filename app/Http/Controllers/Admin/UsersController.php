<?php

namespace Keep\Http\Controllers\Admin;

use Keep\Http\Controllers\Controller;
use Keep\Repositories\User\UserRepositoryInterface as UserRepository;

class UsersController extends Controller
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * Get active accounts.
     *
     * @return \Illuminate\View\View
     */
    public function activeAccounts()
    {
        $request = app('request');
        $sortBy = $request->get('sortBy');
        $direction = $request->get('direction');
        $activeMembers = $this->users->fetchPaginatedUsers(compact('sortBy', 'direction'), 100);

        return view('admin.members.active_accounts', compact('activeMembers'));
    }

    /**
     * Get disabled accounts.
     *
     * @return \Illuminate\View\View
     */
    public function disabledAccounts()
    {
        $disabledMembers = $this->users->fetchDisabledUsers(25);

        return view('admin.members.disabled_accounts', compact('disabledMembers'));
    }

    /**
     * Get account profile.
     *
     * @param $slug
     * @return \Illuminate\View\View
     */
    public function profile($slug)
    {
        $user = $this->users->findBySlugEagerLoadTasks($slug);

        return view('admin.members.profile', compact('user'));
    }

    /**
     * Disable an account.
     *
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disableAccount($slug)
    {
        $this->users->softDelete($slug);
        flash()->info(trans('administrator.account_disabled'));

        return redirect()->route('admin::members.active');
    }

    /**
     * Restore a disabled account.
     *
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restoreAccount($slug)
    {
        $this->users->restore($slug);
        flash()->info(trans('administrator.account_restored'));

        return back();
    }

    /**
     * Permanently delete an account.
     *
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDeleteAccount($slug)
    {
        $this->users->forceDelete($slug);
        flash()->info(trans('administrator.account_destroyed'));

        return back();
    }
}
