<?php

namespace App\Http\Controllers\Auth;

use App\Entities\Users\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout','getUsers']);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        flash(trans('auth.welcome', ['name' => $request->user()->name]));

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    public function doLogin(Request $request)
    {
        $user = User::where('email',$request->email) -> first();
        if ($user) {
            if ($user->is_approved != 1) {
                return $this->login($request);
            } else {
                flash(trans('User not approved'), 'danger');
                return redirect(route('auth.login'));
            }
        }
        return $this->login($request);
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();
        flash(trans('auth.logged_out'), 'success');

        return redirect(route('auth.login'));
    }

    public function request(){
        return view('auth.request-account');

    }

    public function requestStore(Request $request)
    {

        $userData = $request->validate([
            'name'     => 'required|min:5',
            'email'    => 'required|email|unique:users,email',
            'password' => 'nullable|between:6,15',
            'role'     => 'required|array',
        ]);

        if ($userData['password']) {
            $userData['password'] = bcrypt($userData['password']);
        } else {
            $userData['password'] = bcrypt(\Option::get('password_default', 'member'));
        }

        $userData['api_token'] = Str::random(32);
        $userData['is_approved'] = 1;

        $user = User::create($userData);

        $rolesData = array_map(function ($roleId) use ($user) {
            return [
                'user_id' => $user->id,
                'role_id' => $roleId,
            ];
        }, $userData['role']);

        \DB::table('user_roles')->insert($rolesData);

        flash(trans('user.created'), 'success');

        return back();
    }

}