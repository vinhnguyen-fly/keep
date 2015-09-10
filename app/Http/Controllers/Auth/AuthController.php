<?php

namespace Keep\Http\Controllers\Auth;

use Keep\Jobs\AuthenticateUser;
use Keep\Jobs\RegisterUserAccount;
use Keep\Jobs\ActivateUserAccount;
use Keep\Http\Controllers\Controller;
use Keep\Http\Requests\RegistrationRequest;
use Keep\Http\Requests\InitializeSessionRequest;
use Keep\Entities\Concerns\Auth\ThrottlesLogins;

class AuthController extends Controller
{
    protected $throttles;

    public function __construct(ThrottlesLogins $throttles)
    {
        $this->throttles = $throttles;
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function getRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request to the application.
     *
     * @param RegistrationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRegister(RegistrationRequest $request)
    {
        if ($this->dispatchFrom(RegisterUserAccount::class, $request)) {
            flash()->info(trans('authentication.account_activation'));
            return redirect()->home();
        }

        return redirect()->route('auth::register');
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param InitializeSessionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(InitializeSessionRequest $request)
    {
        if ($this->throttles->hasTooManyLoginAttempts($request)) {
            return $this->throttles->sendLockoutResponse($request);
        }

        if ($this->dispatchFrom(AuthenticateUser::class, $request, [
            'active'   => 1,
            'remember' => $request->has('remember'),])
        ) {
            flash()->success(trans('authentication.login_success'));
            $this->throttles->clearLoginAttempts($request);
            return redirect()->intended('/');
        }
        session()->flash('login_error', trans('authentication.login_error'));
        $this->throttles->incrementLoginAttempts($request);

        return redirect()->route('auth::login')->withInput($request->only('email', 'remember'));
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        auth()->logout();
        flash()->success(trans('authentication.logout'));

        return redirect()->home();
    }

    /**
     * Activate user account.
     *
     * @param $code
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate($code)
    {
        if ($this->dispatch(new ActivateUserAccount($code))) {
            flash()->success(trans('authentication.activation_success'));
            return redirect()->home();
        }
        flash()->warning(trans('authentication.activation_error'));

        return redirect()->home();
    }
}
