<?php

namespace App\Http\Controllers\Users;

use App\Entities\Users\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

/**
 * Users Controller.
 *
 * @author Nafies Luthfi <nafiesL@gmail.com>
 */
class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');
        $users = User::where([['name', 'like', '%'.$query.'%'],['is_approved',2]])
            ->with('roles')
            ->paginate(25);

        return view('users.index', compact('users'));
    }

    public function getUsers(){

        $users = User::where('is_approved', 1)
            ->with('roles')
            ->paginate(25);
        return view('users.index', compact('users'));

    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {


        $userData = $request->validate([
            'name'     => 'required|min:5',
            'email'    => 'required|email|unique:users,email',
            'password' => ['nullable', Password::min(6)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
            'role'     => 'required|array',
        ]);

        if ($userData['password']) {
            $password = $userData['password'];
        } else {
            $password = \Option::get('password_default', '123456');
        }

        $userData['password'] = bcrypt($password);
        $userData['api_token'] = Str::random(32);

        Mail::send('auth.account-request-successfull', [
            'name'      => $userData['name'],
            'email'      => $userData['email'],
            'password'      => $password,
            'url'     => config('app.url'),
            'img'     => app_logo_image(['style' => 'margin:20px auto']),
            'organization'     => \Option::get('agency_name'),
        ], function($message) use($userData){
            $message->subject('Account Created in our domain');
            $message->to($userData['email']);
        });

        $user = User::create($userData);

        $rolesData = array_map(function ($roleId) use ($user) {
            return [
                'user_id' => $user->id,
                'role_id' => $roleId,
            ];
        }, $userData['role']);

        \DB::table('user_roles')->insert($rolesData);

        flash(trans('user.created'), 'success');

        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        $userCurrentJobs = $user->jobs()
            ->whereHas('project', function ($query) {
                $query->whereIn('status_id', [2, 3]);
            })->with('tasks')->get();

        return view('users.show', compact('user', 'userCurrentJobs'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $langList = ['en' => trans('lang.en'), 'id' => trans('lang.id')];

        return view('users.edit', compact('user', 'langList'));
    }

    public function approvePending(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $user->is_approved = 2;
        $user->save();

        flash(trans('user.updated'), 'success');

        return redirect(route('users.show', $user->id));
    }
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $userData = $request->validate([
            'name'     => 'required|min:5',
            'email'    => 'required|email|unique:users,email,'.$request->segment(2),
            'password' => 'nullable|required_with:password_confirmation|between:6,15',
            'role'     => 'required|array',
            'lang'     => 'required|string|in:en,id',
        ]);

        if ($userData['password']) {
            $userData['password'] = bcrypt($userData['password']);
        }
        $user->update($userData);

        \DB::table('user_roles')->where(['user_id' => $user->id])->delete();

        $rolesData = array_map(function ($roleId) use ($user) {
            return [
                'user_id' => $user->id,
                'role_id' => $roleId,
            ];
        }, $userData['role']);

        \DB::table('user_roles')->insert($rolesData);

        flash(trans('user.updated'), 'success');

        return redirect()->route('users.edit', $user->id);
    }

    public function delete(User $user)
    {
        $this->authorize('delete', $user);

        return view('users.delete', compact('user'));
    }

    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete', $user);

        $request->validate([
            'user_id' => 'required',
        ]);

        if ($request->get('user_id')) {
            $user->delete();
            flash(trans('user.deleted'), 'success');
        } else {
            flash(trans('user.undeleted'), 'danger');
        }

        return redirect()->route('users.index');
    }
}
