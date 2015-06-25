<?php

namespace Keep\Http\Controllers;

use Keep\Http\Requests\EditUserProfileRequest;
use Keep\Repositories\User\UserRepositoryInterface;

class UsersController extends Controller
{
    protected $userRepo;

    /**
     * Create a new users controller instance.
     *
     * @param UserRepositoryInterface $userRepo
     */
    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
        $this->middleware('auth');
        $this->middleware('auth.correct');
    }

    /**
     * Show profile.
     *
     * @param $slug
     *
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $user = $this->userRepo->findBySlug($slug);

        return view('users.show', compact('user'));
    }

    /**
     * Update profile.
     *
     * @param EditUserProfileRequest $request
     * @param                        $slug
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EditUserProfileRequest $request, $slug)
    {
        $user = $this->userRepo->updateProfile($request->except(['_method', '_token']), $slug);
        flash()->info(trans('controller.profile_updated'));

        return redirect()->route('member::profile', $user);
    }

    /**
     * Cancel account.
     *
     * @param $slug
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($slug)
    {
        $this->userRepo->softDelete($slug);
        flash()->success(trans('controller.account_canceled'));

        return redirect()->home();
    }
}
