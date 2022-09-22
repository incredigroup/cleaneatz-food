<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePassword;
use App\Http\Requests\StoreUser;
use App\Models\User;
use App\Traits\RendersTableButtons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Str;

class UserController extends Controller
{
    use RendersTableButtons;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = $request->get('role', 'admin');
        return view('admin.users.index', compact('role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();
        $user->role = 'store';
        return view('admin.users.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $input = $request->validated();

        $this->saveUserWithInput(new User(), $input);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User has been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUser $request, User $user)
    {
        $input = $request->validated();

        $this->saveUserWithInput($user, $input);

        return redirect()
            ->route('admin.users.index', ['role' => $user->role])
            ->with('status', 'User has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User has been deleted.');
    }

    public function saveUserWithInput($user, $input)
    {
        $user->fill($input);
        $user->role = $input['role'];
        $user->store_location_id = $input['role'] === 'store' ? $input['store_location_id'] : null;

        if (isset($input['password'])) {
            $user->password = Hash::make($input['password']);
            $user->api_token = Str::random(60);
        }

        $user->save();
    }

    public function data($role)
    {
        return datatables()
            ->of(User::where('role', $role))
            ->addColumn('actions', function ($user) {
                return $this->renderEditDeleteButtons('admin.users', $user->id);
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function password(User $user)
    {
        return view('admin.users.password', compact('user'));
    }

    public function updatePassword(StorePassword $request, User $user)
    {
        $input = $request->validated();

        $user->password = Hash::make($input['password']);
        $user->api_token = Str::random(60);
        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User password has been updated.');
    }
}
